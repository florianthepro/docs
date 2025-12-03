async function fetchDirectory(repo, path = "") {
  const url = `https://api.github.com/repos/${repo}/contents/${path}`;
  const response = await fetch(url);
  if (!response.ok) throw new Error("Fehler beim Laden: " + url);
  return await response.json();
}

function formatSize(bytes) {
  if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + " GB";
  if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + " MB";
  if (bytes >= 1024) return (bytes / 1024).toFixed(2) + " KB";
  return bytes + " Bytes";
}

async function renderDirectory(repo, path = "") {
  let files;
  try {
    files = await fetchDirectory(repo, path);
  } catch (err) {
    return `<li>❌ Fehler: ${err.message}</li>`;
  }

  let html = "<ul>";
  for (const file of files) {
    if (["index.html", "style.css", "list.js"].includes(file.name)) continue;

    if (file.type === "file") {
      const size = formatSize(file.size);
      html += `<li class="file">
        <a href="${file.download_url}" target="_blank">${file.name}</a>
        <span class="meta"> | Größe: ${size}</span>
      </li>`;
    } else if (file.type === "dir") {
      html += `<li class="folder">📁 ${file.name}`;
      html += await renderDirectory(repo, file.path); // Rekursiv
      html += `</li>`;
    }
  }
  html += "</ul>";
  return html;
}

async function loadFiles() {
  const repo = "USERNAME/REPOSITORY"; // 👉 anpassen
  const html = await renderDirectory(repo);
  document.getElementById("file-list").innerHTML = html;
}

loadFiles();
