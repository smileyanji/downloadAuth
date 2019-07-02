<?php
/*
 * ===========================================================================
 * 컨텐츠 태그 수정 API 예제
 * ===========================================================================
 *
 * 컨텐츠 태그 수정 : "contents tag update" API통해서 컨텐츠 태그를 수정합니다.
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

$tag = $_POST['tag'] ;

$token = $_POST['token'] ;
if ( ! $token )
	exit ( 'No token' ) ;

/*
* 컨텐츠 태그 수정 API : contents tag update
*/
$re = $AUTH -> tagUpdate ( $token , $contentsKey , $tag ) ;
if ( $re )
	if ( isset ( $re -> Error ) )
		echo $re -> RequestID . ' : ' .$re -> Message ;
	else if ( isset ( $re -> Result ) )
		echo $re -> Result ;
	else
		echo 'Update tag error' ;
else
	echo 'Update tag error' ;
exit ;
?>