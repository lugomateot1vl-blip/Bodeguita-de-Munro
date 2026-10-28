const addWineButton = document.querySelector('#add-wine');
const wineAdded = document.querySelector('#wine-added');
const wineQuantity = document.querySelector('#wine-quantity');
const wineCartKey = 'bodeguita-cart-v1';

function setWineCartCount(items) {
  const badge = document.querySelector('#cart-count');
  if (badge) badge.textContent = String(items.reduce((sum, item) => sum + (Number(item.qty) || 0), 0));
}

addWineButton?.addEventListener('click', () => {
  const id = Number(addWineButton.dataset.id);
  const price = Number(addWineButton.dataset.price);
  const stock = Number(addWineButton.dataset.stock);
  const name = addWineButton.dataset.name || 'Vino';
  const quantity = Math.max(1, Math.floor(Number(wineQuantity?.value) || 1));
  let cart = [];

  try { cart = JSON.parse(localStorage.getItem(wineCartKey) || '[]'); } catch { cart = []; }
  if (!Array.isArray(cart)) cart = [];
  const existing = cart.find(item => Number(item.id) === id);
  const currentQuantity = Number(existing?.qty) || 0;
  if (currentQuantity + quantity > stock) {
    if (wineAdded) { wineAdded.hidden = false; wineAdded.textContent = `Hay ${stock - currentQuantity} unidad${stock - currentQuantity === 1 ? '' : 'es'} disponible${stock - currentQuantity === 1 ? '' : 's'} para esta etiqueta.`; }
    return;
  }

  if (existing) existing.qty = currentQuantity + quantity;
  else cart.push({ id, name, price, stock, qty: quantity });
  localStorage.setItem(wineCartKey, JSON.stringify(cart));
  setWineCartCount(cart);
  if (wineAdded) { wineAdded.hidden = false; wineAdded.textContent = `${quantity} unidad${quantity === 1 ? '' : 'es'} de ${name} en Mi selección.`; }
});
