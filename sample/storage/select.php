<?php
/**
 * 스토리지 검색
 * @package storage
 */
include_once '../inc/config.inc' ;

/*
 * Token 생성
 */
$AUTH -> getToken () ;

$title = 'Select - Storage' ;
?>

<?php include_once INC . DIRECTORY_SEPARATOR . 'header.inc' ; ?>

	<div class="div_main">
		<div class="item">
			<h3>소트리지 목록 리스트 : </h3>
			<?php
			/*
			 * 스토리지list 검색
			 */
			$storages = $AUTH -> storagesSelect ( $AUTH -> token ) ;
			if ( $storages && isset ( $storages -> Storages ) )
			{
				$storages = $storages -> Storages ;
			?>
			<div class="item_body">
				<table name="storagesTable" >
				<thead>
				<tr>
					<th width="9%">소트리지명</th>
					<th width="38%">소트리지키</th>
					<th width="10%">소트리지 총용량</th>
					<th width="10%">소트리지 남은용량</th>
					<th width="8%">소트리지 사용률</th>
					<th width="6%">디폴트</th>
					<th width="10%">생성시간</th>
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
						. "<td>{$val -> available}</td>"
						. "<td>{$val -> percent}%</td>"
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
		</div>

		<div class="item">
			<h3> 스토리지 정보 : </h3>
			<?php
			/*
			 * 스토리지 정보 검색
			 */
			$storage = $AUTH -> storagesSelect ( $AUTH -> token , $AUTH -> storageKey ) ;
			if ( $storage && isset ( $storage -> Storage ) )
			{
				$storage = $storage -> Storage ;
			?>
			<div class="item_body">
				<table name="storageTable">
					<tr>
						<th class="td_bold">스토리지명 : </th>
						<td><?=$storage -> name ?></td>
					</tr>
					<tr>
						<th class="td_bold">스토리지키 : </th>
						<td><?=$storage -> folder_key ?></td>
					</tr>
					<tr>
						<th class="td_bold">스토리지 총용량 : </th>
						<td><?=$storage -> hdd ?></td>
					</tr>
					<tr>
						<th class="td_bold">스토리지 남은용량 : </th>
						<td><?=$storage -> available ?></td>
					</tr>
					<tr>
						<th class="td_bold">소트리지 사용률 : </th>
						<td><?=$storage -> percent ?>%</td>
					</tr>
					<tr>
						<th class="td_bold">디폴트 : </th>
						<td><?=$storage -> name ?></td>
					</tr>
					<tr>
						<th class="td_bold">생성시간 : </th>
						<td><?=$storage -> date_insert ?></td>
					</tr>
				</table>
				<?php
				}
				else
					echo "<script>	alert( '스토리지 조회할때 오류 발생했습니다.' );</script>" ;
				?>
			</div>
		</div>
	</div>
</body>
</html>