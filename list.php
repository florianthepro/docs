<?php
function formatSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' Bytes';
    }
}

function listDirectory($dir) {
    $exclude = ['index.html', 'list.php', 'style.css']; // Unerwünschte Dateien
    $files = scandir($dir);

    echo "<ul>";
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || in_array($file, $exclude)) {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            echo "<li class='folder'>📁 " . htmlspecialchars($file) . "</li>";
            listDirectory($path); // Rekursiv
        } else {
            $size = formatSize(filesize($path));
            $date = date("d.m.Y H:i", filemtime($path));
            echo "<li class='file'>
                    <a href='" . htmlspecialchars($path) . "' target='_blank'>" . htmlspecialchars($file) . "</a>
                    <span class='meta'> | Größe: $size | Geändert: $date</span>
                  </li>";
        }
    }
    echo "</ul>";
}

// Start im aktuellen Verzeichnis
listDirectory(".");
?>
