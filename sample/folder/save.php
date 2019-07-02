<?php
/*
 * ===========================================================================
 * 폴더생성 API 예제
 * ===========================================================================
 *
 * 폴더생성 : "folder create" API통해서 폴더를 생성합니다.
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

$folderName = $_POST['folderName'] ;
if ( ! $folderName )
	exit ( 'Empty folder name' ) ;

$folderKey = $_POST['folderKey'] ;
if ( ! $folderKey )
	exit ( 'Empty folder key' ) ;

$token = $_POST['token'] ;
if ( ! $token )
	exit ( 'No token' ) ;

/*
* 폴더생성 API : folder create
*/
$re = $AUTH -> folderCreate ( $token , $folderKey , $folderName ) ;
if ( $re )
	if ( isset ( $re -> Error ) )
		echo $re -> RequestID . ' : ' . $re -> Message ;
	else if ( isset ( $re -> Result ) )
		echo $re -> Result ;
	else
		echo 'Folder create error' ;
else
	echo 'Folder create error' ;
exit ;
?>