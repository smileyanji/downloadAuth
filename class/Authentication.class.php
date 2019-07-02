<?php
/*
 * ===========================================================================
 * Authentication 클래스 예제
 * ===========================================================================
 *
 * API서버로 접근 내용을 메소드로 구성해서 include해서 사용하면 됩니다.
 *
 * ---------------------------------------------------------------------------
 * 작성자: 리성림 <chenglin@smileserv.com>
 * 작성일: 2018년 04월 01일
 * ===========================================================================
 */

class Authentication
{
	/**
	 * @var string accesskey ID
	 */
	private static $accesskeyId ;
	/**
	 * @var string accesskey 비번
	 */
	private static $accesskeySecret ;
	/**
	 * @var string API서버 도메인
	 */
	private static $apiDomain ;
	/**
	 * @var string Token 요청주소
	 */
	public static $authenticationtUrl ;
	/**
	 * @var string 컨텐츠 관련 요청주소 ( 업로드 , 검색 , 수정 , 삭제 )
	 */
	public static $contentsUrl ;
	/**
	 * @var string 폴더관련 요청주소 ( 생성 , 검색 )
	 */
	public static $foldersUrl ;
	/**
	 * @var string 스토리지 검색 주소
	 */
	public static $storagesUrl ;
	/**
	 * @var string 다운로드 url 요첟주소
	 */
	public static $downloadLinkUrl ;
	/**
	 * @var string 스토리지키
	 */
	public $storageKey ;
	/**
	 * @var string 폴더키
	 */
	public $folderKey ;
	/**
	 * @var boolean 토큰 유효기간 초과할때 다시 토큰을 요청했는지
	 */
	private $countOvertime ;
	/**
	 * @var string Token
	 */
	public $token ;

	/**
	 * 클래스 생성자
	 * class 기준정보를 /inc/setting.php 파일에서 세팅한다
	 * @param array $setting 기준정보 ( accesskeyId : accesskey ID; accesskeySecret : accesskey 비번; apiDomain : API서버 도메인; storageKey : 스토리지키; folderKey : 폴더키; )
	 */
	function __construct ( $setting )
	{
		self::$accesskeyId = $setting['accesskeyId'] ;
		self::$accesskeySecret = $setting['accesskeySecret'] ;
		self::$apiDomain = $setting['apiDomain'] ;
		$this -> storageKey = $setting['storageKey'] ;
		$this -> folderKey = $setting['folderKey'] ;

		self::$authenticationtUrl = self::$apiDomain . 'authorization' ;
		self::$contentsUrl = self::$apiDomain . 'contents/' ;
		self::$foldersUrl = self::$apiDomain . 'folders/' ;
		self::$storagesUrl = self::$apiDomain . 'storages/' ;
		self::$downloadLinkUrl = self::$apiDomain . 'downloadUrl/' ;
		$this -> countOvertime = FALSE ;
	}

	/**
	 * curl 방식으로 api 서버를 접근하기
	 * @param string $url api주소
	 * @param array $headers 헤더정보
	 * @param string $action HTTP ACTION ( GET , POST , PUT , DELETE )
	 * @return object return정보
	 */
	public static function curl ( $url , $headers , $action , $postData = array () )
	{
		$curl = curl_init () ;
		curl_setopt ( $curl , CURLOPT_URL , $url ) ;
		curl_setopt ( $curl , CURLOPT_HTTPHEADER , $headers ) ;
		curl_setopt ( $curl , CURLOPT_RETURNTRANSFER , true ) ;
		curl_setopt ( $curl , CURLOPT_BINARYTRANSFER , true ) ;
		curl_setopt ( $curl , CURLOPT_REFERER , $_SERVER['SERVER_NAME'] ) ; //client 서버 도메인
		if ( $action == 'POST' )
		{
			$o = '' ;
			foreach ( $postData as $k => $v )
			{
				$o .= "$k=" . urlencode ( $v ) . "&" ;
			}
			$postData = substr ( $o , 0 , -1 ) ;
			curl_setopt ( $curl , CURLOPT_POST , 1 ) ;
			curl_setopt ( $curl , CURLOPT_POSTFIELDS , $postData ) ;
		}
		else
			curl_setopt ( $curl , CURLOPT_CUSTOMREQUEST , $action ) ;
		$re = curl_exec ( $curl ) ;
		curl_close ( $curl ) ;
		return $re ;
	}

