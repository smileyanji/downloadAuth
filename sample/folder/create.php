<?php
/*
 * ===========================================================================
 * 폴더 관련 API 예제
 * ===========================================================================
 *
 * 컨텐츠목록 : "contents list select" API통해서 컨텐츠 정보를 API서버에서 불러옵니다.
 * 컨텐츠 업로드 : "contents upload" API통해서 컨텐츠 API서버로 업로드 합니다.
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

//Token 생성
$AUTH -> getToken () ;

$title = 'Create - Folder' ;
?>

<?php include_once INC . DIRECTORY_SEPARATOR . 'header.inc' ; ?>

	<div class="div_main">
		<input type="hidden" id="token" value="<?= $AUTH -> token ?>">

		<div class="item">
			<h3> 폴더생성 : </h3>
			<div class="item_body">
				<table id="folderCreateTable">
					<tr>
						<th>상위 폴더키 : </th>
						<td><input type="text" name="inputFolderKey" value="<?=$AUTH -> folderKey ?>"></input></td>
					</tr>
					<tr>
						<th>폴더명 : </th>
						<td><input type="text" name="inputFolderName" vaue=""></input></td>
					</tr>
					<tr>
						<td colspan=2 ><button type="button" name="btnFolderCreate">생 성</button></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</body>

<!-- jQuery -->
<script src="<?=DOMAIN ?>/sample/jquery.min.js" type="text/javascript"></script>

<script>
$ ( document ).ready ( function () {
	//폴더 생성
	$( "button[name=btnFolderCreate]" ) . click ( function () {
		var nameInput = $ ( "input[name=inputFolderName]" ) ;
		var name = nameInput.val () ;
		var keyInput = $ ( "input[name=inputFolderKey]" ) ;
		var key = keyInput.val () ;
		$.ajax ( {
			url : "save.php" ,
			type : "post" ,
			data : {
				folderName : name,
				folderKey : key,
				token : $ ( "#token" ) . val()
			} ,
			success : function ( data )
			{
				alert ( data ) ;
				if ( data == "Folder create success" )
					nameInput . val ( '' ) ;
			} ,
			error : function ( e )
			{
				alert("컨텐츠 이름 수정중에 문제발생했습니다.");
				console.log ( e ) ;
			}
		} ) ;
	})
})
</script>

</html>

