<?php
include_once("check_login_status.php");
include_once("db_conx.php");
if (!$user_ok) {
  header("location: index.php");
}
 ?>
<link rel="stylesheet" href="css/styles.css" media="screen" title="no title" charset="utf-8">
<?php
include_once 'includes/header.php';
?>
<div class="webcam">
  <video id="video"></video>
  <button class="btn" id="startbutton">Snap it</button>
  <canvas id="canvas" width="800" height="800"></canvas>
			<canvas id="canvas1" width="800" height="800"
				ondrop="add_img(event)" ondragover="event.preventDefault()"></canvas>
        <canvas id="canvas3" width="400" height="300"></canvas></canvas>
  <form action="#" method="post" id="save_pic">
    <input type="text" style="display:none;" name="photo" id="photo" />
    <input type="text" style="display:none;" name="photo1" id="photo1" />
    <input type="button" onclick="screenshot()" type="sumbit" id="savebutton" value="save it" />
  </form>
  <!-- This part concerns the links to hide the video then drag and drop a picture as a background -->
      <a href="#" onclick='hide()'>Want to upload a picture ?</a>
      <br />
      <a href="#" onclick="show()">Back to webcam</a>

      <script>
      function show() {
        document.getElementById("video").style.display = 'block';
        document.getElementById("canvas").style.display = 'none';


    }

    function hide() {
      //window.alert("Drag your image into the frame to use it");
        document.getElementById("video").style.display = 'none';
        document.getElementById("canvas").style.display = 'block';
        var MAX_HEIGHT = 300;
        var MAX_WIDTH = 400;
        function render(src){
        	var image = new Image();
        	image.onload = function(){
        		var canvas = document.getElementById("canvas");
        		if(image.height > MAX_HEIGHT) {
        			image.width *= MAX_HEIGHT / image.height;
        			image.height = MAX_HEIGHT;
              //image.height *= MAX_WIDTH / image.width;
              image.width = MAX_WIDTH;

        		}
        		var ctx = canvas.getContext("2d");
        		ctx.clearRect(0, 0, canvas.width, canvas.height);
        		canvas.width = image.width;
        		canvas.height = image.height;
        		ctx.drawImage(image, 0, 0, image.width, image.height);
        	};
        	image.src = src;
        }

        function loadImage(src){
        	//	Prevent any non-image file type from being read.
        	if(!src.type.match(/image.*/)){
        		console.log("The dropped file is not an image: ", src.type);
        		return;
        	}

        	//	Create our FileReader and run the results through the render function.
        	var reader = new FileReader();
        	reader.onload = function(e){
        		render(e.target.result);
        	};
        	reader.readAsDataURL(src);
        }

        var target = document.getElementById("canvas1");
        target.addEventListener("dragover", function(e){e.preventDefault();}, true);
        target.addEventListener("drop", function(e){
        	e.preventDefault();
        	loadImage(e.dataTransfer.files[0]);
        }, true);

    }
    </script>
  <script src="webcam.js"></script>
</div>

<div id="gallerycontainer">
    <div id="gallery">
     <a href="#"><img id ="eazy" src="img/layer/eazy.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="tupac" src="img/layer/tupac.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="the_rock" src="img/layer/the_rock.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="lebron" src="img/layer/lebron.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="xavier" src="img/layer/xavier.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="luda" src="img/layer/luda.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="redman" src="img/layer/redman.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="snoop" src="img/layer/snoop.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="pc3" src="img/layer/pc3.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="roshi" src="img/layer/roshi.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="piccolo" src="img/layer/piccolo.png" alt="" ondragstart='select_img(event)'/></a>
     <a href="#"><img id="vegeta" src="img/layer/vegeta.png" alt="" ondragstart='select_img(event)'/></a>
   </div>
 </div>

 <?php
 $sql = "SELECT * FROM photos WHERE username='$log_username'";
 $query = mysqli_query($db_conx, $sql);
 while ($data = mysqli_fetch_assoc($query)) {
   ?>
   <frame>

   <div class="img" id="maingallery">
     <a  href="photos.php?id=<?php echo $data['id']; ?>">
       <img src="photos/<?php echo $data['filename']; ?>">
       </a>
   </div>
 </frame>


   <?php
 }
?>

 <?php
 ?>

 <script>
 	var video = document.querySelector("#cam");
  var myphoto = false;
  var php_err = false;
  var width1 = 640;
  var height1 = 480;
  var canvas1 = document.getElementById("canvas1");
  var ctx = canvas1.getContext("2d");
  var obj = [];
  var dragonce = false;
 	setInterval(draw, 10);
	canvas1.onmousedown = myDown;
	canvas1.onmouseup = myUp;
	canvas1.ondblclick = myZoomIn;
	canvas1.oncontextmenu = myZoomOut;
	canvas1.onmousemove = myMove;
