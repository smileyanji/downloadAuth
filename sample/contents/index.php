<?php
/*
 * ===========================================================================
 * 컨텐츠 관련 API 예제
 * ===========================================================================
 *
 * 컨텐츠목록 : "contents list select" API통해서 컨텐츠 정보를 API서버에서 불러옵니다.
 * 컨텐츠 업로드 : "contents upload" API통해서 컨텐츠 API서버로 업로드 합니다.
 * 컨텐츠뎡 수정 : "contents name update" API통해서 컨텐츠명을 수정합니다.
 * 컨텐츠 태그 수정 : "contents tag update" API통해서 컨텐츠 태그를 수정합니다.
 * 컨텐츠 삭제 : "contents delete" API통해서 컨텐츠를 삭제합니다.
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

/*
 * 폴더키를 지정할수 있습니다.
 */
isset ( $_GET['key'] ) ? $AUTH -> folderKey = $_GET['key'] : '' ;

/*
 * Token 생성
 */
$AUTH -> getToken () ;

$title = 'Contents - Demo' ;
?>

<?php include_once INC . DIRECTORY_SEPARATOR . 'header.inc' ; ?>

	<input type="hidden" id="token" value="<?= $AUTH -> token ; ?>">

	<div class="item">
		<?php

		/*
		* 컨텐츠 가져오기 API : contents list select
		*/
		$contentsList = $AUTH -> contentsListSelect ( $AUTH -> token ) ;

		if ( isset ( $contentsList -> Contents ) )
			$contentsList = $contentsList -> Contents ;
		else
		{
			$contentsList = '' ;
			echo "<script>	alert( '컨텐츠 조회 할때 오류 발생했습니다.' );</script>" ;
		}
		?>
		<h3>컨텐츠 목록 : </h3>
		<table name="contentsTable">
		<colgroup>
			<col width="5%">
			<col width="38%">
			<col width="10%">
			<col width="10%">
			<col width="15%">
			<col width="12%">
			<col width="10%">
		</colgroup>
		<thead>
		<tr>
			<th><input type="checkbox" name="ckbAll"></th>
			<th>컨텐츠명</th>
			<th>컨텐츠 크기</th>
			<th>업로드 구분</th>
			<th>태그</th>
			<th>생성 시간</th>
			<th>다운로드</th>
		</tr>
		</thead>
		<tbody>
			<?
			if ( $contentsList )
			{
				foreach ( $contentsList as $k => $val )
				{
					echo "<tr>"
					. "<td><input type='checkbox' name='ckbContents' value='{$k}'></td>"
					. "<td title='{$val -> name}'>{$val -> name}</td>"
					. "<td>{$val -> size}</td>"
					. "<td>{$val -> branch}</td>"
					. "<td>{$val -> tag}</td>"
					. "<td>{$val -> date_insert}</td>"
					. "<td><a href='javascript:download(\"{$k}\")'>다운로드</a></td>"
					. "</tr>" ;
				}
			}
			else
			{
				echo "<tr>"
				. "<td colspan=7>"
				. "<span>컨텐츠가 없습니다.</span>"
				. "</td>"
				. "</tr>" ;
			}
			?>
		</tbody>
		</table>
	</div>

	<div class="item">
		<h3> 컨텐츠 업로드 : </h3>
		<form name="formUpload" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="token" value="<?= $AUTH -> token ?>">
			<input type="text" name="tag"  class="tag" value="origenal,free">
			<input type="file" name="contents[]"  id="contentsFile" multiple="multiple" >
		</form>
		<button  type="button" name="btnUpload">업로드 시작</button>
		<progress name="progressBar" value="0" max="100"> </progress>
	</div>

	<div class="item">
		<h3> 이름 수정 : </h3>
		<input type="text" name="nameUpdate"></input>
		<button type="button" name="nameUpdate">수 정</button>
	</div>

	<div class="item">
		<h3> 태그 수정 : </h3>
		<input type="text" name="tagUpdate"></input>
		<button type="button" name="tagUpdate">수 정</button>
	</div>

	<div class="item">
		<h3> 컨텐츠 삭제 : </h3>
		<button type="button" name="contentsDelete">삭 제</button>
	</div>

