<?php
declare(strict_types=1);
require_once __DIR__ . '/app/config/Conexion.php';
require_once __DIR__ . '/app/helpers/funciones.php';
require_once __DIR__ . '/app/dao/BebidaDAO.php';
require_once __DIR__ . '/partials/imagen-bebida.php';
$bebidas = (new BebidaDAO(Conexion::obtener()))->listarActivas();
$categorias = array_values(array_unique(array_filter(array_column($bebidas, 'variedad'))));
$paginaActiva = 'catalogo';
$tituloPagina = 'Catálogo de vinos y bebidas';
require __DIR__ . '/partials/header.php';
?>
<section class="catalog-hero" id="seleccion"><div><p class="eyebrow">LA SELECCIÓN DE LA BODEGUITA</p><h1>Encontrá algo <em>para brindar.</em></h1><p>Precios a la vista, etiquetas para explorar y una selección que cambia con la tienda.</p></div><figure class="catalog-hero-photo"><img src="media/vino-botella-copa.jpg" alt="Botella de vino tinto junto a una copa" fetchpriority="high"><figcaption>Una botella para cada encuentro <span>✳</span></figcaption></figure></section>
<section class="catalog-main">
  <div class="catalog-heading"><div><p class="eyebrow">CATÁLOGO</p><h2>Elegí a tu <em>ritmo.</em></h2></div><p>Sumá tus favoritos a Mi selección y mandanos el pedido. Te confirmamos stock y entrega.</p></div>
  <div class="catalog-controls"><div class="filters" role="group" aria-label="Filtrar por tipo de bebida"><button class="filter active" data-filter="todos">Todo</button><?php foreach ($categorias as $categoria): ?><?php if (!in_array(mb_strtolower($categoria), ["blanco", "rosado"], true)): ?><button class="filter" data-filter="<?= e(mb_strtolower($categoria)) ?>"><?= e($categoria) ?></button><?php endif; ?><?php endforeach; ?><?php if (array_intersect(["blanco", "rosado"], array_map("mb_strtolower", $categorias))): ?><button class="filter" data-filter="blancos-y-rosados">Blancos y rosados</button><?php endif; ?></div><label class="search-box"><span aria-hidden="true">⌕</span><span class="sr-only">Buscar bebidas</span><input id="search" type="search" placeholder="Etiqueta, bodega o región"></label></div>
  <div class="catalog-grid" id="products">
    <?php foreach ($bebidas as $i => $vino): ?>
    <article class="catalog-card" data-category="<?= e(mb_strtolower($vino['variedad'])) ?>" data-search="<?= e(mb_strtolower($vino['nombre'].' '.$vino['bodega'].' '.$vino['region'])) ?>" style="--card-order:<?= $i ?>">
      <?php $foto = imagenCatalogoBebida($vino); ?>
      <div class="catalog-card-image bottle-tone-<?= $i % 4 ?>">
        <a class="catalog-product-link" href="vino.php?id=<?= (int)$vino['id'] ?>" aria-label="Ver detalles de <?= e($vino['nombre']) ?>"><img src="<?= e($foto['src']) ?>" alt="<?= $foto['ilustrativa'] ? 'Imagen ilustrativa de ' . e(mb_strtolower($vino['variedad'])) : 'Botella de ' . e($vino['nombre']) ?>" loading="lazy"></a>
        <?php if ($foto['ilustrativa']): ?><span class="illustrative-label">Foto ilustrativa</span><?php endif; ?>
        <span class="product-type"><?= e($vino['variedad']) ?></span><?php if ((int)$vino['destacada']): ?><span class="featured-badge">De la casa</span><?php endif; ?>
      </div>
      <div class="catalog-card-copy"><p class="wine-maker"><?= e($vino['bodega']) ?> · <?= e($vino['region']) ?></p><h3><a class="catalog-product-title" href="vino.php?id=<?= (int)$vino['id'] ?>"><?= e($vino['nombre']) ?></a></h3><p class="wine-description"><?= e((string)$vino['anio']) ?> · 750 ml<?= $vino['descripcion'] ? ' · '.e($vino['descripcion']) : '' ?></p><div class="wine-stock"><?= (int)$vino['stock'] > 0 ? '<span class="stock-dot"></span> Disponible' : '<span class="stock-dot sold"></span> Sin stock' ?></div><div class="catalog-buy"><strong><?= pesos((float)$vino['precio']) ?></strong><button class="add-to-cart" data-id="<?= (int)$vino['id'] ?>" data-name="<?= e($vino['nombre']) ?>" data-price="<?= e((string)$vino['precio']) ?>" data-stock="<?= (int)$vino['stock'] ?>" <?= (int)$vino['stock'] < 1 ? 'disabled' : '' ?>><?= (int)$vino['stock'] < 1 ? 'Agotado' : 'Agregar +' ?></button></div></div>
    </article>
    <?php endforeach; ?>
    <?php if (!$bebidas): ?><p class="catalog-empty">Estamos preparando el catálogo. Volvé pronto para descubrir nuevas etiquetas.</p><?php endif; ?>
  </div>
  <p class="catalog-note">Los precios mostrados corresponden a la información cargada por la tienda. La disponibilidad se confirma al recibir el pedido.</p>
