#!/bin/bash
set -e

# --- temporäre Pakete installieren ---
sudo apt update
sudo apt install -y build-essential git

# --- Emscripten SDK holen und aktivieren ---
cd ~
if [ -d emsdk ]; then rm -rf emsdk; fi
git clone https://github.com/emscripten-core/emsdk.git
cd emsdk
./emsdk install latest
./emsdk activate latest
source ./emsdk_env.sh

# --- John the Ripper holen ---
cd ~
if [ -d john ]; then rm -rf john; fi
git clone https://github.com/openwall/john.git
cd john/src

# --- Konfigurieren und bauen (ohne OpenSSL) ---
emconfigure ./configure --without-openssl
emmake make -j

echo "Build abgeschlossen. Artefakte liegen unter ~/john/run"
