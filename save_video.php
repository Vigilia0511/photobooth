<?php
// save_video.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Only POST allowed'; exit; }
if (!isset($_FILES['video'])) { http_response_code(400); echo 'No video uploaded'; exit; }
$uploaddir = __DIR__ . '/uploads/'; if (!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
$file = $_FILES['video'];
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'video_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . ($ext?:'webm');
$dest = $uploaddir . $filename;
if (move_uploaded_file($file['tmp_name'], $dest)) {
echo 'Saved as uploads/' . $filename;
} else {
http_response_code(500); echo 'Failed to save file';
}