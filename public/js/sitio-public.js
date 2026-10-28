const menuButton = document.querySelector('.menu-toggle');
const navigation = document.querySelector('#primary-nav');
if (menuButton && navigation) {
  menuButton.addEventListener('click', () => {
    const open = navigation.classList.toggle('open');
    menuButton.setAttribute('aria-expanded', String(open));
  });
  navigation.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
    navigation.classList.remove('open'); menuButton.setAttribute('aria-expanded', 'false');
  }));
}
const countBadge = document.querySelector('#cart-count');
function refreshBadge() {
  if (!countBadge) return;
  try {
    const items = JSON.parse(localStorage.getItem('bodeguita-cart-v1') || '[]');
    countBadge.textContent = items.reduce((total, item) => total + (Number(item.qty) || 0), 0);
  } catch { countBadge.textContent = '0'; }
}
refreshBadge(); window.addEventListener('storage', refreshBadge);
