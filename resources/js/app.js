import './bootstrap';

// Sidebar toggle and zoom functionality
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('sidebar-toggle');
  if (toggle) {
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      document.documentElement.classList.toggle('layout-menu-collapsed');
    });
  }

  const zoomSlider = document.getElementById('zoom-slider');
  const zoomOut = document.getElementById('zoom-out');
  const zoomIn = document.getElementById('zoom-in');
  const zoomPercentage = document.getElementById('zoom-percentage');
  const tableContainers = document.querySelectorAll('.table-responsive');
  function applyZoom(value) {
    const zoom = value / 100;
    tableContainers.forEach((container) => {
      container.style.transformOrigin = 'top left';
      container.style.transform = `scale(${zoom})`;
      container.style.width = `${100 / zoom}%`;
    });
    if (zoomPercentage) {
      zoomPercentage.textContent = `${Math.round(value)}%`;
    }
  }
  if (zoomSlider && tableContainers.length) {
    applyZoom(zoomSlider.value);
    zoomSlider.addEventListener('input', (e) => {
      applyZoom(e.target.value);
    });
    if (zoomOut) {
      zoomOut.addEventListener('click', () => {
        const value = Math.max(parseInt(zoomSlider.min, 10), parseInt(zoomSlider.value, 10) - 10);
        zoomSlider.value = value;
        applyZoom(value);
      });
    }
    if (zoomIn) {
      zoomIn.addEventListener('click', () => {
        const value = Math.min(parseInt(zoomSlider.max, 10), parseInt(zoomSlider.value, 10) + 10);
        zoomSlider.value = value;
        applyZoom(value);
      });
    }
  }
});
