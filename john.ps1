# Pfad zur Eingabedatei (john.wasm)
$InputFile  = "C:\Pfad\zu\john.wasm"

# Pfad zum Downloads-Ordner des aktuellen Benutzers
$Downloads  = [Environment]::GetFolderPath("UserProfile") + "\Downloads"

# Ausgabedatei im Downloads-Ordner
$OutputFile = Join-Path $Downloads "john.wasm.b64"

Write-Host "Konvertiere $InputFile nach Base64..."

# Datei einlesen und nach Base64 konvertieren
$Base64 = [Convert]::ToBase64String([IO.File]::ReadAllBytes($InputFile))

# Ergebnis speichern
Set-Content -Path $OutputFile -Value $Base64

Write-Host "Fertig! Ergebnis gespeichert unter: $OutputFile"
