<?php
$uploadDir = __DIR__ . "/uploads/";

$files = [];
if (is_dir($uploadDir)) {
    foreach (scandir($uploadDir) as $item) {
        if ($item !== "." && $item !== "..") {
            $path = $uploadDir . $item;
            if (is_file($path)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($path),
                    'modified' => filemtime($path),
                ];
            }
        }
    }
}

function formatFileSize($bytes) {
    if ($bytes >= 1048576) return round($bytes/1048576,2)." MB";
    if ($bytes >= 1024) return round($bytes/1024,2)." KB";
    return $bytes." B";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>MyCloud</title>

<style>
/* 🌈 BACKGROUND ANIMATION */
body{
    margin:0;
    font-family:system-ui;
    background: linear-gradient(270deg,#0f172a,#1e3a8a,#2563eb);
    background-size:600% 600%;
    animation:bgMove 12s ease infinite;
    color:#1e293b;
}

@keyframes bgMove{
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}

/* GLASS EFFECT */
.layout{
    display:flex;
    min-height:100vh;
    backdrop-filter: blur(10px);
}

/* SIDEBAR */
.sidebar{
    width:220px;
    background:rgba(255,255,255,0.85);
    padding:20px;
}

.logo{
    font-weight:700;
    color:#2563eb;
    margin-bottom:20px;
}

.menu div{
    padding:10px;
    border-radius:8px;
    cursor:pointer;
}
.menu div:hover{
    background:#e0e7ff;
}

/* MAIN */
.main{
    flex:1;
    padding:24px;
    background:rgba(255,255,255,0.9);
}

/* HEADER */
h1{margin-bottom:10px}

/* UPLOAD */
.upload{
    display:flex;
    gap:10px;
    margin-bottom:20px;
}

input[type=file]{
    padding:8px;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:10px 16px;
    border-radius:8px;
    cursor:pointer;
    transition:.2s;
}

button:hover{
    transform:scale(1.05);
}

/* TABLE */
.table{
    background:white;
    border-radius:12px;
    overflow:hidden;
}

.row{
    display:grid;
    grid-template-columns:40px 1fr 120px 180px 120px;
    padding:12px;
    border-bottom:1px solid #eee;
}

.row.header{
    background:#eef2ff;
    font-weight:600;
}

.row:hover{
    background:#f9fafb;
}

/* ACTION */
.actions a{
    padding:5px 8px;
    border-radius:6px;
    margin-right:5px;
    text-decoration:none;
}

.download{background:#e0f2fe;color:#0369a1;}
.delete{background:#fee2e2;color:#b91c1c;}

/* ALERT */
.alert{
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
}
.success{background:#dcfce7;}
.error{background:#fee2e2;}

.empty{
    text-align:center;
    padding:20px;
}
</style>
</head>

<body>

<div class="layout">

<div class="sidebar">
    <div class="logo">☁️ MyCloud</div>
    <div class="menu">
        <div>📁 My Files</div>
        <div>⭐ Favorites</div>
        <div>🕘 Recent</div>
    </div>
</div>

<div class="main">

<h1>My Files</h1>

<?php if ($_GET['status'] ?? '' === 'upload_success'): ?>
<div class="alert success">Upload berhasil</div>
<?php endif; ?>

<form class="upload" action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" required>
    <button>Upload</button>
</form>

<div class="table">

<div class="row header">
    <div>#</div>
    <div>Nama</div>
    <div>Ukuran</div>
    <div>Modified</div>
    <div>Aksi</div>
</div>

<?php if(count($files)>0): ?>
    <?php foreach($files as $i=>$f): ?>
    <div class="row">
        <div><?= $i+1 ?></div>
        <div><?= htmlspecialchars($f['name']) ?></div>
        <div><?= formatFileSize($f['size']) ?></div>
        <div><?= date("d M Y H:i",$f['modified']) ?></div>
        <div class="actions">
            <a class="download" href="download.php?file=<?= urlencode($f['name']) ?>">⬇️</a>
            <a class="delete" href="delete.php?file=<?= urlencode($f['name']) ?>">🗑️</a>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="empty">Belum ada file</div>
<?php endif; ?>

</div>

</div>
</div>

</body>
</html>