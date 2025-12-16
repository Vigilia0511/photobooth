// script.js  client-side logic

let stream;
let currentFacingMode = 'user'; // 'user' for front, 'environment' for back
let capturedPhotos = [];
let isContinuous = false;

const preview = document.getElementById('preview');
const canvas = document.getElementById('captureCanvas');
const ctx = canvas.getContext('2d');
const cameraSelect = document.getElementById('cameraSelect');
const uploadFile = document.getElementById('uploadFile');
const captureBtn = document.getElementById('captureBtn');
const continuousBtn = document.getElementById('continuousBtn');
const timerSelect = document.getElementById('timerSelect');
const countdownOverlay = document.getElementById('countdownOverlay');
const status = document.getElementById('status');
const progress = document.getElementById('progress');

async function getCameras() {
    try {
        // Request permission first
        const tempStream = await navigator.mediaDevices.getUserMedia({ video: { aspectRatio: 4/3 }, audio: true });
        tempStream.getTracks().forEach(t => t.stop()); // Stop immediately after permission
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(d => d.kind === 'videoinput');
        cameraSelect.innerHTML = '';
        videoDevices.forEach(d => {
            const option = document.createElement('option');
            option.value = d.deviceId;
            option.text = d.label || Camera ;
            cameraSelect.appendChild(option);
        });
        if (videoDevices.length > 0) {
            currentCameraId = videoDevices[0].deviceId;
            cameraSelect.value = currentCameraId;
        }
    } catch (e) {
        status.innerText = 'Camera access denied or not available: ' + e.message;
        console.error(e);
    }
}

async function startPreview() {
    try {
        if (stream) {
            stream.getTracks().forEach(t => t.stop());
        }
        stream = await navigator.mediaDevices.getUserMedia({
            video: { 
                deviceId: currentCameraId ? { exact: currentCameraId } : undefined,
                aspectRatio: 4/3
            },
            audio: true
        });
        preview.srcObject = stream;
        status.innerText = 'Preview started';
    } catch (e) {
        status.innerText = 'Error starting preview: ' + e.message;
    }
}

cameraSelect.addEventListener('change', () => {
    currentCameraId = cameraSelect.value;
    startPreview();
});

document.getElementById('switchCam').addEventListener('click', () => {
    const options = cameraSelect.options;
    if (options.length > 1) {
        let currentIndex = cameraSelect.selectedIndex;
        currentIndex = (currentIndex + 1) % options.length;
        cameraSelect.selectedIndex = currentIndex;
        currentCameraId = options[currentIndex].value;
        startPreview();
    }
});

uploadFile.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const img = new Image();
        img.onload = () => {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            canvas.toBlob(blob => {
                capturedPhotos.push(blob);
                status.innerText = 'Image uploaded';
                progress.innerText = `Photos captured: ${capturedPhotos.length}/8`;
                if (capturedPhotos.length >= 8) {
                    saveAllPhotos();
                }
            }, 'image/png');
        };
        img.src = URL.createObjectURL(file);
    }
});

async function captureOne() {
    // Countdown
    countdownOverlay.style.display = 'block';
    for (let j = 3; j > 0; j--) {
        status.innerText = `Photo: ${j}`;
        countdownOverlay.innerText = j;
        await new Promise(r => setTimeout(r, 1000));
    }
    countdownOverlay.style.display = 'none';
    status.innerText = 'Photo: Capturing...';
    // Calculate 4:3 dimensions from video
    const videoWidth = preview.videoWidth;
    const videoHeight = preview.videoHeight;
    const aspectRatio = 4 / 3;
    let cropWidth, cropHeight, offsetX = 0, offsetY = 0;
    
    if (videoWidth / videoHeight > aspectRatio) {
        // Video is wider than 4:3, crop width
        cropHeight = videoHeight;
        cropWidth = videoHeight * aspectRatio;
        offsetX = (videoWidth - cropWidth) / 2;
    } else {
        // Video is taller than 4:3, crop height
        cropWidth = videoWidth;
        cropHeight = videoWidth / aspectRatio;
        offsetY = (videoHeight - cropHeight) / 2;
    }
    
    // Set canvas to fixed 4:3 size
    canvas.width = 800;
    canvas.height = 600;
    ctx.drawImage(preview, offsetX, offsetY, cropWidth, cropHeight, 0, 0, 800, 600);
    const blob = await new Promise(res => canvas.toBlob(res, 'image/png'));
    capturedPhotos.push(blob);
    progress.innerText = `Photos captured: ${capturedPhotos.length}`;
}

async function captureSequence() {
    if (!stream) {
        status.innerText = 'Start preview first';
        return;
    }
    capturedPhotos = [];
    const timerDuration = parseInt(timerSelect.value);
    status.innerText = 'Get ready!';
    countdownOverlay.style.display = 'block';
    countdownOverlay.innerText = 'Ready';
    await new Promise(r => setTimeout(r, 1000));
    for (let j = timerDuration; j > 0; j--) {
        status.innerText = `Starting in ${j}...`;
        countdownOverlay.innerText = j;
        await new Promise(r => setTimeout(r, 1000));
    }
    countdownOverlay.style.display = 'none';
    for (let i = 0; i < 8; i++) {
        await captureOne();
        // Short delay between captures
        await new Promise(r => setTimeout(r, 500));
    }
    status.innerText = 'All 8 photos captured!';
    saveAllPhotos();
}

async function continuousCapture() {
    if (!isContinuous) return;
    await captureOne();
    setTimeout(() => continuousCapture(), 3000); // Capture every 3 seconds
}

async function saveAllPhotos() {
    const filenames = [];
    for (let i = 0; i < capturedPhotos.length; i++) {
        const fd = new FormData();
        fd.append('photo', capturedPhotos[i], `photo_${i}.png`);
        const res = await fetch('save_image.php', { method: 'POST', body: fd });
        const text = await res.text();
        console.log(text);
        // Extract filename from response
        const match = text.match(/uploads\/(.+)/);
        if (match) filenames.push(match[1]);
    }
    // Redirect to edit page with filenames
    const params = new URLSearchParams();
    params.append('images', JSON.stringify(filenames));
    window.location.href = 'edit.php?' + params.toString();
}

captureBtn.addEventListener('click', captureSequence);

continuousBtn.addEventListener('click', () => {
    if (!stream) {
        status.innerText = 'Start preview first';
        return;
    }
    if (isContinuous) {
        isContinuous = false;
        continuousBtn.textContent = 'Continuous';
        status.innerText = 'Continuous capture stopped';
        if (capturedPhotos.length > 0) {
            saveAllPhotos();
        }
    } else {
        isContinuous = true;
        capturedPhotos = [];
        continuousBtn.textContent = 'Stop';
        continuousCapture();
    }
});

// Load on start
window.addEventListener('load', async () => {
    await getCameras();
    await startPreview();
});
