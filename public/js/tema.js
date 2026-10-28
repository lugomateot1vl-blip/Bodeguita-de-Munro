(() => {
  const themeMeta = document.querySelector('meta[name="theme-color"]');

  function applyTheme(theme, save = false) {
    const dark = theme === 'dark';
    document.documentElement.dataset.theme = dark ? 'dark' : 'light';

    const toggle = document.querySelector('#theme-toggle');
    if (toggle) {
      toggle.setAttribute('aria-pressed', String(dark));
      toggle.setAttribute('aria-label', dark ? 'Activar modo claro' : 'Activar modo oscuro');
      toggle.title = dark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro';
      const icon = toggle.querySelector('.theme-toggle-icon');
      if (icon) icon.textContent = dark ? '☀' : '☾';
    }

    if (themeMeta) themeMeta.content = dark ? '#211d1a' : '#f8e8a7';
    if (save) {
      try { localStorage.setItem('bodeguita-theme', dark ? 'dark' : 'light'); } catch {}
    }
  }

  let savedTheme = 'light';
  try { savedTheme = localStorage.getItem('bodeguita-theme') === 'dark' ? 'dark' : 'light'; } catch {}
  applyTheme(savedTheme);

  // La delegación conserva el control aunque otro script vuelva a dibujar el encabezado.
  document.addEventListener('click', event => {
    if (!event.target.closest('#theme-toggle')) return;
    applyTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark', true);
  });
})();
