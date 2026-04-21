<?php
declare(strict_types=1);

$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

// Pastikan folder uploads ada
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
        header('Location: index.php?status=error');
        exit;
    }
}

// Validasi request
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['fileToUpload'])) {
    header('Location: index.php?status=error');
    exit;
}

$file = $_FILES['fileToUpload'];

// Cek error upload
if ($file['error'] !== UPLOAD_ERR_OK) {
    header('Location: index.php?status=error');
    exit;
}

// Batas ukuran file: 10 MB
$maxFileSize = 10 * 1024 * 1024;
if ((int)$file['size'] > $maxFileSize) {
    header('Location: index.php?status=error');
    exit;
}

// Validasi MIME type (lebih aman dari sekadar extension)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

// Daftar tipe file yang diizinkan
$allowedMimeTypes = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'application/pdf',
    'text/plain'
];

if (!in_array($mimeType, $allowedMimeTypes, true)) {
    header('Location: index.php?status=error');
    exit;
}

// Sanitasi nama file
$originalName = (string)$file['name'];
$baseName = basename($originalName);
$safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $baseName);

if (!$safeName) {
    header('Location: index.php?status=error');
    exit;
}

// Ambil extension
$ext = strtolower(pathinfo($safeName, PATHINFO_EXTENSION));

// Generate nama unik (anti tabrakan + lebih aman)
$uniqueName = bin2hex(random_bytes(8)) . ($ext ? '.' . $ext : '');

$targetPath = $uploadDir . $uniqueName;

// Pindahkan file
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    header('Location: index.php?status=upload_success');
    exit;
}

// Jika gagal
header('Location: index.php?status=error');
exit;