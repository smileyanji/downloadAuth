<?php
/*
 * ===========================================================================
 * 컨텐츠 삭제 API 예제
 * ===========================================================================
 *
 * 컨텐츠 삭제 : "contents delete" API통해서 컨텐츠를 삭제합니다.
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

$deleteArray = $_POST['contentsKeys'] ;
if ( empty ( $deleteArray ) )
	exit ( 'No contents keys' ) ;

$token = $_POST['token'] ;
if ( ! $token )
	exit ( 'No token' ) ;

/*
 * 컨텐츠 삭제 API : contents delete
 */
$re = $AUTH -> contentsDelete ( $token , $deleteArray ) ;
if ( $re )
	if ( isset ( $re -> Error ) )
		echo $re -> RequestID . ' : ' . $re -> Message ;
	else if ( isset ( $re -> Result ) )
		echo $re -> Result ;
	else
		echo 'Contents delete error' ;
else
	echo 'Contents delete error' ;
exit ;
?>