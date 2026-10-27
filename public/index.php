<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/config/Conexion.php';
require_once __DIR__ . '/../app/helpers/funciones.php';
require_once __DIR__ . '/../app/dao/BebidaDAO.php';
$db = Conexion::obtener();
$bebidas = (new BebidaDAO($db))->listarActivas();
$categorias = array_values(array_unique(array_column($bebidas, 'variedad')));
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f5f0e6"><meta name="description" content="Vinos elegidos con cariño en La Bodeguita de Munro. Descubrí etiquetas, regalos y próximas catas.">
  <title>La Bodeguita de Munro · Vinos para compartir</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<a class="saltear" href="#contenido">Ir al contenido</a>
<div class="announcement">Un buen vino siempre encuentra una buena mesa <span>✳</span> Munro, Buenos Aires</div>
<header class="topbar">
  <a class="wordmark" href="index.php" aria-label="La Bodeguita de Munro, inicio"><span class="wordmark-icon">bm</span><span>La Bodeguita <small>de Munro</small></span></a>
  <button class="menu-toggle" aria-expanded="false" aria-controls="nav">Menú</button>
  <nav id="nav" class="nav"><a href="#seleccion">Vinos</a><a href="#historia">La Bodeguita</a><a href="#catas">Catas</a><a href="#visitanos">Encontranos</a></nav>
  <button class="cart-button" id="open-cart" aria-label="Abrir carrito"><span aria-hidden="true">♧</span> Mi selección <b id="cart-count">0</b></button>
</header>
<main id="contenido">
  <section class="hero">
    <div class="hero-copy"><p class="eyebrow">VINOS CON HISTORIA · MUNRO</p><h1>El vino justo<br>para <em>ese momento.</em></h1><p class="hero-lead">Una selección hecha para descubrir, regalar y compartir. Pasá, preguntá, encontrá tu próximo favorito.</p><a class="button button-dark" href="#seleccion">Explorar la tienda <span aria-hidden="true">↗</span></a><div class="hero-note"><span class="note-mark">✳</span><span>Una bodega de barrio,<br>con ganas de sorprenderte.</span></div></div>
    <div class="hero-image" role="img" aria-label="Estanterías de vinos de La Bodeguita de Munro"><div class="image-caption">Una casa llena de buenos encuentros <span>Munro · Buenos Aires</span></div><span class="image-seal">DESDE<br>EL<br>BARRIO</span></div>
    <div class="hero-side">VINO · AMIGOS · SOBREMESA</div>
  </section>
  <section class="ticker" aria-label="Especialidades"><span>VINOS QUE NOS GUSTAN</span><i>✳</i><span>ETIQUETAS PARA DESCUBRIR</span><i>✳</i><span>EL BARRIO SE ENCUENTRA ACÁ</span><i>✳</i><span>VINOS QUE NOS GUSTAN</span></section>
  <section class="shop section-wrap" id="seleccion">
    <div class="section-heading"><div><p class="eyebrow">LA SELECCIÓN DE LA CASA</p><h2>Algo rico para <em>llevar.</em></h2></div><p>Desde ese Malbec que nunca falla<br>hasta una etiqueta nueva para probar.</p></div>
    <div class="shop-tools"><div class="filters" role="group" aria-label="Filtrar por tipo de vino"><button class="filter active" data-filter="all">Todo</button><?php foreach ($categorias as $categoria): ?><button class="filter" data-filter="<?= e(mb_strtolower($categoria)) ?>"><?= e($categoria) ?></button><?php endforeach; ?></div><label class="search"><span aria-hidden="true">⌕</span><span class="sr-only">Buscar vinos</span><input id="search" type="search" placeholder="Buscar una etiqueta..."></label></div>
    <div class="product-grid" id="products">
      <?php foreach ($bebidas as $i => $vino): ?>
      <article class="product-card" data-category="<?= e(mb_strtolower($vino['variedad'])) ?>" data-search="<?= e(mb_strtolower($vino['nombre'].' '.$vino['bodega'].' '.$vino['region'])) ?>" style="--delay:<?= $i * 65 ?>ms">
        <div class="product-visual tone-<?= $i % 4 ?>"><?php if ($vino['imagen']): ?><img src="<?= e($vino['imagen']) ?>" alt="Botella de <?= e($vino['nombre']) ?>" loading="lazy"><?php else: ?><div class="bottle" aria-hidden="true"><span></span><i></i><b><?= e(mb_strtoupper(mb_substr($vino['bodega'],0,1))) ?></b></div><?php endif; ?><span class="product-tag"><?= e($vino['variedad']) ?></span><?php if ((int)$vino['destacada']): ?><span class="favorite-tag">De la casa</span><?php endif; ?></div>
        <div class="product-info"><p class="product-origin"><?= e($vino['bodega']) ?> · <?= e($vino['region']) ?></p><h3><?= e($vino['nombre']) ?></h3><p class="product-detail"><?= e((string)$vino['anio']) ?> · 750 ml<?= $vino['descripcion'] ? ' · '.e($vino['descripcion']) : '' ?></p><div class="product-buy"><strong><?= pesos((float)$vino['precio']) ?></strong><button class="add-button" data-id="<?= (int)$vino['id'] ?>" data-name="<?= e($vino['nombre']) ?>" data-price="<?= e((string)$vino['precio']) ?>" data-stock="<?= (int)$vino['stock'] ?>" <?= (int)$vino['stock'] < 1 ? 'disabled' : '' ?> aria-label="Agregar <?= e($vino['nombre']) ?> al carrito"><?= (int)$vino['stock'] < 1 ? 'Sin stock' : 'Agregar +' ?></button></div></div>
      </article>
      <?php endforeach; ?>
      <?php if (!$bebidas): ?><div class="empty-state">Estamos armando la selección. Volvé pronto para descubrir nuevas etiquetas.</div><?php endif; ?>
    </div>
  </section>
  <section class="story" id="historia"><div class="story-image" role="img" aria-label="La fachada de la bodega en Munro"></div><div class="story-copy"><p class="eyebrow">UN LUGAR PARA VOLVER</p><h2>El barrio tiene<br>su <em>propia bodega.</em></h2><p>Nos gustan las etiquetas con algo para contar, las recomendaciones sin vueltas y esas charlas que empiezan con “¿qué vino llevo?” y terminan con una sobremesa larga.</p><p>Acercate a conocer la tienda. Si no sabés por dónde empezar, te ayudamos a encontrarlo.</p><a href="#visitanos" class="text-link">Vení a saludarnos <span>↗</span></a><span class="story-doodle" aria-hidden="true">✳</span></div></section>
  <section class="tasting section-wrap" id="catas"><div class="tasting-stamp">COPA<br>EN MANO<br><span>✳</span></div><div><p class="eyebrow">NOS VEMOS ALREDEDOR DE UNA COPA</p><h2>Las catas se disfrutan <em>en compañía.</em></h2><p>De vez en cuando abrimos botellas, conocemos bodegas y compartimos una noche distinta. Escribinos para enterarte de la próxima fecha.</p><a class="button button-outline" href="#visitanos">Consultar próximas catas</a></div><div class="tasting-photo" role="img" aria-label="Copas de vino listas para una cata"></div></section>
  <section class="visit" id="visitanos"><div><p class="eyebrow">TE ESPERAMOS</p><h2>Un vino, una charla<br>y <em>tu lugar en la mesa.</em></h2><p>La Bodeguita de Munro · Buenos Aires</p></div><a class="button button-light" href="https://maps.google.com/?q=La+Bodeguita+de+Munro" target="_blank" rel="noopener">Cómo llegar <span>↗</span></a></section>