	/**
	 * 인증토큰 요청
	 * @return array 인증토큰 ( RequestID : 요청번호 ; Token : 인증토큰 ; Result : 결과 메시지 )
	 */
	public function getToken ()
	{
		$accesskeySecret = password_hash ( self::$accesskeySecret , PASSWORD_DEFAULT ) ;
		$headers[] = 'ACCESSKEYID:' . self::$accesskeyId ;
		$headers[] = "ACCESSKEYSECRET:{$accesskeySecret}" ;
		$headers[] = 'REMOTEADDR:' . $_SERVER['REMOTE_ADDR'] ;
		$reqToken = self::curl ( self::$authenticationtUrl , $headers , 'GET' ) ;
		if ( ! empty ( $reqToken ) )
		{
			$reqToken = json_decode ( $reqToken ) ;
			if ( isset ( $reqToken -> Token ) )
			{
				$this -> token = $reqToken -> Token ;
				return $reqToken -> Token ;
			}
			else
				echo "<script>alert('인증 토큰 생성시 오류발생했습니다.');</script>" ;
		}
		else
			echo "<script>alert('인증 토큰 생성시 오류발생했습니다.');</script>" ;
	}

	/**
	 * 스토리지 총용량 검색
	 * @param string $token 인증토큰
	 * @param string $storageKey 스토리지 키
	 * @return array 남은 용량 ( RequestID : 요청번호 ; TotalStorage : 총용량 ; Result : 결과 메시지 )
	 */
	public function storageTotal ( $token = '' , $storageKey = '' )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : NULL ) ;
		if ( ! $_token )
			return 'No token' ;

		$key = $storageKey ? $storageKey : ($this -> storageKey ? $this -> storageKey : NULL ) ;
		if ( ! $key )
			return 'No storage key' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$storagesUrl . $key . '?action=total' , $headers , 'GET' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ ) ; // $re -> totalStorage
	}

	/**
	 * 스토리지 남은용량 검색
	 * @param string $token 인증토큰
	 * @param string $storageKey 스토리지 키
	 * @return array 남은 용량 ( RequestID : 요청번호 ; RestStorage : 남은 용량 ; Result : 결과 메시지 )
	 */
	public function storageRest ( $token = '' , $storageKey = '' )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : '' ) ;
		if ( ! $_token )
			return 'No token' ;

		$key = $storageKey ? $storageKey : ($this -> storageKey ? $this -> storageKey : NULL ) ;
		if ( ! $key )
			return 'No storage key' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$storagesUrl . $key . '?action=rest' , $headers , 'GET' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ ) ; // $re -> restStorage
	}

	/**
	 * 스토리지 이미사용용량 검색
	 * @param string $token 인증토큰
	 * @param string $storageKey 스토리지 키
	 * @return array 남은 용량 ( RequestID : 요청번호 ; UsedStorage : 사용용량 ; Result : 결과 메시지 )
	 */
	public function storageUsed ( $token = '' , $storageKey = '' )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : '' ) ;
		if ( ! $_token )
			return 'No token' ;

		$key = $storageKey ? $storageKey : ($this -> storageKey ? $this -> storageKey : NULL ) ;
		if ( ! $key )
			return 'No storage key' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$storagesUrl . $key . '?action=used' , $headers , 'GET' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ ) ; // $re -> usedStorage
	}

	/**
	 * 스토리지  검색
	 * @param string $token 인증토큰
	 * @param string $storageKey 스토리지 키
	 * @return array 스토리지  ( RequestID : 요청번호 ; Storages : 스토리지  ; Result : 결과 메시지 )
	 */
	public function storagesSelect ( $token = '' , $storageKey = '' )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : NULL ) ;
		if ( ! $_token )
			return 'No token' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$storagesUrl . $storageKey , $headers , 'GET' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ ) ; // $re -> storages
	}

	/**
	 * 컨텐츠 조회 ( LIST )
	 * @param string $token 인증토큰
	 * @param string $folderKey 폴더키
	 * @return array 컨텐츠 정보list ( RequestID : 요청번호 ; Contents : 컨텐츠 list ; Result : 결과 메시지 )
	 */
	public function contentsListSelect ( $token = '' , $folderKey = '' )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : '' ) ;
		if ( ! $_token )
			return 'No token' ;

		$key = $folderKey ? $folderKey : ($this -> folderKey ? $this -> folderKey : NULL ) ;
		if ( ! $key )
			return 'No token key' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$contentsUrl . $key . '?keyType=list' , $headers , 'GET' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ ) ; // $re -> contents
	}

	/**
	 * 컨텐츠 조회 ( 단건 )
	 * @param string $token 인증토큰
	 * @param string $contentsKey 컨텐츠 키
	 * @return array 컨텐츠 정보 ( RequestID : 요청번호 ; Contents : 컨텐츠 정보 ; Result : 결과 메시지 )
	 */
	public function contentsSelect ( $token = '' , $contentsKey )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : NULL ) ;
		if ( ! $_token )
			return 'No token' ;

		if ( ! $contentsKey )
			return 'No contents key' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$contentsUrl . $contentsKey . '?keyType=single' , $headers , 'GET' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ , $contentsKey ) ; // $re -> contents
	}

	/**
	 * 목록생성
	 * @param string $token 인증토큰
	 * @param string $folderKey 폴더키
	 * @param string $folderName 폴더명
	 * @return array 폴더키 ( RequestID : 요청번호 ; FolderKey : 폴더키 ; Result : 결과 메시지 )
	 */
	public function folderCreate ( $token = '' , $folderKey = '' , $folderName )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : '' ) ;
		if ( ! $_token )
			return 'No token' ;

		$key = $folderKey ? $folderKey : ($this -> folderKey ? $this -> folderKey : '' ) ;
		if ( ! $key )
			return 'No token key' ;

		if ( ! $folderName )
			return 'No folder name' ;

		$headers[] = 'Authorization:' . $_token ;
		$postData = array () ;
		$postData['folderName'] = $folderName ;
		$re = self::curl ( self::$foldersUrl . $key , $headers , 'POST' , $postData ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ ) ; // $re -> folders
	}

	/**
	 * 목록 조회
	 * @param string $token 인증토큰
	 * @param string $action list검색 ( list )
	 * @param string $folderKey 폴더키 ( list검색 경우에 상위 폴더키  )
	 * @return array 목록 정보 list ( RequestID : 요청번호 ; Folders : 목록 정보 list ; Result : 결과 메시지 )
	 */
	public function foldersSelect ( $token = '' , $action = '' , $folderKey = '' )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : '' ) ;
		if ( ! $_token )
			return 'No token' ;

		$key = $folderKey ? $folderKey : ($this -> folderKey ? $this -> folderKey : NULL ) ;
		if ( ! $key )
			return 'No folder key' ;

		$headers[] = 'Authorization:' . $_token ;
		if ( $action == 'list' )
			$re = self::curl ( self::$foldersUrl . $key . '?action=' . $action , $headers , 'GET' ) ;
		else
			$re = self::curl ( self::$foldersUrl . $key , $headers , 'GET' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ ) ; // $re -> folders
	}

	/**
	 * 컨텐츠 삭제 ( 멀티 )
	 * @param string $token 인증토큰
	 * @param array $contentsKeys 컨텐츠 키 ( 멀티 )
	 * @return array 결과 정보 ( RequestID : 요청번호 ; Result : 결과 메시지 )
	 */
	public function contentsDelete ( $token = '' , $contentsKeys )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : '' ) ;
		if ( ! $_token )
			return 'No token' ;

		if ( ! $contentsKeys )
			return 'No contents key' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$contentsUrl . json_encode ( $contentsKeys ) , $headers , 'DELETE' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ , $contentsKeys ) ;
	}

	/**
	 * 태그 수정
	 * @param string $token 인증토큰
	 * @param  string $contentsName 컨텐츠 키
	 * @param  string $contentsName 컨텐츠 명
	 * @return array 결과 정보 ( RequestID : 요청번호 ; Result : 결과 메시지 )
	 */
	public function contentsNameUpdate ( $token = '' , $contentsKey , $contentsName )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : NULL ) ;
		if ( ! $_token )
			return 'No token' ;

		if ( ! $contentsKey )
			return 'No contents key' ;

		if ( ! $contentsName )
			return 'No contents name' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$contentsUrl . $contentsKey . '?action=name&contentsName=' . urlencode ( $contentsName ) , $headers , 'PUT' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ , $contentsKey , $contentsName ) ;
	}

	/**
	 * 태그 수정
	 * @param string $token 인증토큰
	 * @param  string $contentsKey 컨텐츠 키
	 * @param  string $tag 태그내용
	 * @return array 결과 정보 ( RequestID : 요청번호 ; Result : 결과 메시지 )
	 */
	public function tagUpdate ( $token = '' , $contentsKey , $tag = '' )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : NULL ) ;
		if ( ! $_token )
			return 'No token' ;

		if ( ! $contentsKey )
			return 'No contents key' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$contentsUrl . $contentsKey . '?action=tag&tag=' . urlencode ( $tag ) , $headers , 'PUT' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ , $contentsKey , $tag ) ;
	}

	/**
	 * 다운로드 주소 요청
	 * @param string $token 인증토큰
	 * @param string $contentsKey 컨텐츠 키
	 * @return array 다운로드url ( RequestID : 요청번호 ; Url : 다운로드url ; Result : 결과 메시지 )
	 */
	public function downloadLink ( $token = '' , $contentsKey )
	{
		$_token = $token ? $token : ($this -> token ? $this -> token : NULL ) ;
		if ( ! $_token )
			return 'No token' ;

		if ( ! $contentsKey )
			return 'No contents key' ;

		$headers[] = 'Authorization:' . $_token ;
		$re = self::curl ( self::$downloadLinkUrl . $contentsKey , $headers , 'GET' ) ;
		return $this -> returnMsg ( $re , __FUNCTION__ , $contentsKey ) ; // $re -> url
	}

	/**
	 * 결과 처리
	 * @param array 결과정보
	 * @param string $functionName 호출function 이름
	 * @param string $param1 파라미터
	 * @param string $param2 파라미터
	 * @return array 티코드 된 결과정보
	 */
	public function returnMsg ( $response , $functionName , $param1 = '' , $param2 = '' )
	{
		if ( ! $response )
			return FALSE ;
		$response = json_decode ( $response ) ;
		if ( $this -> overtime ( $response -> Result ) )
		{
			//$param구성
			$param = array () ;
			if ( $param1 )
				array_push ( $param , $param1 ) ;
			if ( $param2 )
				array_push ( $param , $param2 ) ;
			//세토큰로 다시function호출하기
			return $this -> countOvertime ? NULL : call_user_func_array ( array ( $this , $functionName ) , $param ) ;
		}
		return $response ;
	}

	/**
	 *  토큰 유효시간 초과할때 다시요청 ( 한번만 )
	 * @param string $msg API return 메시지
	 * @return bool TRUE:다시요청 완료 ; FALSE:거절 ( 이미 다시요청했음 )
	 */
	public function overtime ( $msg )
	{
		if ( $msg == 'InvalidToken.Expired' )
		{
			$reqToken = json_decode ( $this -> getToken () ) ;
			$this -> countOvertime = TRUE ;
			return TRUE ;
		}
		else
		{
			$this -> countOvertime = FALSE ;
			return FALSE ;
		}
	}

}
