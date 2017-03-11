<?php
$upload_dir = 'img/png';
$img = $_POST['my_hidden'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = $upload_dir."image_name.png";
$success = file_put_contents($file, $data);
header('Location: '.$_POST['return_url']);
?>
