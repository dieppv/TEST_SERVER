<head>
<link href="css/videojs/video-js.css" rel="stylesheet">
<script src="scripts/videojs/video.js"></script>
<script>
 videojs.options.flash.swf = "swf/videojs/video-js.swf";
</script>
</head>



 $videofilename = $name;  // name of the file Eg video.mp4
    echo '<video id="player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="640" height="360" poster="images/video_img.png">

 <source type="video/mp4" src="uploads/video.php?'.$videofilename.'" >

<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
    </video>';



  echo'<script>
        videojs("player", {}, function(){
     });    
    </script>';