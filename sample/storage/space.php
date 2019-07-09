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

	<div class="div_main">
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
			<div class="item_body">
				<table id="storagSingleTable">
					<tr>
						<th width="45%">스토리지 총용량 ( byte ) : </th>
						<td width="55%"><?=$total ?> byte</td>
					</tr>
					<tr>
						<th>스토리지 남은 용량 ( byte ) : </th>
						<td><?=$rest ?> byte</td>
					</tr>
					<tr>
						<th>스토리지 사용용량 ( byte ) : </th>
						<td><?=$used ?> byte</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</body>
</html>