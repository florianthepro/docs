# Zurück ins Home-Verzeichnis
cd ~

# Falls alte Reste existieren, löschen
rm -rf emsdk

# Emscripten SDK neu klonen
git clone https://github.com/emscripten-core/emsdk.git
cd emsdk

# SDK installieren und aktivieren
./emsdk install latest
./emsdk activate latest

# Umgebung aktivieren
source ./emsdk_env.sh

cd ~
rm -rf john
git clone https://github.com/openwall/john.git
cd john/src

emconfigure ./configure
emmake make -j

