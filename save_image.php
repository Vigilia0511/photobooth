<?php
// save_image.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Only POST allowed'; exit; }
if (!isset($_FILES['photo'])) { http_response_code(400); echo 'No photo uploaded'; exit; }
$uploaddir = __DIR__ . '/uploads/'; if (!is_dir($uploaddir)) mkdir($uploaddir, 0777, true);
$file = $_FILES['photo'];
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'photo_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . ($ext?:'png');
$dest = $uploaddir . $filename;
if (move_uploaded_file($file['tmp_name'], $dest)) {
echo 'Saved as uploads/' . $filename;
} else {
http_response_code(500); echo 'Failed to save file';
}