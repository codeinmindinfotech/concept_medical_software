const canvas = document.getElementById('signature-pad');
const ctx = canvas.getContext('2d');
let drawing = false;

canvas.addEventListener('mousedown', () => drawing = true);
canvas.addEventListener('mouseup', () => drawing = false);
canvas.addEventListener('mouseout', () => drawing = false);
canvas.addEventListener('mousemove', draw);

function draw(e) {
    if (!drawing) return;
    const rect = canvas.getBoundingClientRect();
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
    ctx.beginPath();
    ctx.moveTo(e.offsetX, e.offsetY);
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();
}

function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

// On form submit, convert canvas to base64
document.querySelector('form').addEventListener('submit', function(e){
    document.getElementById('signature_draw').value = canvas.toDataURL('image/png');
});


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

