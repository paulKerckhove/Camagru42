(function() {

  var streaming = false,
       video        = document.querySelector('#video'),
       cover        = document.querySelector('#cover'),
       canvas       = document.querySelector('#canvas'),
       photo        = document.querySelector('#photo'),
       startbutton  = document.querySelector('#startbutton'),
       width = 400,
       height = 0;
   navigator.getMedia = ( navigator.getUserMedia ||
                          navigator.webkitGetUserMedia ||
                          navigator.mozGetUserMedia ||
                          navigator.msGetUserMedia);
   navigator.getMedia(
     {
       video: true,
       audio: false
     },
     function(stream) {
       if (navigator.mozGetUserMedia) {
         video.mozSrcObject = stream;
       } else {
         var vendorURL = window.URL || window.webkitURL;
         video.src = vendorURL.createObjectURL(stream);
       }
       video.play();
     },
     function(err) {
       console.log("An error occured! " + err);
     }
   );
   video.addEventListener('canplay', function(ev){
     if (!streaming) {
       height = video.videoHeight / (video.videoWidth/width);
       video.setAttribute('width', width);
       video.setAttribute('height', height);
       canvas.setAttribute('width', width);
       canvas.setAttribute('height', height);
       streaming = true;
       document.getElementById("canvas1").width = width;
       document.getElementById("canvas1").height = height;
     }
   }, false);
   function takepicture() {
     canvas.width = width;
     canvas.height = height;
     canvas.getContext('2d').drawImage(video, 0, 0, width, height);
     var data = canvas.toDataURL('image/png');
     /*console.log(data);*/
     photo.setAttribute('value', data);
   }
   startbutton.addEventListener('click', function(ev){
       takepicture();
     ev.preventDefault();
   }, false);

/****************************************************************************/

/* This scrip allows the user to use the webcam, take a picture (canvas) the
* picture will be saved later in 'image/png'
*/

})();

/* Draggable Canvas */ http://jsfiddle.net/v92gn/
function init_drag(img_src)
{
	var tmp;

	tmp = {img: new Image(), size: 0, dragok: false, x: 47 , y: 80}; /*should be 320 & 240, half the size of the canvas*/
	tmp.img.src = img_src;
	tmp.size = tmp.img.width > 200 ? 200 / tmp.img.width : 1;
	obj.push(tmp);
  //document.addEventListener('keydown', onKeyDown, false);
}

//This was made so i can move the picture using the num pad.
//function onKeyDown(e)
///{
  //if (e.keyCode == 49)
    //obj[0].x++;
      //if (e.keyCode == 50)
        //obj[0].x--;
          //if (e.keyCode == 51)
            //obj[0].y++;
              //if (e.keyCode == 52)
                //obj[0].y--;
//}

//This function draws the image over the video stream
function draw()
{
	ctx.clearRect(0, 0, width1, height1);
	obj.forEach(function(item, i)
	{
		ctx.drawImage(item.img, item.x - item.img.width * item.size / 2 + 170, item.y - item.img.height
			* item.size / 2, item.img.width * item.size, item.img.height * item.size);
	});
}
/*
Need to be fixed hte cursor cannot be found with the right coordonates right now
*/
function myMove(e)
{
	var curs = false;

	obj.forEach(function(item, i)
	{
		if (e.pageX < item.x + 50 + canvas1.offsetLeft && e.pageX > item.x - 50 +
			canvas1.offsetLeft && e.pageY < item.y + 50 + canvas1.offsetTop &&
			e.pageY > item.y - 50 + canvas1.offsetTop)
			curs = true;
		canvas1.style.cursor = curs ? 'pointer' : 'default';
		if (item.dragok)
		{
			item.x = e.pageX - canvas1.offsetLeft;
			item.y = e.pageY - canvas1.offsetTop;
		}
	});
}

function myZoomIn(e)
{
	e.preventDefault();
	obj.forEach(function(item, i)
	{
		if (e.pageX < item.x + 50 + canvas1.offsetLeft && e.pageX > item.x - 50 +
			canvas1.offsetLeft && e.pageY < item.y + 50 + canvas1.offsetTop &&
			e.pageY > item.y - 50 + canvas1.offsetTop)
			item.size *= 1.2;
	});
}

function myZoomOut(e)
{
	obj.forEach(function(item, i)
	{
		if (e.pageX < item.x + 50 + canvas1.offsetLeft && e.pageX > item.x - 50 +
			canvas1.offsetLeft && e.pageY < item.y + 50 + canvas1.offsetTop &&
			e.pageY > item.y - 50 + canvas1.offsetTop)
			item.size /= 1.2;
	});
	e.preventDefault();
}

function myDown(e)
{
	obj.forEach(function(item, i)
	{
		if (e.pageX < item.x + 50 + canvas1.offsetLeft && e.pageX > item.x - 50 +
			canvas1.offsetLeft && e.pageY < item.y + 50 + canvas1.offsetTop &&
			e.pageY > item.y - 50 + canvas1.offsetTop)
		{
			if (e.button == 0 && !dragonce)
			{
				dragonce = true;
				item.dragok = true;
			}
			if (e.button == 1)
				obj.splice(i, 1);
		}
	});
}

function myUp()
{
	obj.forEach(function(item, i)
	{
		item.dragok = false;
	});
	dragonce = false;
	canvas1.style.cursor = 'default';
}

function select_img(e)
{
//	console.log(e.target.src);
	e.dataTransfer.setData("text", e.target.src);
}

function add_img(e)
{
	e.preventDefault();
	init_drag(e.dataTransfer.getData("text"));
}

/****************************************************************************/



/* This part is about seeing the result, since we want to use the canvas we do not need it.
//	Remember that DTK 1.7+ is AMD!
require(["dojo/request"], function(request){
    request.post("webcam.php", {
        data: {
            imageName: "my_photo.png",
            imageData: encodeURIComponent(document.getElementById("canvas").toDataURL("photos/png"))
        }
    }).then(function(text){
        console.log("The server returned: ", text);
    });
});
*/



//function screenshot()
//{
//	var pic_form = document.querySelector('#save_pic');
//	var data, data1, post, post1;

//	if (!obj[0])
	//	return ;
//	if (!myphoto)
//		canvas.getContext('2d').drawImage(video, 0, 0, width, height);
//	data = canvas.toDataURL('image/png');
//	data1 = canvas1.toDataURL('image/png');

	//if (data.length > 500000)
//	{
	//	post = '<input type="text" name="video" value="'+data.substr(0, 500000)
	//		+'"></input><input type="text" name="cam1" value="'+data.slice(500000)
	//		+'"></input>';
//	}
//	else
//		post = '<input type="text" name="video" value="'+data+'"></input>';
//	if (data1.length > 500000)
//	{
	//	post1 = '<input type="text" name="layer" value="'+data1.substr(0, 500000)
	//		+'"></input><input type="text" name="layer1" value="'+data1.slice(500000)
	//		+'"></input>';
//	}
//	else
//		post1 = '<input type="text" name="layer" value="'+data1+'"></input>';
//	pic_form.innerHTML = post + post1;
//	pic_form.submit();
//	myplay();
//}
