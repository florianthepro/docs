<?php
$baseDir = __DIR__;
$self    = basename($_SERVER['SCRIPT_NAME']); // aktueller Scriptname
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); // z.B. /public-utils

// Funktion zum sicheren Auflisten
function listDirectory($dir, $self, $baseUrl = '', $scriptDir = '') {
    $items = scandir($dir);

    echo "<table id='fileTable'>";
    echo "<tr><th>Name</th><th>Typ</th><th>Größe</th></tr>";

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        if ($item === $self) continue; // sich selbst ausschließen
        if (str_starts_with($item, '.')) continue; // Verzeichnisse/Dateien mit "." nicht anzeigen

        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $url  = $baseUrl . '/' . rawurlencode($item);

        if (is_dir($path)) {
            echo "<tr>
                    <td>📁 <a href='?path=" . urlencode($url) . "'>$item</a></td>
                    <td>Ordner</td>
                    <td>-</td>
                  </tr>";
        } else {
            $size = filesize($path);
            $size = number_format($size / 1024, 2) . " KB";
            // WICHTIG: relativer Link zum Skriptverzeichnis
            $fileUrl = $scriptDir . '/' . ltrim($url, '/');
            echo "<tr>
                    <td>📄 <a href='" . htmlspecialchars($fileUrl) . "' target='_blank'>$item</a></td>
                    <td>Datei</td>
                    <td>$size</td>
                  </tr>";
        }
    }
    echo "</table>";
}

// Aktuellen Pfad bestimmen
$path = isset($_GET['path']) ? rawurldecode($_GET['path']) : '';
$currentDir = realpath($baseDir . DIRECTORY_SEPARATOR . $path);

// Sicherheitscheck
if ($currentDir === false || strpos($currentDir, $baseDir) !== 0) {
    die("Ungültiger Pfad.");
}

// Zugriff auf Dateien, die mit "." beginnen, blockieren
$basename = basename($currentDir);
if (str_starts_with($basename, '.')) {
    die("Zugriff verweigert.");
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($path); ?></title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: var(--bg);
        color: var(--fg);
        margin: 40px;
        transition: background 0.3s, color 0.3s;
    }
    :root {
        --bg: #f4f6f9;
        --fg: #333;
        --link: #0078d7;
        --table-bg: white;
    }
    body.dark {
        --bg: #1e1e1e;
        --fg: #ddd;
        --link: #4aa3ff;
        --table-bg: #2a2a2a;
    }
    h2 { margin-bottom: 10px; }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        background: var(--table-bg);
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 10px 15px;
        border-bottom: 1px solid #5553;
    }
    th {
        background: var(--link);
        color: white;
        text-align: left;
    }
    a {
        color: var(--link);
        text-decoration: none;
    }
    a:hover { text-decoration: underline; }
    .back, .search {
        margin: 10px 10px 20px 0;
        display: inline-block;
    }
    input[type="text"] {
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>
</head>
<body class="dark">
    <h2>📂 <?php echo htmlspecialchars($currentDir); ?></h2>

    <?php if ($path !== ''): ?>
        <a class="back" href="?path=<?php echo urlencode(dirname($path) === '.' ? '' : dirname($path)); ?>">⬅️ Zurück</a>
    <?php endif; ?>

    <input class="search" type="text" id="searchBox" placeholder="🔍 Dateien filtern...">

    <?php listDirectory($currentDir, $self, $path, $scriptDir); ?>

<script>
// Darkmode automatisch aktivieren
document.addEventListener("DOMContentLoaded", () => {
    document.body.classList.add("dark");
    localStorage.setItem("theme", "dark");
});

// Live-Suche
document.getElementById("searchBox").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#fileTable tr");
    rows.forEach((row, i) => {
        if (i === 0) return; // Kopfzeile überspringen
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>
</body>
</html>
