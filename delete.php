<?php
declare(strict_types=1);

$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

if (!isset($_GET['file']) || $_GET['file'] === '') {
    header('Location: index.php?status=error');
    exit;
}

// Amankan nama file
$fileName = basename((string)$_GET['file']);
$filePath = $uploadDir . $fileName;

if (!is_file($filePath) || !file_exists($filePath)) {
    header('Location: index.php?status=error');
    exit;
}

// Hapus file
if (unlink($filePath)) {
    header('Location: index.php?status=delete_success');
    exit;
}

header('Location: index.php?status=error');
exit;