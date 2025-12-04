const canvas = document.getElementById('signature-pad');
const ctx = canvas.getContext('2d');
const hiddenField = document.getElementById('signature_draw');

let drawing = false;
let lastX = 0;
let lastY = 0;

// Save signature to hidden input
function saveSignature() {
    hiddenField.value = canvas.toDataURL("image/png");
}

// Start drawing
canvas.addEventListener('mousedown', function (e) {
    drawing = true;
    [lastX, lastY] = [e.offsetX, e.offsetY];
});

// Stop drawing
canvas.addEventListener('mouseup', () => {
    drawing = false;
    saveSignature(); // save after finishing
});

canvas.addEventListener('mouseout', () => drawing = false);

// Draw smooth line
canvas.addEventListener('mousemove', function (e) {
    if (!drawing) return;

    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000";

    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();

    [lastX, lastY] = [e.offsetX, e.offsetY];

    saveSignature(); // save continuously while drawing
});

// Clear canvas
function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hiddenField.value = ""; // Clear hidden field also
}