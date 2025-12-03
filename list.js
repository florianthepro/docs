async function loadFiles() {
  const repo = "USERNAME/REPOSITORY"; // 👉 anpassen
  const url = `https://api.github.com/repos/${repo}/contents/`;

  try {
    const response = await fetch(url);
    const files = await response.json();

    let html = "<ul>";
    files.forEach(file => {
      if (file.name === "index.html" || file.name === "style.css" || file.name === "list.js") return;

      const sizeKB = (file.size / 1024).toFixed(2) + " KB";
      html += `<li>
        <a href="${file.download_url}" target="_blank">${file.name}</a>
        <span class="meta"> | Größe: ${sizeKB}</span>
      </li>`;
    });
    html += "</ul>";

    document.getElementById("file-list").innerHTML = html;
  } catch (err) {
    document.getElementById("file-list").innerHTML = "Fehler beim Laden der Dateien.";
    console.error(err);
  }
}

loadFiles();
