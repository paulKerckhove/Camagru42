<?php
include_once 'db_conx.php';
include_once 'check_login_status.php';
include_once 'includes/header.php';
include_once 'includes/footer.php';

?><?php
$sql = "SELECT * FROM photos WHERE id=".ceil($_GET['id'])." LIMIT 1";
$query = mysqli_query($db_conx, $sql);
$data = mysqli_fetch_assoc($query);
if (isset($_POST['submit'])){
$comm = htmlspecialchars($_POST['new_comment']);
		$sql = "INSERT INTO comments VALUES (NULL, ".$data['id'].", ".$log_id.", '".$comm."')";
    $query = mysqli_query($db_conx, $sql);

		$from = "Camagru_pkerckho";
		$subject = 'Camagru comment notification';
		$message = "Someone commented your montage ! Get online and check it !";
		$headers = "From: $from\n";
				$headers .= "MIME-Version: 1.0\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$retour = mysqli_query($db_conx, "
		SELECT users.email AS mail
		FROM photos
		LEFT JOIN users ON users.username = photos.username
		WHERE photos.id = ".$data['id']."
		");
		$datas = mysqli_fetch_assoc($retour);
		mail($datas['mail'], $subject, $message, $headers);

	}
?>
<link rel="stylesheet" href="css/styles.css" media="screen" title="no title" charset="utf-8">
<div class="img" id="photo_comment">
    <img src="photos/<?php echo $data['filename']; ?>">
</div>
<form id="comment_form" method="post" action="photos.php?id=<?php echo $data['id']; ?>">
    <td align="right" id="one"></td>
<textarea name="new_comment" id="tmessageid"></textarea>
<input type="submit" name="submit" id="submit" value="comment">
</form>

<?php
$as_like = mysqli_num_rows(mysqli_query($db_conx, "SELECT * FROM likes WHERE photo_id = ".ceil($_GET['id'])." AND users_id = ".$log_id)) > 0;

echo " &nbsp <a href='photos.php?id=".$data['id']."&like=1'>".($as_like ? "<img src='img/like1.png' id='likebtn'/>" : "<img src='img/unlike.png' id='unlikebtn'/> ")." ( ".mysqli_num_rows(mysqli_query($db_conx, "SELECT * FROM likes WHERE photo_id = ".ceil($_GET['id'])))." like)</a>";
 ?>

<?php

if (isset($_GET['del_id'])) {
	$del_id = ceil($_GET['del_id']);
	mysqli_query($db_conx, "DELETE FROM comments WHERE id=".$del_id);
}

$sql = "SELECT * FROM comments where photo_id=".ceil($_GET['id']);
$query = mysqli_query($db_conx, $sql);
while ($row = mysqli_fetch_array($query, MYSQLI_BOTH)) {
 echo "<div>".$row['comment'];
 if ($row['users_id'] == $log_id) {
	 echo " &nbsp <a href='photos.php?id=".$data['id']."&del_id=".$row['id']."'><img src='img/rbutton.png' id='bin'  /></a></a>";
 }
 echo "</div>";
}

if (isset($_GET['like'])) {
	if ($as_like) {
		$sql = "DELETE FROM likes WHERE photo_id = ".ceil($_GET['id'])." AND users_id = ".$log_id;
		$query = mysqli_query($db_conx, $sql);
	} else {
		$sql = "INSERT INTO likes VALUES (".ceil($_GET['id']).", ".$log_id.")";
		$query = mysqli_query($db_conx, $sql);
	}
}


?>
