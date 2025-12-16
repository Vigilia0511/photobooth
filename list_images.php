<?php
// list_images.php
header('Content-Type: application/json');
$dir = __DIR__ . '/uploads/';
if (!is_dir($dir)) {
    echo json_encode([]);
    exit;
}
$files = array_diff(scandir($dir), array('.', '..'));
$images = array_filter($files, function($f) {
    return preg_match('/\.(png|jpg|jpeg|gif)$/i', $f);
});
echo json_encode(array_values($images));
?>