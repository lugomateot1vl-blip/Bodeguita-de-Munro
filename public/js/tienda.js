const cartKey = 'bodeguita-cart-v1';
// Se toman solo los productos visibles en esta respuesta, con stock y precio actuales.
const productButtons = [...document.querySelectorAll('.add-to-cart')];
const products = productButtons.map(button => ({
  id: Number(button.dataset.id), name: button.dataset.name,
  price: Number(button.dataset.price), stock: Number(button.dataset.stock)
}));
const money = value => new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS', maximumFractionDigits: 0 }).format(value);
const escapeHtml = value => String(value).replace(/[&<>"']/g, char => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' }[char]));
// El carrito puede estar vacío o tener datos viejos; se valida antes de usarlo.
let stored = [];
try {
  const savedCart = JSON.parse(localStorage.getItem(cartKey) || '[]');
  if (Array.isArray(savedCart)) stored = savedCart;
} catch { stored = []; }
const productsById = new Map(products.map(product => [product.id, product]));
let cart = stored.flatMap(item => {
  const product = productsById.get(Number(item?.id));
  if (!product || product.stock < 1) return [];
  return [{ ...product, qty: Math.min(Math.max(1, Number(item.qty) || 1), product.stock) }];
});
const toast = document.querySelector('#toast');
let toastTimer;
function announce(message) {
  if (!toast) return;
  toast.textContent = message; toast.classList.add('show'); clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.remove('show'), 2400);
}
function renderCart() {
  const count = document.querySelector('#cart-count');
  const total = document.querySelector('#cart-total');
  const box = document.querySelector('#cart-items');
  if (count) count.textContent = cart.reduce((sum, item) => sum + item.qty, 0);
  if (total) total.textContent = money(cart.reduce((sum, item) => sum + item.price * item.qty, 0));
  if (box) box.innerHTML = cart.length ? cart.map(item => `<div class="cart-line"><div><strong>${escapeHtml(item.name)}</strong><br><small>${money(item.price)} · ${item.qty} unidad${item.qty === 1 ? '' : 'es'}</small></div><div class="quantity"><button data-change="-1" data-id="${item.id}" aria-label="Quitar una unidad">−</button><span>${item.qty}</span><button data-change="1" data-id="${item.id}" aria-label="Agregar una unidad">+</button></div><span class="cart-line-total">${money(item.price * item.qty)}</span></div>`).join('') : '<p class="cart-empty">Todavía no hay bebidas en tu selección.<br>Encontrá algo rico para compartir.</p>';
  try { localStorage.setItem(cartKey, JSON.stringify(cart)); } catch { /* El carrito sigue activo durante esta visita aunque el navegador no permita guardarlo. */ }
}
function openOverlay(selector) { const overlay = document.querySelector(selector); if (overlay) { overlay.hidden = false; document.body.style.overflow = 'hidden'; } }
function closeOverlay(selector) { const overlay = document.querySelector(selector); if (overlay) overlay.hidden = true; if (!document.querySelector('.overlay:not([hidden])')) document.body.style.overflow = ''; }
productButtons.forEach(button => button.addEventListener('click', () => {
  const item = cart.find(current => current.id === Number(button.dataset.id));
  if (item && item.qty >= Number(button.dataset.stock)) { announce('Llegaste al stock disponible de esta etiqueta.'); return; }
  if (item) item.qty++;
  else cart.push({ id:Number(button.dataset.id), name:button.dataset.name, price:Number(button.dataset.price), stock:Number(button.dataset.stock), qty:1 });
  renderCart(); announce('Sumamos una etiqueta a Mi selección ✳');
}));
const openCart = document.querySelector('#open-cart');
if (openCart) openCart.addEventListener('click', () => openOverlay('#cart-overlay'));
document.querySelector('.close-cart')?.addEventListener('click', () => closeOverlay('#cart-overlay'));
document.querySelector('.close-checkout')?.addEventListener('click', () => closeOverlay('#checkout-overlay'));
document.querySelectorAll('.overlay').forEach(overlay => overlay.addEventListener('click', event => { if (event.target === overlay) closeOverlay(`#${overlay.id}`); }));
document.addEventListener('keydown', event => { if (event.key === 'Escape') document.querySelectorAll('.overlay:not([hidden])').forEach(overlay => closeOverlay(`#${overlay.id}`)); });
document.querySelector('#cart-items')?.addEventListener('click', event => {
  const button = event.target.closest('[data-change]'); if (!button) return;
  const item = cart.find(current => current.id === Number(button.dataset.id)); if (!item) return;
  if (button.dataset.change === '1' && item.qty >= item.stock) { announce('Llegaste al stock disponible de esta etiqueta.'); return; }
  item.qty += Number(button.dataset.change); if (item.qty < 1) cart = cart.filter(current => current.id !== item.id); renderCart();
});
document.querySelector('#checkout')?.addEventListener('click', () => {
  if (!cart.length) { announce('Agregá al menos una bebida para continuar.'); return; }
  const checkoutButton = document.querySelector('#checkout');
  if (checkoutButton?.dataset.requiresLogin === 'true') {
    location.href = 'cuenta.php?destino=catalogo.php%23mi-seleccion';
    return;
  }
  document.querySelector('#order-items').value = JSON.stringify(cart.map(({ id, qty }) => ({ id, cantidad:qty })));
  closeOverlay('#cart-overlay'); openOverlay('#checkout-overlay');
});
document.querySelector('[name="modalidad"]')?.addEventListener('change', event => {
  const address = document.querySelector('.address-field'); if (!address) return;
  address.hidden = event.target.value !== 'Envío a domicilio'; address.querySelector('input').required = !address.hidden;
});
document.querySelector('#checkout-form')?.addEventListener('submit', event => {
  document.querySelector('#order-items').value = JSON.stringify(cart.map(({ id, qty }) => ({ id, cantidad:qty })));
  if (!cart.length) event.preventDefault();
});
const query = new URLSearchParams(location.search);
const search = document.querySelector('#search');
if (search && query.has('buscar')) search.value = query.get('buscar');
function filterProducts() {
  if (!search) return;
  const active = document.querySelector('.filter.active');
  const category = active?.dataset.filter || 'todos';
  const term = search.value.trim().toLocaleLowerCase('es');
  document.querySelectorAll('.catalog-card').forEach(card => {
    const typeMatch = category === 'todos' || card.dataset.category === category || (category === 'blancos-y-rosados' && ['blanco', 'rosado'].includes(card.dataset.category));
    card.hidden = !(typeMatch && card.dataset.search.includes(term));
  });
}
const requestedType = query.get('tipo');
if (requestedType) {
  const initial = document.querySelector(`.filter[data-filter="${CSS.escape(requestedType)}"]`);
  if (initial) { document.querySelector('.filter.active')?.classList.remove('active'); initial.classList.add('active'); }
}
document.querySelectorAll('.filter').forEach(button => button.addEventListener('click', () => {
  document.querySelector('.filter.active')?.classList.remove('active'); button.classList.add('active'); filterProducts();
}));
search?.addEventListener('input', filterProducts);
renderCart(); filterProducts();
if (location.hash === '#mi-seleccion') openOverlay('#cart-overlay');