</body>

<!-- jQuery -->
<script src="<?=DOMAIN ?>/sample/jquery.min.js" type="text/javascript"></script>

<script>
<?php
/*
* 남은 용량 (byte) API : rest storage select
*/
$rest = $AUTH -> storageRest ( $AUTH -> token , $AUTH -> storageKey ) ;
if ( isset ( $rest -> RestStorage ) )
	$rest = $rest -> RestStorage ;
?>

var rest = "<?= $rest ?>" ;
if ( isNaN ( rest ) )
{
	alert ( "남은용량 가져올때 오류 발생했습니다." ) ;
	rest = 0 ;
}

var restCheck = true ;

$ ( document ).ready ( function () {
	/*
	* 남은 용량 check
	*/
	$ ( '#contentsFile' ).change ( function ( e ) {
		var fileMsg = e.currentTarget.files ;
		var totailSize = 0 ;
		for ( var i = 0 ; i < fileMsg.length ; i ++ )
		{
			totailSize += fileMsg[i].size ;
		}
		if ( totailSize > rest )
		{
			alert ( "스토리지 남은공간 부족입니다." ) ;
			restCheck = true ;
		}
		else
			restCheck = false ;
	} ) ;


	$ ( 'input[name="ckbAll"]' ).on ( "click" , function () {
		 if ( $ ( this ).is ( ':checked' ) )
		{
			$ ( 'input[name="ckbContents"]' ).each ( function () {
				$ ( this ).prop ( "checked" , true ) ;
			} ) ;
		 }
		else
		{
			$ ( 'input[name="ckbContents"]' ).each ( function () {
				$ ( this ).prop ( "checked" , false ) ;
			} ) ;
		}
	 } ) ;

	/*
	* 컨텐츠 업로드 API : contents upload
	*/
	$( "button[name=btnUpload]" ) . click ( function () {
		var fileInput = $ ( '#contentsFile' ).get ( 0 ).files[0] ;
		if ( ! fileInput )
		{
			alert ( "업로드할 파일을 선택해주세요." ) ;
			return ;
		}
		if ( restCheck )
		{
			alert ( "스토리지 남은공간 부족입니다." ) ;
			return ;
		}

		var form = new FormData ( document.getElementsByName ( "formUpload" )[0] ) ;
		$.ajax ( {
			url : "<? echo $AUTH::$contentsUrl . $AUTH -> folderKey ?>" ,
			type : "POST" ,
			data : form ,
			dataType : "json" ,
			cache : false ,
			processData : false ,
			contentType : false ,
			xhr : function () {
				myXhr = $.ajaxSettings.xhr () ;
				if ( myXhr.upload ) { // check if upload property exists
					myXhr.upload.addEventListener ( 'progress' , function ( e ) {
						var progressBar = $ ( "progress[name=progressBar]" ) ;
						progressBar.prop ( 'max' , e.total ) ;
						progressBar.val ( e.loaded ) ;
					} , false ) ;
				}
				return myXhr ;
			} ,
			success : function ( data )	{
				if ( typeof ( data . Result ) == "undefined" )
				{
					alert ( "Upload error" ) ;
				}
				else if ( ( data . Result ) . endsWith ( "uploaded" ) )
				{
					console . log ( data . ContentsList ) ;
					alert ( data . Result ) ;
					location.href = location . href ;
				}
				else
				{
					alert ( data . Result ) ;
				}

			} ,
			error : function ( e ) {
				console.log ( e ) ;
			}
		} ) ;
	})

	/*
	* 컨텐츠 삭제 API : contents delete
	*/
	$ ( "button[name=contentsDelete]" ).click ( function () {
		var checked = $ ( "input[name=ckbContents]:checked" ) ;
		if ( checked.length < 1 )
		{
			alert ( "선택된 컨텐츠가 없습니다." ) ;
			return ;
		}
		var contentsKeys = [ ] ;
		checked.each ( function () {
			contentsKeys.push ( $ ( this ).val () ) ;
		} ) ;
		$.ajax ( {
			url : "delete.php" ,
			type : "post" ,
			data : {
				contentsKeys : contentsKeys ,
				token : $ ( "#token" ) . val()
			} ,
			success : function ( data )
			{
				alert ( data ) ;
				if ( data . endsWith ( "success" ) ) {
					checked.each ( function () {
						$ ( this ).parent().parent().remove() ;
					} ) ;
					if( $( "input[name=ckbContents]" ).length == 0 )
					{
						$ ( "table[name=contentsTable]" ) .find( "tbody" ) . append( "<tr>"
						+ "<td colspan=7>"
						+ "<span>컨텐츠가 없습니다.</span>"
						+ "</td>"
						+ "</tr>" );
					}
				}
			} ,
			error : function ( e )
			{
				alert("컨텐츠 삭제중에 문제발생했습니다.");
				console.log ( e ) ;
			}
		} ) ;
	} ) ;

	/*
	* 컨텐츠명 수정 API : contents name update
	*/
	$ ( "button[name=nameUpdate]" ).click ( function () {
		var checked = $ ( "input[name=ckbContents]:checked" ) ;
		if ( checked.length < 1 )
		{
			alert ( "선택된 컨텐츠가 없습니다." ) ;
			return ;
		}
		if ( checked.length > 1 )
		{
			alert ( "컨텐츠가 하나만 선택하세요." ) ;
			return ;
		}
		var contentsKey = checked.val () ;
		var nameInput = $ ( "input[name=nameUpdate]" ) ;
		var name = nameInput.val () ;
		$.ajax ( {
			url : "updateName.php" ,
			type : "post" ,
			data : {
				contentsKey : contentsKey ,
				contentsName : name ,
				token : $ ( "#token" ) . val()
			} ,
			success : function ( data )
			{
				alert ( data ) ;
				if ( data == "Contents name update success" )
				{
					checked.parent ().next ().html ( name ) ;
					checked.prop ( "checked" , false ) ;
					nameInput.val( '' );
				}
			} ,
			error : function ( e )
			{
				alert("컨텐츠 이름 수정중에 문제발생했습니다.");
				console.log ( e ) ;
			}
		} ) ;
	} ) ;

	/*
	* 컨텐츠 태그 수정 API : contents tag update
	*/
	$ ( "button[name=tagUpdate]" ) . click ( function () {
		var checked = $ ( "input[name=ckbContents]:checked" ) ;
		if ( checked.length < 1 )
		{
			alert ( "선택된 컨텐츠가 없습니다." ) ;
			return ;
		}
		if ( checked.length > 1 )
		{
			alert ( "컨텐츠가 하나만 선택하세요." ) ;
			return ;
		}
		var contentsKey = checked.val () ;
		var tagInput = $ ( "input[name=tagUpdate]" ) ;
		var tag = tagInput.val () ;
		$.ajax ( {
			url : "updateTag.php" ,
			type : "post" ,
			data : {
				contentsKey : contentsKey ,
				tag : tag ,
				token : $ ( "#token" ) . val()
			} ,
			success : function ( data )
			{
				alert ( data ) ;
				if ( data == "Contents tag update success" )
				{
					checked.parent ().next ().next ().next ().next ().html ( tag ) ;
					checked.prop ( "checked" , false ) ;
					tagInput.val( '' );
				}
			} ,
			error : function ( e )
			{
				alert("태그 수정중에 문제발생했습니다.");
				console.log ( e ) ;
			}
		} ) ;
	} ) ;
} ) ;

/*
* 컨텐츠 삭제 API : contents delete
*/
function download ( key ) {
	if ( ! confirm ( "다운로드 하시겠습니까?" ) )
		return;
	$.ajax ( {
		url : "download.php" ,
		type : "post" ,
		data : {
			contentsKey : key ,
			token : $ ( "#token" ) . val()
		} ,
		success : function ( data )
		{
			var reg=/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(([A-Za-z0-9-~]+).)+([A-Za-z0-9-~\/])+$/;
			if ( data == 'closed' )
				alert ( '비공개 컨텐츠입니다.' ) ;
			else if ( !reg . test ( data ) )
				alert ( data ) ;
			else
				location.href = data ;
		} ,
		error : function ( e )
		{
			alert("다운로드중에 문제발생했습니다.");
			console.log ( e ) ;
		}
	} ) ;
}

</script>
</html>