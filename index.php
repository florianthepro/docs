<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Dateiverzeichnis</title>
</head>
<body>
  <h1>Dateiverzeichnis</h1>
  <ul>
    <?php
      foreach (scandir(".") as $file) {
        if ($file != "." && $file != ".." && $file != "index.html") {
          echo "<li><a href='$file'>$file</a></li>";
        }
      }
    ?>
  </ul>
</body>
</html>
