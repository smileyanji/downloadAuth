<?php
/**
 * 스토리지 용량 검색
 * @package storage
 */
include_once '../inc/config.inc' ;

//Token 생성
$AUTH -> getToken () ;

$title = 'Space - Storage' ;
?>

<?php include_once INC . DIRECTORY_SEPARATOR . 'header.inc' ; ?>

	<div class="item">
		<h3> 스토리지 용량 정보  : </h3>
		<?php
		// 총용량 (byte)
		$total = $AUTH -> storageTotal ( $AUTH -> token , $AUTH -> storageKey ) ;
		if ( isset ( $total -> TotalStorage ) )
			$total = $total -> TotalStorage ;

		// 남은 용량 (byte)
		$rest = $AUTH -> storageRest ( $AUTH -> token , $AUTH -> storageKey ) ;
		if ( isset ( $rest -> RestStorage ) )
			$rest = $rest -> RestStorage ;

		// 사용 용량 (byte)
		$used = $AUTH -> storageUsed ( $AUTH -> token , $AUTH -> storageKey ) ;
		if ( isset ( $used -> UsedStorage ) )
			$used = $used -> UsedStorage ;
		?>
		<table id="storagSingleTable">
			<tr>
				<td class="td_bold">스토리지 총용량 ( byte ) : </td>
				<td><?=$total ?></td>
			</tr>
			<tr>
				<td class="td_bold">스토리지 남은 용량 ( byte ) : </td>
				<td><?=$rest ?></td>
			</tr>
			<tr>
				<td class="td_bold">스토리지 사용용량 ( byte ) : </td>
				<td><?=$used ?></td>
			</tr>
		</table>
	</div>
</body>
</html>