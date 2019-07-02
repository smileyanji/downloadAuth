<?php
/**
 * 스토리지 검색
 * @package storage
 */
include_once '../inc/config.inc' ;

//Token 생성
$AUTH -> getToken () ;

$title = 'Select - Storage' ;
?>

<?php include_once INC . DIRECTORY_SEPARATOR . 'header.inc' ; ?>

	<div class="item">
		<h3>소트리지 목록 ( list ) : </h3>
		<?php
		//스토리지list 가져오기
		$storages = $AUTH -> storagesSelect ( $AUTH -> token ) ;
		if ( $storages && isset ( $storages -> Storages ) )
		{
			$storages = $storages -> Storages ;
		?>
		<table name="storagesTable" >
		<thead>
		<tr>
			<th width="10%">소트리지명</th>
			<th width="30%">소트리지키</th>
			<th width="15%">소트리지 총용량</th>
			<th width="15%">소트리지 사용용량</th>
			<th width="7%">디폴트</th>
			<th width="15%">생성시간</th>
		</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $storages as $val )
			{
				$trSytle = "" ;
				if ( $AUTH -> storageKey == $val -> storage_key )
					$trSytle = "style='font-weight:bolder'" ;
				echo "<tr {$trSytle}>"
				. "<td>{$val -> name}</td>"
				. "<td>{$val -> storage_key}</td>"
				. "<td>{$val -> hdd}</td>"
				. "<td>{$val -> current}</td>"
				. "<td>{$val -> active}</td>"
				. "<td>{$val -> date_insert}</td>"
				. "</tr>" ;
			}
			?>
		</tbody>
		</table>
		<?php
		}
		else
			echo "<script>	alert( '스토리지 조회할때 오류 발생했습니다.' );</script>" ;
		?>
	</div>

	<div class="item">
		<h3> 스토리지 정보 : </h3>
		<?php
		//스토리지 정보 가져오기
		$storage = $AUTH -> storagesSelect ( $AUTH -> token , $AUTH -> storageKey ) ;
		if ( $storage && isset ( $storage -> Storage ) )
		{
			$storage = $storage -> Storage ;
		?>
		<table name="storageTable">
			<tr>
				<td class="td_bold">스토리지명 : </td>
				<td><?=$storage -> name ?></td>
			</tr>
			<tr>
				<td class="td_bold">스토리지키 : </td>
				<td><?=$storage -> folder_key ?></td>
			</tr>
			<tr>
				<td class="td_bold">스토리지 총용량 : </td>
				<td><?=$storage -> hdd ?></td>
			</tr>
			<tr>
				<td class="td_bold">스토리지 사용용량 : </td>
				<td><?=$storage -> current ?></td>
			</tr>
			<tr>
				<td class="td_bold">디폴트 : </td>
				<td><?=$storage -> name ?></td>
			</tr>
			<tr>
				<td class="td_bold">생성시간 : </td>
				<td><?=$storage -> date_insert ?></td>
			</tr>
		</table>
		<?php
		}
		else
			echo "<script>	alert( '스토리지 조회할때 오류 발생했습니다.' );</script>" ;
		?>
	</div>
</body>
</html>