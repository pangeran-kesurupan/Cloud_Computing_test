<?php
$uploadDir = "uploads/";

$files = [];
if (is_dir($uploadDir)) {
    $items = scandir($uploadDir);
    foreach ($items as $item) {
        if ($item !== "." && $item !== "..") {
            $filePath = $uploadDir . $item;
            if (is_file($filePath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($filePath),
                    'modified' => filemtime($filePath),
                ];
            }
        }
    }
}

function formatFileSize(int $bytes): string
{
    if ($bytes >= 1024 * 1024) {
        return round($bytes / (1024 * 1024), 2) . " MB";
    }
    if ($bytes >= 1024) {
        return round($bytes / 1024, 2) . " KB";
    }
    return $bytes . " B";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Cloud Storage</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            color: #1e293b;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #ffffffcc;
            backdrop-filter: blur(10px);
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        p {
            color: #64748b;
        }

        .upload-box {
            margin: 24px 0;
            padding: 20px;
            background: linear-gradient(135deg, #e0f2fe, #eef2ff);
            border-radius: 12px;
        }

        input[type="file"] {
            padding: 6px;
        }

        button {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(59,130,246,0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            border-radius: 12px;
            overflow: hidden;
        }

        table th {
            background: #1e293b;
            color: white;
            padding: 12px;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        tbody tr:hover {
            background: #f1f5f9;
        }

        .actions a {
            margin-right: 10px;
            text-decoration: none;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 6px;
            transition: 0.2s;
        }

        .actions a:not(.delete) {
            background: #e0f2fe;
            color: #0284c7;
        }

        .actions a:not(.delete):hover {
            background: #bae6fd;
        }

        .actions a.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .actions a.delete:hover {
            background: #fecaca;
        }

        .empty {
            padding: 20px;
            text-align: center;
            background: #fef3c7;
            border-radius: 10px;
        }

        .note {
            margin-top: 20px;
            font-size: 14px;
            color: #475569;
        }

        .success, .error {
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>
<div class="container">
    <h1>☁️ Simple Cloud Storage</h1>
    <p>Simulasi sederhana cloud storage menggunakan PHP & folder server.</p>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'upload_success'): ?>
        <div class="success">✅ File berhasil diupload</div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] === 'delete_success'): ?>
        <div class="success">🗑️ File berhasil dihapus</div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
        <div class="error">❌ Terjadi kesalahan</div>
    <?php endif; ?>

    <div class="upload-box">
        <h2>📤 Upload File</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" required>
            <button type="submit">Upload</button>
        </form>
    </div>

    <h2>📁 Daftar File</h2>

    <?php if (count($files) > 0): ?>
        <table>
            <thead>
            <tr>
                <th>No</th>
                <th>Nama File</th>
                <th>Ukuran</th>
                <th>Terakhir Diubah</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($files as $index => $file): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= htmlspecialchars($file['name']); ?></td>
                    <td><?= formatFileSize($file['size']); ?></td>
                    <td><?= date("d M Y, H:i", $file['modified']); ?></td>
                    <td class="actions">
                        <a href="download.php?file=<?= urlencode($file['name']); ?>">⬇️ Download</a>
                        <a class="delete"
                           href="delete.php?file=<?= urlencode($file['name']); ?>"
                           onclick="return confirm('Yakin ingin menghapus file ini?');">
                            🗑️ Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty">
            📭 Belum ada file
        </div>
    <?php endif; ?>

    <div class="note">
        <strong>Catatan:</strong> Folder <code>uploads/</code> bertindak sebagai storage cloud.
    </div>
</div>
</body>
</html>