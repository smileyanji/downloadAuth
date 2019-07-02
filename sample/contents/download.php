<?php
/*
 * ===========================================================================
 * 컨텐츠 다운로드 url API 예제
 * ===========================================================================
 *
 * 컨텐츠 다운로드 url : "download url select" API통해서 컨텐츠 다운로드 주소를 불러옵니다.
 *
 * ---------------------------------------------------------------------------
 * 작성자: 리성림 <chenglin@smileserv.com>
 * 작성일: 2018년 06월 07일
 * ===========================================================================
 */


/*
 * 프레임워크 파일을 불러옵니다.
 */
include_once '../inc/config.inc' ;

$contentsKey = $_POST['contentsKey'] ;
if ( ! $contentsKey )
	exit ( 'No contents key' ) ;

$token = $_POST['token'] ;
if ( ! $token )
	exit ( 'No token' ) ;

/*
* 컨텐츠 다운로드 url API : download url select
*/
$re = $AUTH -> downloadLink ( $token , $contentsKey ) ;
if ( $re )
	if ( isset ( $re -> Error ) )
		echo $re -> RequestID . ' : ' . $re -> Message ;
	else if ( isset ( $re -> Url ) )
		echo $re -> Url ;
	else
		echo 'Download url error' ;
else
	echo 'Download url error' ;
exit ;
?>