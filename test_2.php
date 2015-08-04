<!doctype html>
<head>
    <title>Medina Element Example</title>
	<script src="/lib/mediaelement-master/jquery.js"></script>
	<script src="/lib/mediaelement-master/mediaelement-and-player.min.js"></script>
	<link rel="stylesheet" href="/lib/mediaelement-master/mediaelementplayer.css" />
</head>
<body>
    <div style="width:700px;margin:0px auto;">
		<video src="/videos/150724_080601.3gp" 
		controls preload="auto"
		data-setup='{"controls":true}'
		width="940" height="400"></video>    
    </div>
</body>
<script>s
var v = document.getElementsByTagName("video")[0];
new MediaElement(v, {success: function(media) {
    media.play();
}});
</script>
