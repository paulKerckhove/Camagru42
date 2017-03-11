<header>
  <nav class="main-header">
    <h2 >Camagru</h2>
    <a href="index.php" title="Homepage"> <img id= "settings" src=img/home.png /></a>
    <?php if ($user_ok) { ?>
    <a href="webcam.php" title="Add_a_picture"> <img id= "settings" src=img/camera.png /></a>
    <a href="gallery.php" title="My_gallery"> <img id= "settings" src=img/open_folder.png /></a>
    <a href="my_account.php" title="My_account"> <img id= "settings" src=img/settings.png /></a>
		<a href="logout.php" title="Logout"> <img id= "settings" src=img/logout.png /></a>
    <?php } else { ?>
    <a href="signup.php" title="Signup"> <img id= "settings" src=img/signup.png /></a>
    <a href="login.php" title="Login"> <img id= "settings" src=img/login.png /></a>
    <a href="logout.php" title="Logout"> <img id= "settings" src=img/logout.png /></a>
    <?php } ?>
  </nav>
  </header>
