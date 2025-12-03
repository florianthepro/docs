# >>> Pfad zu deinem WSL-Ordner hier anpassen <<<
$WslPath = "\\wsl.localhost\Ubuntu\home\user\john\run"

# Eingabedatei (john.wasm im WSL-Ordner)
$WasmFile = Join-Path $WslPath "john.wasm"

# Ziel: Downloads-Ordner
$Downloads = [Environment]::GetFolderPath("UserProfile") + "\Downloads"
$OutputFile = Join-Path $Downloads "john_wasm_code.txt"

Write-Host "Lese $WasmFile und konvertiere nach Base64..."

# Datei einlesen und nach Base64 konvertieren
$Base64 = [Convert]::ToBase64String([IO.File]::ReadAllBytes($WasmFile))

# Fertigen Code erzeugen
$Code = @"
<script>
const wasmBase64 = '$Base64';

// Hilfsfunktion: Base64 -> Uint8Array
function base64ToUint8Array(base64) {
    const binaryString = atob(base64);
    const len = binaryString.length;
    const bytes = new Uint8Array(len);
    for (let i = 0; i < len; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }
    return bytes;
}

const wasmBytes = base64ToUint8Array(wasmBase64);

// Beispiel: WebAssembly laden
WebAssembly.instantiate(wasmBytes.buffer).then(obj => {
    console.log("WASM geladen:", obj);
});
</script>
"@

# Ergebnis speichern
Set-Content -Path $OutputFile -Value $Code -Encoding UTF8

Write-Host "Fertig! Code gespeichert unter: $OutputFile"
