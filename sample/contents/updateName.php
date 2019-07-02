<?php
/*
 * ===========================================================================
 * 컨텐츠명 수정 API 예제
 * ===========================================================================
 *
 * 컨텐츠뎡 수정 : "contents name update" API통해서 컨텐츠명을 수정합니다.
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

$name = $_POST['contentsName'] ;
if ( ! $name )
	exit ( 'No contents name' ) ;

$token = $_POST['token'] ;
if ( ! $token )
	exit ( 'No token' ) ;

/*
* 컨텐츠명 수정 API : contents name update
*/
$re = $AUTH -> contentsNameUpdate ( $token , $contentsKey , $name ) ;
if ( $re )
	if ( isset ( $re -> Error ) )
		echo $re -> RequestID . ' : ' . $re -> Message ;
	else if ( isset ( $re -> Result ) )
		echo $re -> Result ;
	else
		echo 'Update contents name error' ;
else
	echo 'Update contents name error' ;
exit ;
?>