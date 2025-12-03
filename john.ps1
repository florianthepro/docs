# Eingabedateien
$HtmlInput   = "C:\Pfad\zu\deiner\john.html"   # deine vorhandene HTML
$WasmInput   = "C:\Pfad\zu\john.wasm"         # die kompilierte john.wasm

# Ziel: Downloads-Ordner des aktuellen Benutzers
$Downloads   = [Environment]::GetFolderPath("UserProfile") + "\Downloads"
$HtmlOutput  = Join-Path $Downloads "john.html"

Write-Host "Lese $WasmInput und konvertiere nach Base64..."

# john.wasm einlesen und nach Base64 konvertieren
$Base64 = [Convert]::ToBase64String([IO.File]::ReadAllBytes($WasmInput))

Write-Host "Ersetze Platzhalter BASE64_WASM_DATA in $HtmlInput..."

# HTML einlesen
$HtmlContent = Get-Content -Raw -Path $HtmlInput

# Platzhalter ersetzen
$HtmlContent = $HtmlContent -replace "BASE64_WASM_DATA", $Base64

# Ergebnis speichern
Set-Content -Path $HtmlOutput -Value $HtmlContent

Write-Host "Fertig! Neue Datei gespeichert unter: $HtmlOutput"
