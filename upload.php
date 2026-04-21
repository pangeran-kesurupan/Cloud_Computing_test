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

// Ambil nama file asli dan bersihkan karakter berbahaya
$originalName = (string)$file['name'];
$baseName = basename($originalName);
$sanitizedFileName = preg_replace('/[^A-Za-z0-9._-]/', '_', $baseName);

if ($sanitizedFileName === null || $sanitizedFileName === '') {
    header('Location: index.php?status=error');
    exit;
}

// Cegah file tertimpa: tambahkan timestamp jika nama sudah ada
$targetPath = $uploadDir . $sanitizedFileName;
if (file_exists($targetPath)) {
    $fileInfo = pathinfo($sanitizedFileName);
    $nameOnly = $fileInfo['filename'] ?? 'file';
    $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
    $sanitizedFileName = $nameOnly . '_' . date('Ymd_His') . $extension;
    $targetPath = $uploadDir . $sanitizedFileName;
}

// Pindahkan file ke folder uploads
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    header('Location: index.php?status=upload_success');
    exit;
}

header('Location: index.php?status=error');
exit;