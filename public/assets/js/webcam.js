function showUpload() {
	document.getElementById('uploadBox').style.display = 'block';
	document.getElementById('webcamBox').style.display = 'none';
}

function showWebcam() {
	document.getElementById('uploadBox').style.display = 'none';
	document.getElementById('webcamBox').style.display = 'block';
}

Webcam.set({
	width: 320
	, height: 240
	, image_format: 'png'
	, png_quality: 90
});

Webcam.attach('#camera');

function takeSnapshot() {
	Webcam.snap(function(dataURI) {
		document.getElementById('snapshotResult').innerHTML =
			'<img src="' + dataURI + '" width="320" class="img-thumbnail">';

		document.getElementById('webcamImageField').value = dataURI;
		document.getElementById('uploadBtn').style.display = 'block';
	});
}

