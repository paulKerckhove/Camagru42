<?php
include_once 'check_login_status.php';
include_once 'includes/header.php';
include_once 'includes/footer.php';
include_once 'db_conx.php';

if (isset($_GET['del_id'])) {
  $del_id = ceil($_GET['del_id']);
  $retour = mysqli_query($db_conx, "SELECT username FROM photos WHERE id = ".$del_id);
  $data = mysqli_fetch_assoc($retour);
  if ($data['username'] == $log_username) {
    mysqli_query($db_conx, "DELETE FROM photos WHERE id = ".$del_id);
  }
}

 ?>
       <link rel="stylesheet" href="css/styles.css" media="screen" title="no title" charset="utf-8">
    <?php
    $sql = "SELECT * FROM photos WHERE username='$log_username'";
    $query = mysqli_query($db_conx, $sql);
    while ($data = mysqli_fetch_assoc($query)) {
      ?>
      <div class="img">
        <a  href="photos.php?id=<?php echo $data['id']; ?>">
          <img src="photos/<?php echo $data['filename']; ?>">
          </a>
      </div>

      <a href="gallery.php?del_id=<?php echo $data['id']; ?>"> <img src='img/rbutton.png' id='trashgall'/></a>
      <?php


    }
?>