</main>
<footer class="footer"><a class="wordmark footer-mark" href="#contenido"><span class="wordmark-icon">bm</span><span>La Bodeguita <small>de Munro</small></span></a><p>Vinos para los momentos que merecen quedarse.</p><a href="admin.php" class="admin-link">Administración</a><small>© <?= date('Y') ?> La Bodeguita de Munro · Tomar con moderación. Prohibida la venta de alcohol a menores de 18 años.</small></footer>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
<div class="overlay" id="cart-overlay" hidden><aside class="cart-panel" role="dialog" aria-modal="true" aria-labelledby="cart-title"><div class="cart-header"><div><p class="eyebrow">TU PRÓXIMA SOBREMESA</p><h2 id="cart-title">Tu selección</h2></div><button class="close-cart" aria-label="Cerrar carrito">×</button></div><div id="cart-items" class="cart-items"></div><div class="cart-footer"><div class="cart-total"><span>Total estimado</span><strong id="cart-total">$ 0</strong></div><p class="cart-disclaimer">El pedido queda sujeto a confirmación de stock y coordinación de entrega o retiro.</p><button class="button button-dark checkout-button" id="checkout">Continuar con mi pedido <span>↗</span></button></div></aside></div>
<div class="overlay" id="checkout-overlay" hidden><section class="checkout-modal" role="dialog" aria-modal="true" aria-labelledby="checkout-title"><button class="close-checkout" aria-label="Cerrar">×</button><p class="eyebrow">UN PASO MÁS</p><h2 id="checkout-title">¿A quién preparamos el pedido?</h2><p>Te contactaremos para confirmar disponibilidad, pago y entrega.</p><form action="pedido.php" method="post" id="checkout-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="items" id="order-items"><label>Nombre y apellido<input name="nombre" required maxlength="100" autocomplete="name"></label><div class="form-row"><label>Email<input name="email" type="email" required maxlength="140" autocomplete="email"></label><label>Teléfono<input name="telefono" type="tel" required maxlength="40" autocomplete="tel"></label></div><label>¿Cómo preferís recibirlo?<select name="modalidad"><option value="Retiro por el local">Retiro por el local</option><option value="Envío a domicilio">Envío a domicilio</option></select></label><label class="address-field" hidden>Domicilio para el envío<input name="domicilio" maxlength="180" autocomplete="street-address"></label><label>Nota (opcional)<textarea name="nota" rows="2" maxlength="300" placeholder="¿Es para regalar? ¿Alguna consulta?"></textarea></label><label class="age-confirm"><input type="checkbox" name="mayor_edad" value="si" required> Confirmo que soy mayor de 18 años.</label><button class="button button-dark" type="submit">Enviar pedido <span>↗</span></button></form></section></div>
<script src="js/tienda.js" defer></script>
</body></html>