</section>
<div class="overlay" id="cart-overlay" hidden><aside class="cart-panel" role="dialog" aria-modal="true" aria-labelledby="cart-title"><div class="cart-header"><div><p class="eyebrow">TU PRÓXIMA SOBREMESA</p><h2 id="cart-title">Mi selección</h2></div><button class="close-cart" aria-label="Cerrar selección">×</button></div><div id="cart-items" class="cart-items"></div><div class="cart-footer"><div class="cart-total"><span>Total estimado</span><strong id="cart-total">$ 0</strong></div><p class="cart-disclaimer">La tienda confirma disponibilidad, pago y forma de entrega con vos.</p><button class="button button-wine checkout-button" id="checkout" data-requires-login="<?= cliente_autenticado() ? 'false' : 'true' ?>">Continuar con mi pedido ↗</button></div></aside></div>
<div class="overlay" id="checkout-overlay" hidden><section class="checkout-modal" role="dialog" aria-modal="true" aria-labelledby="checkout-title"><button class="close-checkout" aria-label="Cerrar">×</button><p class="eyebrow">UN PASO MÁS</p><h2 id="checkout-title">¿A quién preparamos el pedido?</h2><p>Te contactaremos para confirmar disponibilidad, pago y entrega.</p><form action="pedido.php" method="post" id="checkout-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="items" id="order-items"><label>Nombre y apellido<input name="nombre" required maxlength="100" autocomplete="name" value="<?= e($_SESSION['cliente_nombre'] ?? '') ?>" <?= cliente_autenticado() ? 'readonly' : '' ?>></label><div class="form-row"><label>Email<input name="email" type="email" required maxlength="140" autocomplete="email" value="<?= e($_SESSION['cliente_email'] ?? '') ?>" <?= cliente_autenticado() ? 'readonly' : '' ?>></label><label>Teléfono<input name="telefono" type="tel" required maxlength="40" autocomplete="tel" value="<?= e($_SESSION['cliente_telefono'] ?? '') ?>"></label></div><label>¿Cómo preferís recibirlo?<select name="modalidad"><option value="Retiro por el local">Retiro por el local</option><option value="Envío a domicilio">Envío a domicilio</option></select></label><label class="address-field" hidden>Domicilio para el envío<input name="domicilio" maxlength="180" autocomplete="street-address"></label><label>Nota (opcional)<textarea name="nota" rows="2" maxlength="300" placeholder="¿Es para regalar? ¿Alguna consulta?"></textarea></label><label class="age-confirm"><input type="checkbox" name="mayor_edad" value="si" required> Confirmo que soy mayor de 18 años.</label><button class="button button-wine" type="submit">Enviar pedido ↗</button></form></section></div>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script src="js/tienda.js?v=20261028-1" defer></script>
<?php require __DIR__ . '/partials/footer.php'; ?>
