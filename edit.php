<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Booth - Edit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #fafafa; /* White with a subtle black tint */
            min-height: 100vh;
            padding: 20px;
        }

        .thanksgiving-text {
            text-align: center;
            font-family: 'Brush Script MT', cursive;
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            color: black;
        }

        .back-btn {
            background: rgba(0, 0, 0, 0.1);
            border: none;
            padding: 8px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            font-size: 14px;
            color: black;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .icon-btn {
            background: rgba(0, 0, 0, 0.1);
            border: none;
            padding: 8px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            font-size: 14px;
            color: black;
        }

        .preview-container {
            background: white;
            border-radius: 20px;
            padding: 40px 20px;
            margin: 15px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .frame-wrapper {
            position: relative;
            background: #f5f5f5;
            padding: 0;
            width: 150px;
            height: 450px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
        }

        .photo-strip {
            background: transparent;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        /* 4 strips: 1 column × 4 rows (vertical) */
        .photo-strip.four {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: repeat(4, 1fr);
            gap: 10px 0;
            height: 400px; /* Adjusted for smaller frame */
        }

        /* 8 strips: 2 columns × 4 rows */
        .photo-strip.eight {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: repeat(4, 1fr);
            gap: 10px 0;
            height: 480px;
        }

        .photo-slot {
            width: 100%;
            height: 100%;
            background: #f9f9f9;
            border: 2px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            font-size: 48px;
            overflow: hidden;
            position: relative;
        }

        .photo-slot::before {
            content: '';
            float: left;
            padding-top: 150%; /* 3:2 ratio = height / width = 3/2 = 150% */
        }

        .photo-slot::after {
            content: '';
            display: block;
            clear: both;
        }

        .photo-slot > * {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .photo-slot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Bottom space for logo/text */
        .bottom-space {
            height: auto;
            min-height: 20px;
            width: auto;
            max-width: 100%;
            margin-top: 30px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #999;
            cursor: move;
            position: absolute;
            bottom: 10px;
            left: 10px;
            z-index: 10;
            padding: 5px;
            border-radius: 5px;
        }

        .tabs-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 30px 0;
        }

        .tab {
            background: none;
            border: none;
            color: #666;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            padding: 10px 0;
            position: relative;
            transition: color 0.3s;
        }

        .tab.active {
            color: #333;
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: white;
        }

        .tab-icon {
            display: block;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 30px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-radius: 20px 20px 0 0;
            padding: 30px 20px;
            box-shadow: 0 -5px 30px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .modal.active { display: block; }

        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .modal-backdrop.active { display: block; }

        .modal-header { text-align: center; margin-bottom: 10px; }
        .modal-title { font-size: 20px; font-weight: 600; margin-bottom: 5px; }
        .modal-subtitle { color: #666; font-size: 14px; }

        .upload-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .strip-options {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 20px 0;
        }

        .strip-btn {
            padding: 12px 30px;
            border: 2px solid #333;
            background: white;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .strip-btn.active {
            background: #333;
            color: white;
        }

        .close-btn {
            width: 100%;
            padding: 15px;
            background: #f5f5f5;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
        }

        .hashtags-section {
            margin-top: 20px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            text-align: center;
        }

        .hashtags-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        .hashtags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 15px;
        }

        .hashtag-btn {
            background: #667eea;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .hashtag-btn:hover {
            background: #5a67d8;
        }

        .custom-hashtag {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 15px;
        }

        .custom-hashtag input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 14px;
            flex: 1;
            max-width: 200px;
        }

        .custom-hashtag button {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
        }

        .selected-hashtags {
            font-size: 14px;
            color: #666;
            min-height: 20px;
        }

        .cancel-btn {
            width: 100%;
            padding: 15px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .modal-tabs { display: flex; gap: 20px; margin: 20px 0; border-bottom: 1px solid #eee; }
        .modal-tab { background: none; border: none; padding: 10px 15px; font-size: 14px; color: #999; cursor: pointer; position: relative; }
        .modal-tab.active { color: #333; font-weight: 600; }
        .modal-tab.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: #333; }

        .frames-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin: 20px 0;
            max-height: 300px;
            overflow-y: auto;
        }

        .frame-option {
            position: relative;
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: border-color 0.3s;
        }

        .frame-option.selected { border-color: #667eea; }

        .frame-option img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .frame-label {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 11px;
            padding: 5px;
            text-align: center;
        }

        .checkmark {
            position: absolute;
            top: 5px; right: 5px;
            width: 24px; height: 24px;
            background: #667eea;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .frame-option.selected .checkmark { display: flex; }
    </style>
</head>
<body>
    <div class="thanksgiving-text">HAPPY THANKSGIVING BRETHREN!!!</div>
    <div class="container">
        <div class="header">
            <button class="back-btn" onclick="goBack()">← Back</button>
            <div class="action-btns">
                <button class="icon-btn" id="downloadBtn" onclick="downloadPhoto()">⬇ Download</button>
                <button class="icon-btn" id="uploadBtn" onclick="sharePhoto()">⤴ Upload</button>
            </div>
        </div>

        <div class="preview-container">
            <div class="frame-wrapper">
                <div class="photo-strip four" id="photoStrip">
                    <div class="photo-slot">+</div>
                    <div class="photo-slot">+</div>
                    <div class="photo-slot">+</div>
                    <div class="photo-slot">+</div>
                </div>
            </div>
        </div>

        <div class="tabs-container">
            <button class="tab active" onclick="openLayoutModal()">
                <span class="tab-icon">📋</span>
                Layouts
            </button>
            <button class="tab" onclick="openFrameModal()">
                <span class="tab-icon">🖼️</span>
                Frames
            </button>
            <button class="tab" onclick="openHashtagsModal()">
                <span class="tab-icon">#️⃣</span>
                Hashtags
            </button>
        </div>

        <div class="footer">
            <p>© 2025 @marcjoshuavigilia. All rights reserved</p>
            <p class="version">Version: v1 Mobile</p>
        </div>
    </div>

    <!-- Layout Modal -->
    <div class="modal-backdrop" id="layoutBackdrop" onclick="closeLayoutModal()"></div>
    <div class="modal" id="layoutModal">
        <div class="modal-header">
            <div class="modal-title">Select Photo Strip</div>
            <div class="modal-subtitle">Choose your preferred layout</div>
        </div>
        <div class="strip-options">
            <button class="strip-btn active">Photo Strip (4)</button>
            <button class="strip-btn">Photo Strip (8)</button>
        </div>
        <button class="close-btn" onclick="closeLayoutModal()">Close</button>
    </div>

    <!-- Frame Modal -->
    <div class="modal-backdrop" id="frameBackdrop" onclick="closeFrameModal()"></div>
    <div class="modal" id="frameModal">
        <div class="modal-header">
            <div class="modal-title">Select Frame</div>
            <div class="modal-subtitle">Choose a stylish frame to highlight your photos</div>
        </div>
        <div class="modal-tabs">
            <button class="modal-tab active">Frames</button>
            <button class="modal-tab">Solid Colors</button>
            <button class="modal-tab">Gradients</button>
        </div>
        <div class="frames-grid">
            <div class="frame-option selected">
                <div style="background: white; width: 100%; height: 100%;"></div>
                <div class="frame-label">No Frame</div>
                <div class="checkmark">✓</div>
            </div>
            <div class="frame-option">
                <img src="frames/frame0.png" style="width: 100%; height: 100%; object-fit: cover;" alt="Frame 1">
                <div class="frame-label">Frame 1</div>
                <div class="checkmark">✓</div>
            </div>
            <div class="frame-option">
                <img src="frames/frame2.png" style="width: 100%; height: 100%; object-fit: cover;" alt="Frame 2">
                <div class="frame-label">Frame 2</div>
                <div class="checkmark">✓</div>
            </div>
            <div class="frame-option">
                <img src="frames/frame3.png" style="width: 100%; height: 100%; object-fit: cover;" alt="Frame 3">
                <div class="frame-label">Frame 3</div>
                <div class="checkmark">✓</div>
            </div>
            <div class="frame-option">
                <img src="frames/frame4.png" style="width: 100%; height: 100%; object-fit: cover;" alt="Frame 14">
                <div class="frame-label">Frame 14</div>
                <div class="checkmark">✓</div>
            </div>
        </div>
        <button class="close-btn" onclick="closeFrameModal()">Close</button>
    </div>

    <!-- Hashtags Modal -->
    <div class="modal-backdrop" id="hashtagsBackdrop" onclick="closeHashtagsModal()"></div>
    <div class="modal" id="hashtagsModal">
        <div class="modal-header">
            <div class="modal-title">Add Hashtags</div>
            <div class="modal-subtitle">Choose hashtags to add to your photobooth, click the hashtags below again to delete it</div>
        </div>
        <div class="hashtags-section">
            <div class="hashtags-list">
                <button class="hashtag-btn" onclick="addHashtag('#PNK')">#PNK</button>
                <button class="hashtag-btn" onclick="addHashtag('#BINHI')">#BINHI</button>
                <button class="hashtag-btn" onclick="addHashtag('#KADIWA')">#KADIWA</button>
                <button class="hashtag-btn" onclick="addHashtag('#BUKLOD')">#BUKLOD</button>

            </div>
            <div class="custom-hashtag">
                <input type="text" id="customHashtagInput" placeholder="Custom hashtag" maxlength="20">
                <button onclick="addCustomHashtag()">Add</button>
            </div>
            <div class="selected-hashtags" id="selectedHashtags"></div>
        </div>
        <button class="close-btn" onclick="closeHashtagsModal()">Close</button>
    </div>

    <!-- Photo Select Modal -->
    <div class="modal-backdrop" id="photoSelectBackdrop" onclick="closePhotoSelectModal()"></div>
    <div class="modal" id="photoSelectModal">
        <div class="modal-header">
            <div class="modal-title">Select Photo</div>
            <div class="modal-subtitle">Choose a photo to place in this slot</div>
        </div>
        <div class="frames-grid" id="photoSelectGrid"></div>
        <button class="close-btn" onclick="closePhotoSelectModal()">Close</button>
    </div>

    <!-- Upload Modal -->
    <div class="modal-backdrop" id="uploadBackdrop" onclick="closeUploadModal()"></div>
    <div class="modal" id="uploadModal">
        <div class="modal-header">
            <div class="modal-title" id="uploadTitle">Sending Image.....</div>
            <div class="modal-subtitle" id="uploadMessage">Please wait while we send your photobooth image for your hardcopy.</div>
        </div>
        <div class="upload-spinner" id="uploadSpinner"></div>
        <button class="close-btn" id="uploadCloseBtn" onclick="closeUploadModal()" style="display: none;">Close</button>
        <button class="cancel-btn" id="uploadCancelBtn" onclick="cancelUpload()" style="display: block;">Cancel</button>
    </div>

    <script>
        let currentLayout = 4;
        let currentFrame = 'No Frame';
        let capturedPhotos = [];
        let selectedPhotos = [];
        let selectedSlotIndex = null;
        let uploadController;
        let selectedHashtags = [];
        let isDragging = false;
        let dragOffsetX = 0;
        let dragOffsetY = 0;
        let draggedHashtag = null;
        let scale = 0.8; // Default smaller scale
        let initialDistance = 0;

        function loadPhotos() {
            const stored = localStorage.getItem('capturedPhotos');
            if (stored) {
                capturedPhotos = JSON.parse(stored);
                selectedPhotos = new Array(currentLayout).fill(null);
                displayPhotos();
            }
            const storedHashtags = localStorage.getItem('selectedHashtags');
            if (storedHashtags) {
                selectedHashtags = JSON.parse(storedHashtags);
                updateHashtagsDisplay();
            }
        }

        function displayPhotos() {
            const strips = document.querySelectorAll('.photo-strip');
            let allSlots = [];
            strips.forEach(strip => {
                allSlots = allSlots.concat(Array.from(strip.querySelectorAll('.photo-slot')));
            });
            allSlots.forEach((slot, index) => {
                if (selectedPhotos[index] !== null && capturedPhotos[selectedPhotos[index]]) {
                    slot.innerHTML = `<img src="${capturedPhotos[selectedPhotos[index]].data}" alt="Photo ${index + 1}">`;
                } else {
                    slot.innerHTML = '+';
                }
            });
        }

        function openLayoutModal() {
            document.getElementById('layoutModal').classList.add('active');
            document.getElementById('layoutBackdrop').classList.add('active');
        }

        function closeLayoutModal() {
            document.getElementById('layoutModal').classList.remove('active');
            document.getElementById('layoutBackdrop').classList.remove('active');
        }

        function openFrameModal() {
            document.getElementById('frameModal').classList.add('active');
            document.getElementById('frameBackdrop').classList.add('active');
        }

        function closeFrameModal() {
            document.getElementById('frameModal').classList.remove('active');
            document.getElementById('frameBackdrop').classList.remove('active');
        }

        function openHashtagsModal() {
            document.getElementById('hashtagsModal').classList.add('active');
            document.getElementById('hashtagsBackdrop').classList.add('active');
        }

        function closeHashtagsModal() {
            document.getElementById('hashtagsModal').classList.remove('active');
            document.getElementById('hashtagsBackdrop').classList.remove('active');
        }

        function openPhotoSelectModal() {
            const grid = document.getElementById('photoSelectGrid');
            grid.innerHTML = '';
            capturedPhotos.forEach((photo, idx) => {
                const div = document.createElement('div');
                div.className = 'frame-option';
                div.innerHTML = `<img src="${photo.data}" alt="Photo ${idx+1}">`;
                div.onclick = () => selectPhoto(idx);
                grid.appendChild(div);
            });
            document.getElementById('photoSelectModal').classList.add('active');
            document.getElementById('photoSelectBackdrop').classList.add('active');
        }

        function closePhotoSelectModal() {
            document.getElementById('photoSelectModal').classList.remove('active');
            document.getElementById('photoSelectBackdrop').classList.remove('active');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.remove('active');
            document.getElementById('uploadBackdrop').classList.remove('active');
        }

        function cancelUpload() {
            if (uploadController) uploadController.abort();
            closeUploadModal();
            document.getElementById('uploadBtn').innerHTML = '⤴ Upload';
            document.getElementById('uploadBtn').disabled = false;
        }

        function selectPhoto(photoIndex) {
            selectedPhotos[selectedSlotIndex] = photoIndex;
            displayPhotos();
            closePhotoSelectModal();
        }

        function goBack() { window.history.back(); }
        function downloadPhoto() {
            const frameWrappers = document.querySelectorAll('.frame-wrapper');
            const scale = 16; // Higher quality scale factor
            let canvas, ctx, totalWidth, height;

            if (frameWrappers.length === 1) {
                // Single frame (4 strips)
                const frameWrapper = frameWrappers[0];
                canvas = document.createElement('canvas');
                ctx = canvas.getContext('2d');
                canvas.width = frameWrapper.offsetWidth * scale;
                canvas.height = frameWrapper.offsetHeight * scale;
                ctx.scale(scale, scale);
                drawFrame(frameWrapper, 0, 0);
            } else {
                // Two frames (8 strips)
                const frame1 = frameWrappers[0];
                const frame2 = frameWrappers[1];
                totalWidth = frame1.offsetWidth + frame2.offsetWidth; // no gap
                height = frame1.offsetHeight;
                canvas = document.createElement('canvas');
                ctx = canvas.getContext('2d');
                canvas.width = totalWidth * scale;
                canvas.height = height * scale;
                ctx.scale(scale, scale);
                drawFrame(frame1, 0, 0);
                drawFrame(frame2, frame1.offsetWidth, 0);
            }

            function drawFrame(frameWrapper, offsetX, offsetY) {
                // Draw background
                const bg = frameWrapper.style.background;
                if (bg.includes('url')) {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = function() {
                        ctx.drawImage(img, offsetX, offsetY, frameWrapper.offsetWidth, frameWrapper.offsetHeight);
                        drawSlots(frameWrapper, offsetX, offsetY);
                        if (frameWrappers.length === 1 || offsetX > 0) {
                            finalizeDownload();
                        }
                    };
                    img.src = bg.match(/url\(["']?([^"']*)["']?\)/)[1];
                } else {
                    ctx.fillStyle = bg || '#f5f5f5';
                    ctx.fillRect(offsetX, offsetY, frameWrapper.offsetWidth, frameWrapper.offsetHeight);
                    drawSlots(frameWrapper, offsetX, offsetY);
                    if (frameWrappers.length === 1 || offsetX > 0) {
                        finalizeDownload();
                    }
                }
            }

            function drawSlots(frameWrapper, offsetX, offsetY) {
                const slots = frameWrapper.querySelectorAll('.photo-slot');
                slots.forEach(slot => {
                    const img = slot.querySelector('img');
                    if (img) {
                        const rect = slot.getBoundingClientRect();
                        const wrapperRect = frameWrapper.getBoundingClientRect();
                        const x = rect.left - wrapperRect.left + offsetX;
                        const y = rect.top - wrapperRect.top + offsetY;
                        const w = rect.width;
                        const h = rect.height;
                        ctx.drawImage(img, x, y, w, h);
                    }
                });
                
                // Hashtags are drawn separately
            }

            function drawHashtags() {
                selectedHashtags.forEach(hashtag => {
                    const x = hashtag.x;
                    const y = hashtag.y;
                    
                    // Draw hashtag
                    ctx.save();
                    ctx.translate(x, y);
                    ctx.scale(hashtag.scale, hashtag.scale);
                    ctx.fillStyle = '#666';
                    ctx.font = 'bold 9px Arial';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'top';
                    ctx.fillText(hashtag.text, 0, 0);
                    ctx.restore();
                });
            }

            function finalizeDownload() {
                drawHashtags();
                // Download the canvas as high quality PNG
                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'photobooth_strip.png';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                }, 'image/png');
            }
        }
        function sharePhoto() {
            const btn = document.getElementById('uploadBtn');
            btn.innerHTML = '⤴ Uploading...';
            btn.disabled = true;

            uploadController = new AbortController();

            // Show loading modal
            document.getElementById('uploadModal').classList.add('active');
            document.getElementById('uploadBackdrop').classList.add('active');
            document.getElementById('uploadTitle').textContent = 'Sending Image.....';
            document.getElementById('uploadMessage').textContent = 'Please wait while we send your photobooth image for your hardcopy.';
            document.getElementById('uploadSpinner').style.display = 'block';
            document.getElementById('uploadCloseBtn').style.display = 'none';
            document.getElementById('uploadCancelBtn').style.display = 'block';

            const frameWrappers = document.querySelectorAll('.frame-wrapper');
            const scale = 16;
            let canvas, ctx, totalWidth, height;

            if (frameWrappers.length === 1) {
                const frameWrapper = frameWrappers[0];
                canvas = document.createElement('canvas');
                ctx = canvas.getContext('2d');
                canvas.width = frameWrapper.offsetWidth * scale;
                canvas.height = frameWrapper.offsetHeight * scale;
                ctx.scale(scale, scale);
                drawFrame(frameWrapper, 0, 0);
            } else {
                const frame1 = frameWrappers[0];
                const frame2 = frameWrappers[1];
                totalWidth = frame1.offsetWidth + frame2.offsetWidth;
                height = frame1.offsetHeight;
                canvas = document.createElement('canvas');
                ctx = canvas.getContext('2d');
                canvas.width = totalWidth * scale;
                canvas.height = height * scale;
                ctx.scale(scale, scale);
                drawFrame(frame1, 0, 0);
                drawFrame(frame2, frame1.offsetWidth, 0);
            }

            function drawFrame(frameWrapper, offsetX, offsetY) {
                const bg = frameWrapper.style.background;
                if (bg.includes('url')) {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = function() {
                        ctx.drawImage(img, offsetX, offsetY, frameWrapper.offsetWidth, frameWrapper.offsetHeight);
                        drawSlots(frameWrapper, offsetX, offsetY);
                        if (frameWrappers.length === 1 || offsetX > 0) {
                            sendEmail();
                        }
                    };
                    img.src = bg.match(/url\(["']?([^"']*)["']?\)/)[1];
                } else {
                    ctx.fillStyle = bg || '#f5f5f5';
                    ctx.fillRect(offsetX, offsetY, frameWrapper.offsetWidth, frameWrapper.offsetHeight);
                    drawSlots(frameWrapper, offsetX, offsetY);
                    if (frameWrappers.length === 1 || offsetX > 0) {
                        sendEmail();
                    }
                }
            }

            function drawSlots(frameWrapper, offsetX, offsetY) {
                const slots = frameWrapper.querySelectorAll('.photo-slot');
                slots.forEach(slot => {
                    const img = slot.querySelector('img');
                    if (img) {
                        const rect = slot.getBoundingClientRect();
                        const wrapperRect = frameWrapper.getBoundingClientRect();
                        const x = rect.left - wrapperRect.left + offsetX;
                        const y = rect.top - wrapperRect.top + offsetY;
                        const w = rect.width;
                        const h = rect.height;
                        ctx.drawImage(img, x, y, w, h);
                    }
                });
                
                // Hashtags are drawn separately
            }

            function drawHashtags() {
                selectedHashtags.forEach(hashtag => {
                    const x = hashtag.x;
                    const y = hashtag.y;
                    
                    // Draw hashtag
                    ctx.save();
                    ctx.translate(x, y);
                    ctx.scale(hashtag.scale, hashtag.scale);
                    ctx.fillStyle = '#666';
                    ctx.font = 'bold 12px Arial';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'top';
                    ctx.fillText(hashtag.text, 0, 0);
                    ctx.restore();
                });
            }

            function sendEmail() {
                drawHashtags();
                const formData = new FormData();
                canvas.toBlob(function(blob) {
                    formData.append('image', blob, 'photobooth_strip.png');
                    fetch('send_email.php', {
                        method: 'POST',
                        body: formData,
                        signal: uploadController.signal
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('uploadTitle').textContent = 'Success!';
                            document.getElementById('uploadMessage').textContent = 'Your photobooth image has been sent via email.';
                            document.getElementById('uploadSpinner').style.display = 'none';
                            document.getElementById('uploadCloseBtn').style.display = 'block';
                            document.getElementById('uploadCancelBtn').style.display = 'none';
                        } else {
                            document.getElementById('uploadTitle').textContent = 'Error';
                            document.getElementById('uploadMessage').textContent = 'Failed to send email: ' + data.error;
                            document.getElementById('uploadSpinner').style.display = 'none';
                            document.getElementById('uploadCloseBtn').style.display = 'block';
                            document.getElementById('uploadCancelBtn').style.display = 'none';
                        }
                        btn.innerHTML = '⤴ Upload';
                        btn.disabled = false;
                    })
                    .catch(error => {
                        document.getElementById('uploadTitle').textContent = 'Error';
                        document.getElementById('uploadMessage').textContent = 'Error: ' + error;
                        document.getElementById('uploadSpinner').style.display = 'none';
                        document.getElementById('uploadCloseBtn').style.display = 'block';
                        document.getElementById('uploadCancelBtn').style.display = 'none';
                        btn.innerHTML = '⤴ Upload';
                        btn.disabled = false;
                    });
                }, 'image/png');
            }
        }

        // Layout selection
        document.querySelectorAll('.strip-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.strip-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const isEight = this.textContent.includes('8');
                const previewContainer = document.querySelector('.preview-container');

                if (isEight) {
                    currentLayout = 8;
                    // Remove existing frame
                    const existingFrame = document.querySelector('.frame-wrapper');
                    if (existingFrame) existingFrame.remove();
                    const existingContainer = previewContainer.querySelector('div[style*="display: flex"]');
                    if (existingContainer) existingContainer.remove();

                    // Create container for two frames
                    const frameContainer = document.createElement('div');
                    frameContainer.style.display = 'flex';
                    frameContainer.style.gap = '0px';
                    frameContainer.style.justifyContent = 'center';

                    // Create first frame
                    const frame1 = document.createElement('div');
                    frame1.className = 'frame-wrapper';
                    frame1.innerHTML = '<div class="photo-strip four" id="photoStrip1"><div class="photo-slot">+</div><div class="photo-slot">+</div><div class="photo-slot">+</div><div class="photo-slot">+</div></div>';

                    // Create second frame
                    const frame2 = document.createElement('div');
                    frame2.className = 'frame-wrapper';
                    frame2.innerHTML = '<div class="photo-strip four" id="photoStrip2"><div class="photo-slot">+</div><div class="photo-slot">+</div><div class="photo-slot">+</div><div class="photo-slot">+</div></div>';

                    frameContainer.appendChild(frame1);
                    frameContainer.appendChild(frame2);
                    previewContainer.appendChild(frameContainer);
                    selectedPhotos = new Array(8).fill(null);
                } else {
                    currentLayout = 4;
                    // Remove existing two frames
                    const existingContainer = previewContainer.querySelector('div[style*="display: flex"]');
                    if (existingContainer) existingContainer.remove();

                    // Create single frame
                    const frameWrapper = document.createElement('div');
                    frameWrapper.className = 'frame-wrapper';
                    frameWrapper.innerHTML = '<div class="photo-strip four" id="photoStrip"><div class="photo-slot">+</div><div class="photo-slot">+</div><div class="photo-slot">+</div><div class="photo-slot">+</div></div>';
                    previewContainer.appendChild(frameWrapper);
                    selectedPhotos = selectedPhotos.slice(0, 4);
                }
                displayPhotos();
                updateHashtagsDisplay();
            });
        });

        // Slot click
        document.addEventListener('click', function(e) {
            const slot = e.target.closest('.photo-slot');
            if (slot) {
                const strips = document.querySelectorAll('.photo-strip');
                let allSlots = [];
                strips.forEach(strip => {
                    allSlots = allSlots.concat(Array.from(strip.querySelectorAll('.photo-slot')));
                });
                selectedSlotIndex = allSlots.indexOf(slot);
                if (selectedSlotIndex >= 0 && selectedSlotIndex < currentLayout) {
                    openPhotoSelectModal();
                }
            }
        });

        // Frame selection (placeholder)
        document.querySelectorAll('.frame-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.frame-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                currentFrame = this.querySelector('.frame-label').textContent;
                
                // Apply the frame
                const frameWrappers = document.querySelectorAll('.frame-wrapper');
                frameWrappers.forEach(frameWrapper => {
                    if (currentFrame === 'No Frame') {
                        frameWrapper.style.background = '#f5f5f5';
                    } else if (currentFrame === 'Frame 1') {
                        frameWrapper.style.background = 'url(frames/frame0.png) no-repeat center center / cover';
                    } else if (currentFrame === 'Frame 2') {
                        frameWrapper.style.background = 'url(frames/frame2.png) no-repeat center center / cover';
                    } else if (currentFrame === 'Frame 3') {
                        frameWrapper.style.background = 'url(frames/frame3.png) no-repeat center center / cover';
                    } else if (currentFrame === 'Frame 14') {
                        frameWrapper.style.background = 'url(frames/frame4.png) no-repeat center center / cover';
                    } else {
                        frameWrapper.style.background = '#f5f5f5';
                    }
                });
            });
        });

        // Tab switching
        document.querySelectorAll('.tabs-container .tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tabs-container .tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        function addHashtag(tag) {
            if (!selectedHashtags.some(h => h.text === tag)) {
                selectedHashtags.push({
                    text: tag,
                    x: 10,
                    y: 0,
                    scale: 0.8
                });
                updateHashtagsDisplay();
            }
        }

        function addCustomHashtag() {
            const input = document.getElementById('customHashtagInput');
            let tag = input.value.trim();
            if (tag) {
                if (!tag.startsWith('#')) tag = '#' + tag;
                if (!selectedHashtags.some(h => h.text === tag)) {
                    selectedHashtags.push({
                        text: tag,
                        x: 10,
                        y: 0,
                        scale: 0.8
                    });
                    updateHashtagsDisplay();
                }
                input.value = '';
            }
        }

        function updateHashtagsDisplay() {
            const container = document.querySelector('.frame-wrapper');
            // Remove existing hashtag elements
            container.querySelectorAll('.hashtag-item').forEach(el => el.remove());
            
            selectedHashtags.forEach((hashtag, index) => {
                const hashtagDiv = document.createElement('div');
                hashtagDiv.className = 'hashtag-item';
                hashtagDiv.textContent = hashtag.text;
                hashtagDiv.style.position = 'absolute';
                hashtagDiv.style.left = hashtag.x + 'px';
                hashtagDiv.style.top = hashtag.y + 'px';
                hashtagDiv.style.transform = `scale(${hashtag.scale})`;
                hashtagDiv.style.cursor = 'move';
                hashtagDiv.style.fontSize = '9px';
                hashtagDiv.style.color = '#666';
                hashtagDiv.style.userSelect = 'none';
                hashtagDiv.style.zIndex = '10';
                hashtagDiv.dataset.index = index;
                
                const deleteBtn = document.createElement('span');
                deleteBtn.textContent = ' ✕';
                deleteBtn.style.cursor = 'pointer';
                deleteBtn.style.marginLeft = '5px';
                deleteBtn.onclick = (e) => {
                    e.stopPropagation();
                    removeHashtag(index);
                };
                hashtagDiv.appendChild(deleteBtn);
                
                container.appendChild(hashtagDiv);
            });
            
            const selectedDiv = document.getElementById('selectedHashtags');
            selectedDiv.innerHTML = '';
            selectedHashtags.forEach((hashtag, index) => {
                const tagSpan = document.createElement('span');
                tagSpan.textContent = hashtag.text;
                tagSpan.style.marginRight = '10px';
                tagSpan.style.cursor = 'pointer';
                tagSpan.onclick = () => removeHashtag(index);
                selectedDiv.appendChild(tagSpan);
            });
            localStorage.setItem('selectedHashtags', JSON.stringify(selectedHashtags));
        }

        function clearHashtags() {
            selectedHashtags = [];
            updateHashtagsDisplay();
        }

        function removeHashtag(index) {
            selectedHashtags.splice(index, 1);
            updateHashtagsDisplay();
        }

        // Initialize drag for hashtags
        document.addEventListener('mousedown', startDrag);
        document.addEventListener('touchstart', startDrag);
        document.addEventListener('mousemove', drag);
        document.addEventListener('touchmove', drag);
        document.addEventListener('mouseup', stopDrag);
        document.addEventListener('touchend', stopDrag);

        function startDrag(e) {
            const target = e.target.closest('.hashtag-item');
            if (!target) return;
            
            draggedHashtag = parseInt(target.dataset.index);
            if (e.touches && e.touches.length === 2) {
                // Start pinch
                initialDistance = getDistance(e.touches[0], e.touches[1]);
                isDragging = false; // Don't drag during pinch
            } else {
                isDragging = true;
                const rect = target.getBoundingClientRect();
                const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                const clientY = e.clientY || (e.touches && e.touches[0].clientY);
                dragOffsetX = clientX - rect.left;
                dragOffsetY = clientY - rect.top;
                target.style.cursor = 'grabbing';
            }
            e.preventDefault(); // Prevent scrolling on touch
        }

        function drag(e) {
            if (draggedHashtag === null) return;
            const hashtag = selectedHashtags[draggedHashtag];
            const target = document.querySelector(`.hashtag-item[data-index="${draggedHashtag}"]`);
            
            if (e.touches && e.touches.length === 2) {
                // Handle pinch
                const currentDistance = getDistance(e.touches[0], e.touches[1]);
                hashtag.scale = Math.max(0.5, Math.min(2, hashtag.scale * (currentDistance / initialDistance)));
                initialDistance = currentDistance;
                target.style.transform = `scale(${hashtag.scale})`;
                e.preventDefault();
            } else if (isDragging) {
                const container = document.querySelector('.frame-wrapper');
                const containerRect = container.getBoundingClientRect();
                const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                const clientY = e.clientY || (e.touches && e.touches[0].clientY);
                let newLeft = clientX - containerRect.left - dragOffsetX;
                let newTop = clientY - containerRect.top - dragOffsetY;
                // Constrain to container
                const maxLeft = containerRect.width - target.offsetWidth * hashtag.scale;
                const maxTop = containerRect.height - target.offsetHeight * hashtag.scale;
                newLeft = Math.max(0, Math.min(newLeft, maxLeft));
                newTop = Math.max(0, Math.min(newTop, maxTop));
                hashtag.x = newLeft;
                hashtag.y = newTop;
                target.style.left = newLeft + 'px';
                target.style.top = newTop + 'px';
                e.preventDefault(); // Prevent scrolling on touch
            }
        }

        function stopDrag() {
            isDragging = false;
            draggedHashtag = null;
            document.querySelectorAll('.hashtag-item').forEach(el => el.style.cursor = 'move');
        }

        function getDistance(touch1, touch2) {
            const dx = touch1.clientX - touch2.clientX;
            const dy = touch1.clientY - touch2.clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }

        document.getElementById('customHashtagInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                addCustomHashtag();
            }
        });

        // Load photos on page load
        loadPhotos();
    </script>
</body>
</html>