//
  //navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia
    //      || navigator.mozGetUserMedia || navigator.msGetUserMedia
      //    || navigator.oGetUserMedia;
//navigator.getUserMedia({video: true}, streamer, videoError);
navigator.getUserMedia = ( navigator.getUserMedia ||
                             navigator.webkitGetUserMedia ||
                             navigator.mozGetUserMedia ||
                             navigator.msGetUserMedia);

      var video;
      var webcamStream;

      function startWebcam() {
        if (navigator.getUserMedia) {
           navigator.getUserMedia (

              // constraints
              {
                 video: true,
                 audio: false
              },

              // successCallback
              function(localMediaStream) {
                  video = document.querySelector('video');
                 video.src = window.URL.createObjectURL(localMediaStream);
                 webcamStream = localMediaStream;
              },

              // errorCallback
              function(err) {
                 console.log("The following error occured: " + err);
              }
           );
        } else {
           console.log("getUserMedia not supported");
        }
      }

      function stopWebcam() {
          webcamStream.stop();
      }


//This part is the javascript to use an image instead of the webcam //


</script>

<!--
/* This part is about the drag and drop without using the webcam so by uploading a file */
-->



<!--
To save the picture I must use http://php.net/manual/fr/function.imagecopy.php to save the canvas and the image drawn over it
-->
<?php
if (isset($_POST['cam'])) {
 $gallery = $log_username;
 $cam = $_POST['cam'];
		$layer = $_POST['layer'];
		if (isset($_POST['cam1']))
			$cam = implode('', array($cam, $_POST['cam1']));
		if (isset($_POST['layer1']))
			$layer = implode('', array($layer, $_POST['layer1']));
		list($type, $cam) = explode(';', $cam);
		list($type, $layer) = explode(';', $layer);
		list(, $cam) = explode(',', $cam);
		list(, $layer) = explode(',', $layer);
		$cam = imagecreatefromstring(base64_decode($cam));
		$layer = imagecreatefromstring(base64_decode($layer));
		imagecopy($cam, $layer, 0, 0, 0, 0, imagesx($cam), imagesy($cam));
    $fileExt = "png";
    $db_file_name = date("DMjGisY")."".rand(1000,9999).".".$fileExt;
    $photo_path = "photos/$db_file_name";
    imagepng($cam, $photo_path);
    /*file_put_contents("photos/tmp_photo.$date.$log_username.png", base64_decode($cam[0]));*/
    $sql = "INSERT INTO photos VALUES (NULL, 'photos/".$log_username."', '$log_username', 'a continuer..', '$db_file_name')";
    $query = mysqli_query($db_conx, $sql);
	}
  ?>
  <script>
  function screenshot(){
  // Console log in here
    console.log("do we get here1 ?");
    savecan();
    var can3 = document.getElementById('canvas3');
  	var save_pic = document.querySelector('#save_pic');
  	var data, post;


  	if (!obj[0])
  		return ;
      data = canvas.toDataURL('image/png');
      data1 = canvas1.toDataURL('image/png');


  	if (data.length > 500000)
  	{
  		post = '<input type="text" name="cam" value="'+data.substr(0, 500000)
  			+'"></input><input type="text" name="cam1" value="'+data.slice(500000)
  			+'"></input>';
  	}
  	else
  		post = '<input type="text" name="cam" value="'+data+'"></input>';
    if (data1.length > 500000)
    {
		   post1 = '<input type="text" name="layer" value="'+data1.substr(0, 500000)
		   +'"></input><input type="text" name="layer1" value="'+data1.slice(500000)
		   +'"></input>';
    }
	  else
		  post1 = '<input type="text" name="layer" value="'+data1+'"></input>';

  	save_pic.innerHTML = post + post1;
  	save_pic.submit();
  	//myplay();
  }
  function savecan() {
    //Console log in here
    console.log("do we get here2 ?");
    var can = document.getElementById('canvas');
    var ctx = can.getContext('2d');

    var can2 = document.getElementById('canvas1');
    var ctx2 = can2.getContext('2d');

    var can3 = document.getElementById('canvas3');
    var ctx3 = can3.getContext('2d');

    ctx3.drawImage(can, 0, 0);
    ctx3.drawImage(can2, 0, 0);
  }
  </script>

<?php include_once 'includes/footer.php';?>
