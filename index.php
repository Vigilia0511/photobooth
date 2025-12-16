<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Photo Booth</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: fixed;
            width: 100%;
            height: 100%;
        }

        .app-container {
            width: 100%;
            max-width: 600px;
            height: 100vh;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .camera-view {
            flex: 1;
            position: relative;
            background: #000;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            max-height: 65vh;
        }

        .captured-photos-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 10px;
            padding: 20px;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            align-items: center;
            justify-items: center;
        }

        .captured-photos-grid img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .camera-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
        }

        .header-btn {
            background: rgba(0, 0, 0, 0.6);
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            color: white;
            display: flex;
            align-items: center;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: all 0.2s;
        }

        .header-btn:active {
            background: rgba(0, 0, 0, 0.8);
            transform: scale(0.95);
        }

        .photo-count {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 12px;
            padding: 10px 16px;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
            font-size: 14px;
            font-weight: 500;
        }

        .video-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        video#preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transform: scaleX(-1);
            background: black;
        }

        video#preview.back-camera {
            transform: scaleX(1);
        }

        video#preview.front-camera-fix {
            object-fit: contain;
            background: black;
        }

        #captureCanvas {
            display: none;
        }

        .bottom-controls {
            background: white;
            padding: 24px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.1);
        }

        .timer-select {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 16px;
            color: white;
            backdrop-filter: blur(10px);
        }

        .countdown-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 100px;
            font-weight: bold;
            color: white;
            text-shadow: 0 0 20px rgba(0,0,0,0.8);
            background: rgba(0,0,0,0.5);
            padding: 20px;
            border-radius: 20px;
            display: none;
            z-index: 20;
            pointer-events: none;
        }

        .control-btn {
            background: white;
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #333;
            position: relative;
        }

        .control-btn:active {
            background: #f0f0f0;
            transform: scale(0.95);
        }

        .capture-btn {
            background: #2c3e50;
            border: 4px solid white;
            box-shadow: 0 0 0 3px #2c3e50;
            border-radius: 50%;
            width: 72px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: white;
        }

        .capture-btn:active {
            transform: scale(0.95);
        }

        .next-btn {
            background: #28a745;
            box-shadow: 0 0 0 3px #28a745;
        }

        .action-buttons {
            position: absolute;
            bottom: 300px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 10;
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #333;
            backdrop-filter: blur(10px);
            transition: all 0.2s;
            position: relative;
        }

        .action-btn:active {
            background: rgba(255, 255, 255, 0.9);
            transform: scale(0.95);
        }

        .upload-control {
            flex-direction: column;
            height: 65px;
            border-radius: 12px;
            padding: 6px 4px;
        }

        .footer {
            background: white;
            padding: 16px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .footer p {
            margin: 2px 0;
        }

        .version {
            color: #999;
        }

        @keyframes flash {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }

        .flash {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            pointer-events: none;
            animation: flash 0.3s ease-out;
            z-index: 5;
        }

        .gallery-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1000;
            padding: 20px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .gallery-modal.active {
            display: block;
        }

        .gallery-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            color: white;
        }

        .gallery-header h2 {
            font-size: 24px;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }

        .delete-photo {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 71, 87, 0.9);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 14px 24px;
            border-radius: 8px;
            z-index: 2000;
            display: none;
            backdrop-filter: blur(10px);
            max-width: 90%;
            text-align: center;
        }

        .notification.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }

        .start-camera-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #2c3e50;
            color: white;
            border: none;
            padding: 20px 40px;
            border-radius: 12px;
            font-size: 18px;
            cursor: pointer;
            z-index: 15;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .start-camera-btn:active {
            transform: translate(-50%, -50%) scale(0.95);
        }

        .permission-info {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 14;
            max-width: 80%;
            text-align: center;
            font-size: 14px;
            line-height: 1.5;
        }

        .rotate-prompt {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 18px 24px;
            border-radius: 12px;
            z-index: 30;
            text-align: center;
            font-size: 16px;
            line-height: 1.4;
            box-shadow: 0 8px 30px rgba(0,0,0,0.4);
        }

        .rotate-prompt small { display:block; margin-top:8px; opacity:0.85; font-size:13px }

        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 5;
            display: none;
        }

        .grid-overlay.active {
            display: block;
        }

        .grid-overlay svg {
            width: 100%;
            height: 100%;
        }
        /* Fullscreen modal for captured images */
        #fullscreenModal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3000;
        }
        #fullscreenModal img {
            max-width: 100%;
            max-height: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        }
        /* Display-rotation helpers: rotate horizontal captures for portrait UI */
        .display-rotated {
            transform: rotate(90deg);
            transform-origin: center center;
        }
        .gallery-item img.display-rotated {
            width: 150px;
            height: auto;
            object-fit: contain;
        }
        #fullscreenModal img.display-rotated {
            max-width: none;
            max-height: 100vh;
        }

        .button-label {
            font-size: 9px;
            color: #666;
            margin-top: 1px;
            text-align: center;
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="camera-view">
        <div class="camera-header">
                <button class="header-btn" id="flipCameraBtn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="margin-left: 4px;">
                        <path d="M16 17l5-5-5-5M4 12h17"></path>
                    </svg>
                </button>
                <select id="timerSelect" class="timer-select">
                    <option value="0">0s</option>
                    <option value="2">2s</option>
                    <option value="3">3s</option>
                </select>
                <div class="photo-count">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span id="photoCounter">0/8</span>
                </div>
            </div>
            <div class="video-container">
                <video id="preview" autoplay muted playsinline webkit-playsinline></video>
                <button class="start-camera-btn" id="startCameraBtn">📷 Start Camera</button>
                <div class="permission-info" id="permissionInfo" style="display: none;">
                    Tap "Allow" when your browser asks for camera permission
                </div>
                <div class="rotate-prompt" id="rotatePrompt" style="display:none;">
                    Please rotate your phone to <strong>landscape</strong> for best results.<br>
                    <small>Tap this message when you've rotated the device.</small>
                </div>
                <div class="grid-overlay" id="gridOverlay">
                    <svg>
                        <line x1="33.33%" y1="0" x2="33.33%" y2="100%" stroke="white" stroke-width="1" opacity="0.5"/>
                        <line x1="66.66%" y1="0" x2="66.66%" y2="100%" stroke="white" stroke-width="1" opacity="0.5"/>
                        <line x1="0" y1="33.33%" x2="100%" y2="33.33%" stroke="white" stroke-width="1" opacity="0.5"/>
                        <line x1="0" y1="66.66%" x2="100%" y2="66.66%" stroke="white" stroke-width="1" opacity="0.5"/>
                    </svg>
                </div>
            </div>
            <div class="captured-photos-grid" id="capturedPhotosGrid" style="display: none;">
                <!-- Photos will be populated here -->
            </div>
            <div id="countdownOverlay" class="countdown-overlay"></div>
            <canvas id="captureCanvas"></canvas>
        </div>

        <div class="bottom-controls">
            <button class="control-btn" id="galleryBtn">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="badge" id="galleryBadge">0</span>
            </button>
            <button class="capture-btn" id="captureBtn">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
            </button>
            <button class="capture-btn next-btn" id="nextBtn" style="display: none;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6"></path>
                </svg>
            </button>
            <button class="control-btn upload-control" id="uploadBtn">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <span class="button-label">For better quality</span>
            </button>
        </div>

        <div class="action-buttons">
            <button class="action-btn" id="undoBtn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7v6h6"></path>
                    <path d="M21 17a9 9 0 00-9-9 9 9 0 00-9 9"></path>
                </svg>
                <span class="button-label">Undo</span>
            </button>
            <button class="action-btn" id="gridBtn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span class="button-label">Grid</span>
            </button>
            <button class="action-btn" id="clearAllBtn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                <span class="button-label">Clear All</span>
            </button>
        </div>

        <div class="footer">
            <p>© 2025 @marcjoshuavigilia. All rights reserved</p>
            <p class="version">Version: v1 Mobile</p>
        </div>
    </div>

    <input type="file" id="uploadFile" accept="image/*" multiple style="display:none">

    <div class="gallery-modal" id="galleryModal">
        <div class="gallery-header">
            <h2>Gallery (<span id="galleryCount">0</span>)</h2>
            <button class="close-btn" id="closeGallery">Close</button>
        </div>
        <div class="gallery-grid" id="galleryGrid"></div>
    </div>

    <div class="notification" id="notification"></div>

    <script>
        let stream = null;
        let capturedPhotos = [];
        let useFrontCamera = false;
        let showGrid = false;
        let cameraStarted = false;
        let forceLiveView = false;

        async function init() {
            loadPhotosFromStorage();
            setupEventListeners();
            // show grid overlay by default on app open
            const gridOverlay = document.getElementById('gridOverlay');
            if (gridOverlay) {
                gridOverlay.classList.add('active');
                showGrid = true;
            }

            // check orientation and prompt user if portrait on touch devices
            checkOrientationAndPrompt();
            window.addEventListener('orientationchange', checkOrientationAndPrompt);
            window.addEventListener('resize', checkOrientationAndPrompt);
        }

        let userConfirmedOrientation = false;

        function isTouchDevice() {
            return ('ontouchstart' in window) || navigator.maxTouchPoints > 0;
        }

        function checkOrientationAndPrompt() {
            const rotatePrompt = document.getElementById('rotatePrompt');
            if (!rotatePrompt) return;

            // consider portrait if height > width
            const inPortrait = window.innerHeight > window.innerWidth;

            if (isTouchDevice() && inPortrait && !userConfirmedOrientation) {
                rotatePrompt.style.display = 'block';
            } else {
                rotatePrompt.style.display = 'none';
            }
        }

        async function startCamera() {
            const startBtn = document.getElementById('startCameraBtn');
            const permissionInfo = document.getElementById('permissionInfo');
            const video = document.getElementById('preview');
            
            try {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                startBtn.textContent = '⏳ Starting...';
                permissionInfo.style.display = 'block';

                // Mobile-optimized constraints
                const constraints = {
                    video: {
                        facingMode: useFrontCamera ? 'user' : 'environment',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                };

                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;
                
                // Reset classes and apply correct ones
                video.className = '';
                if (useFrontCamera) {
                    video.classList.add('front-camera-fix');
                } else {
                    video.classList.add('back-camera');
                }
                
                // Wait for video to be ready
                await new Promise((resolve) => {
                    video.onloadedmetadata = () => {
                        video.play().then(resolve).catch(resolve);
                    };
                });
                
                startBtn.style.display = 'none';
                permissionInfo.style.display = 'none';
                cameraStarted = true;
                
                showNotification('✅ Camera ready!');
                
            } catch (error) {
                console.error('Camera error:', error);
                permissionInfo.style.display = 'none';
                
                let errorMessage = '❌ Camera Error: ';
                
                if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                    errorMessage += 'Permission denied. Please allow camera access in your browser settings.';
                } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                    errorMessage += 'No camera found. Please check your device.';
                } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                    errorMessage += 'Camera is being used by another app. Please close other apps and try again.';
                } else if (error.name === 'OverconstrainedError') {
                    errorMessage += 'Camera settings not supported. Trying alternative...';
                    // Try with basic constraints
                    tryBasicCamera();
                    return;
                } else if (error.name === 'TypeError') {
                    errorMessage += 'Camera not supported in this browser. Please use Chrome, Safari, or Firefox.';
                } else {
                    errorMessage += error.message || 'Unknown error. Please refresh and try again.';
                }
                
                showNotification(errorMessage, 7000);
                startBtn.style.display = 'block';
                startBtn.textContent = '🔄 Try Again';
            }
        }

        async function tryBasicCamera() {
            const video = document.getElementById('preview');
            const startBtn = document.getElementById('startCameraBtn');
            
            try {
                const constraints = {
                    video: { facingMode: useFrontCamera ? 'user' : 'environment' },
                    audio: false
                };

                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;
                
                video.className = '';
                if (useFrontCamera) {
                    video.classList.add('front-camera-fix');
                } else {
                    video.classList.add('back-camera');
                }
                
                await new Promise((resolve) => {
                    video.onloadedmetadata = () => {
                        video.play().then(resolve).catch(resolve);
                    };
                });
                
                startBtn.style.display = 'none';
                cameraStarted = true;
                showNotification('✅ Camera ready!');
                
            } catch (err) {
                showNotification('❌ Could not start camera with basic settings', 5000);
                startBtn.style.display = 'block';
                startBtn.textContent = '🔄 Try Again';
            }
        }

        function setupEventListeners() {
            document.getElementById('startCameraBtn').addEventListener('click', startCamera);
            document.getElementById('captureBtn').addEventListener('click', capturePhoto);
            document.getElementById('nextBtn').addEventListener('click', goToEdit);
            document.getElementById('flipCameraBtn').addEventListener('click', flipCamera);
            document.getElementById('galleryBtn').addEventListener('click', openGallery);
            document.getElementById('undoBtn').addEventListener('click', undoLastPhoto);
            document.getElementById('uploadBtn').addEventListener('click', () => {
                document.getElementById('uploadFile').click();
            });
            document.getElementById('gridBtn').addEventListener('click', toggleGrid);
            document.getElementById('clearAllBtn').addEventListener('click', clearAllPhotos);
            document.getElementById('closeGallery').addEventListener('click', closeGallery);
            document.getElementById('uploadFile').addEventListener('change', handleFileUpload);
            // rotate prompt tap-to-confirm
            const rotatePrompt = document.getElementById('rotatePrompt');
            if (rotatePrompt) {
                rotatePrompt.addEventListener('click', () => {
                    userConfirmedOrientation = true;
                    rotatePrompt.style.display = 'none';
                    showNotification('Thanks — you can now capture photos');
                });
            }
        }

        function capturePhoto() {
            if (!cameraStarted) {
                showNotification('Please start the camera first!');
                return;
            }
            if (capturedPhotos.length >= 8) {
                showNotification('Maximum 8 photos reached!');
                return;
            }

            const timerDuration = parseInt(document.getElementById('timerSelect').value);
            if (timerDuration === 0) {
                performCapture();
            } else {
                const countdownOverlay = document.getElementById('countdownOverlay');
                countdownOverlay.style.display = 'block';
                countdownOverlay.innerText = 'Ready';
                setTimeout(() => {
                    for (let j = timerDuration; j > 0; j--) {
                        setTimeout(() => {
                            countdownOverlay.innerText = j;
                        }, (timerDuration - j + 1) * 1000);
                    }
                    setTimeout(() => {
                        countdownOverlay.style.display = 'none';
                        performCapture();
                    }, (timerDuration + 1) * 1000);
                }, 1000);
            }
        }

        function performCapture() {
            const video = document.getElementById('preview');
            const canvas = document.getElementById('captureCanvas');
            const ctx = canvas.getContext('2d');

            // Final output: LANDSCAPE 1440×1080
            const LAND_W = 1440;
            const LAND_H = 1080;
            canvas.width = LAND_W;
            canvas.height = LAND_H;

            // Exact visible area from portrait preview
            const displayW = video.clientWidth;
            const displayH = video.clientHeight;
            const videoW = video.videoWidth;
            const videoH = video.videoHeight;

            const scale = Math.max(displayW / videoW, displayH / videoH);
            const visibleW = displayW / scale;
            const visibleH = displayH / scale;
            const sx = (videoW - visibleW) / 2;
            const sy = (videoH - visibleH) / 2;

            ctx.clearRect(0, 0, LAND_W, LAND_H);
            ctx.save();

            // Always rotate 90° clockwise to turn portrait feed into landscape canvas
            ctx.translate(LAND_W, 0);
            ctx.rotate(Math.PI / 2);

            // Correct orientation based on camera type
            if (useFrontCamera) {
                // Front camera: mirrored horizontally by browser → we want mirrored in result
                // After 90° rotation, it appears correct if we flip vertically
                ctx.scale(1, -1);
                ctx.translate(0, -LAND_W);
            } else {
                // Back camera: NOT mirrored by browser
                // After 90° rotation, it's usually upside down → flip 180°
                ctx.scale(-1, -1);
                ctx.translate(-LAND_H, -LAND_W);
            }

            // Draw the visible portion of the video
            ctx.drawImage(
                video,
                sx, sy, visibleW, visibleH,
                0, 0, LAND_H, LAND_W
            );

            ctx.restore();

            // Flash effect
            const flash = document.createElement('div');
            flash.className = 'flash';
            document.querySelector('.camera-view').appendChild(flash);
            setTimeout(() => flash.remove(), 300);

            // Save perfect upright landscape photo
            const photoData = canvas.toDataURL('image/jpeg', 0.95);
            capturedPhotos.push({
                id: Date.now().toString(),
                data: photoData,
                timestamp: new Date().toLocaleString()
            });

            savePhotosToStorage();
            updatePhotoCounter();
            if (navigator.vibrate) navigator.vibrate(50);
            showNotification(`Photo ${capturedPhotos.length}/8 captured!`);
            if (capturedPhotos.length === 8) {
                showNotification('All 8 photos captured! Click Next to edit.');
            }
        }

        async function flipCamera() {
            if (!cameraStarted) {
                showNotification('📷 Please start the camera first!');
                return;
            }
            
            useFrontCamera = !useFrontCamera;
            showNotification(`🔄 Switching to ${useFrontCamera ? 'front' : 'back'} camera...`);
            await startCamera();
        }

        function handleFileUpload(event) {
            const files = event.target.files;
            if (!files || files.length === 0) return;
            
            const availableSlots = 8 - capturedPhotos.length;
            if (availableSlots <= 0) {
                showNotification('✋ Maximum 8 photos reached!');
                event.target.value = '';
                return;
            }
            
            const filesToProcess = Math.min(files.length, availableSlots);
            
            for (let i = 0; i < filesToProcess; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        
                        canvas.width = 1440;
                        canvas.height = 1080;
                        
                        const aspectRatio = 4 / 3;
                        const imgAspectRatio = img.width / img.height;
                        let sourceX = 0;
                        let sourceY = 0;
                        let sourceWidth = img.width;
                        let sourceHeight = img.height;

                        if (imgAspectRatio > aspectRatio) {
                            sourceWidth = img.height * aspectRatio;
                            sourceX = (img.width - sourceWidth) / 2;
                        } else {
                            sourceHeight = img.width / aspectRatio;
                            sourceY = (img.height - sourceHeight) / 2;
                        }

                        ctx.drawImage(
                            img,
                            sourceX, sourceY, sourceWidth, sourceHeight,
                            0, 0, canvas.width, canvas.height
                        );
                        
                        const photo = {
                            id: (Date.now() + Math.random()).toString(), // Ensure unique ID
                            data: canvas.toDataURL('image/jpeg', 0.92),
                            timestamp: new Date().toLocaleString()
                        };
                        
                        capturedPhotos.push(photo);
                        savePhotosToStorage();
                        updatePhotoCounter();
                        
                        if (capturedPhotos.length === 8) {
                            setTimeout(() => {
                                showNotification('🎉 All 8 photos complete!');
                            }, 1000);
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
            
            if (files.length > filesToProcess) {
                showNotification(`📤 ${filesToProcess} photos uploaded! (${files.length - filesToProcess} skipped - max 8)`);
            } else {
                showNotification(`📤 ${filesToProcess} photo${filesToProcess > 1 ? 's' : ''} uploaded!`);
            }
            
            event.target.value = '';
            
            if (capturedPhotos.length === 8) {
                const captureBtn = document.getElementById('captureBtn');
                captureBtn.innerHTML = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"></path></svg>';
                captureBtn.classList.add('next-btn');
                captureBtn.removeEventListener('click', capturePhoto);
                captureBtn.addEventListener('click', goToEdit);
                setTimeout(() => {
                    showNotification('🎉 All 8 photos complete! Tap Next to edit.');
                }, 1000);
            }
        }

        function openGallery() {
            if (capturedPhotos.length === 0) {
                showNotification('📷 No photos yet!');
                return;
            }
            
            const modal = document.getElementById('galleryModal');
            const grid = document.getElementById('galleryGrid');
            const count = document.getElementById('galleryCount');
            
            count.textContent = capturedPhotos.length;
            grid.innerHTML = '';
            
            capturedPhotos.forEach((photo, index) => {
                const item = document.createElement('div');
                item.className = 'gallery-item';

                const img = document.createElement('img');
                img.src = photo.data;
                img.alt = `Photo ${index + 1}`;
                img.style.cursor = 'zoom-in';
                img.addEventListener('click', () => openFullscreen(photo.data));

                const delBtn = document.createElement('button');
                delBtn.className = 'delete-photo';
                delBtn.textContent = '×';
                delBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    deletePhoto(photo.id);
                });

                item.appendChild(img);
                item.appendChild(delBtn);
                grid.appendChild(item);
            });
            
            modal.classList.add('active');
        }

        function closeGallery() {
            document.getElementById('galleryModal').classList.remove('active');
        }

        function deletePhoto(photoId) {
            if (confirm('Delete this photo?')) {
                capturedPhotos = capturedPhotos.filter(p => p.id !== photoId);
                savePhotosToStorage();
                updatePhotoCounter();
                
                if (capturedPhotos.length === 0) {
                    closeGallery();
                } else {
                    openGallery();
                }
                showNotification('🗑️ Photo deleted');
                location.reload();
            }
        }

        function undoLastPhoto() {
            if (capturedPhotos.length === 0) {
                showNotification('❌ No photos to undo!');
                return;
            }
            capturedPhotos.pop();
            savePhotosToStorage();
            updatePhotoCounter();
            showNotification('↩️ Last photo removed');
            location.reload();
        }

        function clearAllPhotos() {
            if (capturedPhotos.length === 0) {
                showNotification('❌ No photos to clear!');
                return;
            }
            if (confirm('Clear all photos? This cannot be undone.')) {
                capturedPhotos = [];
                savePhotosToStorage();
                updatePhotoCounter();
                closeGallery();
                showNotification('🗑️ All photos cleared');
                location.reload();
            }
        }

        function toggleGrid() {
            showGrid = !showGrid;
            const gridOverlay = document.getElementById('gridOverlay');
            if (showGrid) {
                gridOverlay.classList.add('active');
            } else {
                gridOverlay.classList.remove('active');
            }
            showNotification(showGrid ? '⊞ Grid enabled' : '⊟ Grid disabled');
        }

        function goToEdit() {
            window.location.href = 'edit.php';
        }

        function updatePhotoCounter() {
            document.getElementById('photoCounter').textContent = `${capturedPhotos.length}/8`;
            const badge = document.getElementById('galleryBadge');
            badge.textContent = capturedPhotos.length;
            badge.style.display = capturedPhotos.length > 0 ? 'flex' : 'none';
            
            // Update view and buttons based on photo count
            const videoContainer = document.querySelector('.video-container');
            const capturedPhotosGrid = document.getElementById('capturedPhotosGrid');
            const captureBtn = document.getElementById('captureBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            if (capturedPhotos.length >= 8 && !forceLiveView) {
                // Show captured photos grid
                videoContainer.style.display = 'none';
                capturedPhotosGrid.style.display = 'grid';
                populateCapturedPhotosGrid();
                captureBtn.style.display = 'none';
                nextBtn.style.display = 'block';
            } else {
                // Show live view
                videoContainer.style.display = 'block';
                capturedPhotosGrid.style.display = 'none';
                captureBtn.style.display = 'block';
                nextBtn.style.display = 'none';
            }
        }

        function populateCapturedPhotosGrid() {
            const grid = document.getElementById('capturedPhotosGrid');
            grid.innerHTML = '';
            capturedPhotos.forEach(photo => {
                const img = document.createElement('img');
                img.src = photo.data;
                img.alt = 'Captured photo';
                grid.appendChild(img);
            });
        }

        function showNotification(message, duration = 3000) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
            }, duration);
        }

        function savePhotosToStorage() {
            try {
                localStorage.setItem('capturedPhotos', JSON.stringify(capturedPhotos));
            } catch (e) {
                console.error('Failed to save photos:', e);
            }
        }

        function loadPhotosFromStorage() {
            try {
                const saved = localStorage.getItem('capturedPhotos');
                if (saved) {
                    capturedPhotos = JSON.parse(saved);
                    forceLiveView = localStorage.getItem('hideNextButton') === 'true';
                    if (forceLiveView) {
                        localStorage.removeItem('hideNextButton');
                    }
                    updatePhotoCounter();
                    if (capturedPhotos.length >= 8 && !forceLiveView) {
                        const captureBtn = document.getElementById('captureBtn');
                        captureBtn.innerHTML = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"></path></svg>';
                        captureBtn.classList.add('next-btn');
                        captureBtn.removeEventListener('click', capturePhoto);
                        captureBtn.addEventListener('click', () => window.location.href = 'edit.php');
                    }
                }
            } catch (e) {
                console.error('Failed to load photos:', e);
            }
        }

        window.addEventListener('DOMContentLoaded', init);
        // Fullscreen viewer for captured images
        function openFullscreen(photoData) {
            let modal = document.getElementById('fullscreenModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'fullscreenModal';
                modal.addEventListener('click', closeFullscreen);
                document.body.appendChild(modal);
            }
            // clear and create image element to allow adding rotation class
            modal.innerHTML = '';
            const img = document.createElement('img');
            img.id = 'fullscreenImage';
            img.src = photoData;
            modal.appendChild(img);
            modal.style.display = 'flex';

            // try request fullscreen for better UX
            try {
                if (modal.requestFullscreen) modal.requestFullscreen();
                else if (modal.webkitRequestFullscreen) modal.webkitRequestFullscreen();
            } catch (e) {
                // ignore
            }

            // escape handler
            const escHandler = (e) => { if (e.key === 'Escape') closeFullscreen(); };
            document.addEventListener('keydown', escHandler);
            modal._escHandler = escHandler;
        }

        function closeFullscreen() {
            const modal = document.getElementById('fullscreenModal');
            if (!modal) return;
            if (document.fullscreenElement) {
                document.exitFullscreen && document.exitFullscreen();
            } else if (document.webkitFullscreenElement) {
                document.webkitExitFullscreen && document.webkitExitFullscreen();
            }
            modal.style.display = 'none';
            if (modal._escHandler) document.removeEventListener('keydown', modal._escHandler);
        }
    </script>
</body>
</html>