<?php
/**
 * 폴더 검색
 * @package folder
 */
include_once '../inc/config.inc' ;

// 폴더키
isset ( $_GET['key'] ) ? $AUTH -> folderKey = $_GET['key'] : '' ;

//Token 생성
$AUTH -> getToken () ;

$title = 'Select - Folder' ;
?>

<?php include_once INC . DIRECTORY_SEPARATOR . 'header.inc' ; ?>

	<div class="div_main">
		<div class="item">
			<?php
			//폴더정보 조회
			$folder = $AUTH -> foldersSelect ( $AUTH -> token , '' , $AUTH -> folderKey ) ;
			if ( isset ( $folder -> Folders ) )
			{
				$folder = $folder -> Folders ;
			?>
			<h3> 폴더 정보 : </h3>

			<div class="item_body">
				<table>
					<tr>
						<th width="25%">폴더명 : </th>
						<td width="75%"><?=$folder[0] -> name ?></td>
					</tr>
					<tr>
						<th>폴더키 : </th>
						<td><?=$folder[0] -> folder_key ?></td>
					</tr>
					<tr>
						<th>생성기간 : </th>
						<td><?=$folder[0] -> date_insert ?></td>
					</tr>
				</table>
				<?php
				}
				else
					echo "<script>	alert( '폴더 조회할때 오류 발생했습니다.' );</script>" ;
				?>
			</div>
		</div>

		<div class="item">
			<?php
			//폴더list 조회
			$folders = $AUTH -> foldersSelect ( $AUTH -> token , 'list' , $AUTH -> folderKey ) ;
			if ( isset ( $folders -> Folders ) )
				$folders = $folders -> Folders ;
			else
				echo "<script>	alert( '폴더list 조회할때 오류 발생했습니다.' );</script>" ;
			?>
			<h3>폴더 목록 리스트 : </h3>

			<div class="item_body">
				<table>
				<thead>
				<tr>
					<th width="26%">폴더명</th>
					<th width="57%">폴더키</th>
					<th width="19%">생성시간</th>
				</tr>
				</thead>
				<tbody>
					<?
					if ( is_array ( $folders ) )
					{
						foreach ( $folders as $val )
						{
							echo "<tr name='foldersName' data-key='{$val -> folder_key}'>"
							. "<td>{$val -> name}</td>"
							. "<td>{$val -> folder_key}</td>"
							. "<td>{$val -> date_insert}</td>"
							. "</tr>" ;
						}
					}
					else
					{
						echo "<tr>"
						. "<td colspan=3>"
						. "<span>목록 없습니다.</span>"
						. "</td>"
						. "</tr>" ;
					}
					?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>