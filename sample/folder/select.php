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

	<div class="item">
		<?php
		//폴더정보 조회
		$folder = $AUTH -> foldersSelect ( $AUTH -> token , '' , $AUTH -> folderKey ) ;
		if ( isset ( $folder -> Folders ) )
		{
			$folder = $folder -> Folders ;
		?>
		<h3> 폴더 정보 : </h3>
		<table id="folderSingleTable">
			<tr>
				<td class="td_bold">폴더명 : </td>
				<td><?=$folder[0] -> name ?></td>
			</tr>
			<tr>
				<td class="td_bold">폴더키 : </td>
				<td><?=$folder[0] -> folder_key ?></td>
			</tr>
			<tr>
				<td class="td_bold">생성기간 : </td>
				<td><?=$folder[0] -> date_insert ?></td>
			</tr>
		</table>
		<?php
		}
		else
			echo "<script>	alert( '폴더 조회할때 오류 발생했습니다.' );</script>" ;
		?>
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
		<h3>폴더 목록 ( list ) : </h3>
		<table name="foldersTable">
		<thead>
		<tr>
			<th width="40%">폴더명</th>
			<th width="40%">폴더키</th>
			<th width="20%">생성시간</th>
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
</body>
</html>
