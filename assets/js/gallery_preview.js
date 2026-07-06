(function () {
  'use strict';

  async function loadGalleryPreview() {
    const container = document.getElementById('galleryPreview');
    if (!container) return;

    try {
      // gallery.php renders full HTML; request with header to only parse images.
      // Fallback: use a lightweight JSON endpoint if it exists.
      let res;
      try {
        res = await fetch('gallery_api.php', { headers: { 'Accept': 'application/json' } });
        if (res && res.ok) {
          const data = await res.json();
          if (Array.isArray(data.images)) {
            renderImages(container, data.images);
            return;
          }
        }
      } catch (e) {
        // ignore and fallback to DOM parsing
      }

      res = await fetch('gallery.php');
      if (!res.ok) throw new Error('Failed to fetch gallery.php');
      const html = await res.text();

      // Parse <img src="..." ...> from returned HTML
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const imgs = Array.from(doc.querySelectorAll('img'))
        .map(img => ({
          src: img.getAttribute('src'),
          alt: img.getAttribute('alt') || '',
        }))
        .filter(x => x.src && x.src.includes('uploads/gallery'));

      const images = imgs.slice(0, 8);
      renderImages(container, images);
    } catch (err) {
      container.innerHTML = '<div class="col-12 text-center text-muted">Gallery preview unavailable.</div>';
      console.error(err);
    }
  }

  function renderImages(container, images) {
    if (!images || images.length === 0) {
      container.innerHTML = '<div class="col-12 text-center text-muted">No gallery images yet.</div>';
      return;
    }

    const fragment = document.createDocumentFragment();
    const row = document.createElement('div');
    row.className = 'row g-3';

    images.forEach((img) => {
      const col = document.createElement('div');
      col.className = 'col-6 col-md-3';

      col.innerHTML = `
        <div class="gallery-thumb">
          <img src="${escapeHtml(img.src)}" alt="${escapeHtml(img.alt)}" class="img-fluid rounded-3 shadow-sm" loading="lazy" />
        </div>
      `;

      row.appendChild(col);
    });

    // Replace container content
    container.innerHTML = '';
    fragment.appendChild(row);
    container.appendChild(row);
  }

  function escapeHtml(str) {
    return String(str)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '<')
      .replaceAll('>', '>')
      .replaceAll('"', '"')
      .replaceAll("'", '&#039;');
  }

  document.addEventListener('DOMContentLoaded', loadGalleryPreview);
})();

