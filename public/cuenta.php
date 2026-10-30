<?php
declare(strict_types=1);
require_once __DIR__ . '/app/config/Conexion.php';
require_once __DIR__ . '/app/helpers/funciones.php';
require_once __DIR__ . '/partials/cliente-auth.php';

$db = Conexion::obtener();
cliente_preparar_tabla($db);
$error = '';
$modo = ($_GET['modo'] ?? $_POST['modo'] ?? 'ingresar') === 'crear' ? 'crear' : 'ingresar';
$destino = cliente_destino_seguro($_POST['destino'] ?? $_GET['destino'] ?? null);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $accion = $_POST['accion'] ?? '';
    // Cerrar la cuenta de cliente sin cerrar una sesión administrativa que pudiera existir.
    if ($accion === 'salir') {
        unset($_SESSION['cliente_id'], $_SESSION['cliente_nombre'], $_SESSION['cliente_email'], $_SESSION['cliente_telefono']);
        session_regenerate_id(true);
        header('Location: index.php');
        exit;
    }

    $email = mb_strtolower(trim($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    // Tanto el registro como el ingreso usan contraseñas protegidas con el algoritmo de PHP.
    if ($accion === 'crear') {
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 100) $error = 'Completá tu nombre y apellido.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Ingresá un email válido.';
        elseif (mb_strlen($telefono) < 6 || mb_strlen($telefono) > 40) $error = 'Ingresá un teléfono válido para coordinar el pedido.';
        elseif (mb_strlen($password) < 10) $error = 'La contraseña debe tener al menos 10 caracteres.';
        elseif ($password !== (string)($_POST['password_confirmar'] ?? '')) $error = 'Las contraseñas no coinciden.';
        elseif (($_POST['mayor_edad'] ?? '') !== 'si') $error = 'Confirmá que sos mayor de 18 años para crear tu cuenta.';
        else {
            try {
                $insertar = $db->prepare('INSERT INTO clientes(nombre,email,telefono,password_hash) VALUES(?,?,?,?)');
                $insertar->execute([$nombre, $email, $telefono, password_hash($password, PASSWORD_DEFAULT)]);
                session_regenerate_id(true);
                $_SESSION['cliente_id'] = (int)$db->lastInsertId();
                $_SESSION['cliente_nombre'] = $nombre;
                $_SESSION['cliente_email'] = $email;
                $_SESSION['cliente_telefono'] = $telefono;
                header('Location: ' . $destino);
                exit;
            } catch (PDOException $exception) {
                $error = $exception->getCode() === '23000' ? 'Ya existe una cuenta con ese email. Iniciá sesión.' : 'No pudimos crear tu cuenta. Probá nuevamente.';
            }
        }
        $modo = 'crear';
    } elseif ($accion === 'ingresar') {
        $buscar = $db->prepare('SELECT id,nombre,email,telefono,password_hash FROM clientes WHERE email=? LIMIT 1');
        $buscar->execute([$email]);
        $cliente = $buscar->fetch();
        if (!$cliente || !password_verify($password, $cliente['password_hash'])) {
            $error = 'El email o la contraseña no coinciden.';
        } else {
            session_regenerate_id(true);
            $_SESSION['cliente_id'] = (int)$cliente['id'];
            $_SESSION['cliente_nombre'] = $cliente['nombre'];
            $_SESSION['cliente_email'] = $cliente['email'];
            $_SESSION['cliente_telefono'] = $cliente['telefono'];
            header('Location: ' . $destino);
            exit;
        }
        $modo = 'ingresar';
    }
}

$paginaActiva = 'cuenta';
$tituloPagina = 'Tu cuenta';
require __DIR__ . '/partials/header.php';
?>
<section class="pagina-cuenta">
  <div class="introduccion-cuenta"><p class="eyebrow">LA BODEGUITA · MUNRO</p><h1>Tu lugar para<br><em>brindar tranquilo.</em></h1><p>Iniciá sesión para preparar tu pedido. Podés recorrer el catálogo y conocer la tienda cuando quieras.</p><span class="adorno-cuenta" aria-hidden="true">✳</span></div>
  <div class="tarjeta-cuenta">
    <?php if ($error): ?><p class="aviso-cuenta" role="alert"><?= e($error) ?></p><?php endif; ?>
    <?php if (cliente_autenticado()): ?>
      <p class="eyebrow">QUÉ BUENO VERTE</p><h2>Hola, <?= e($_SESSION['cliente_nombre'] ?? ''); ?>.</h2><p class="texto-cuenta">Ya podés armar tu pedido. Tus datos de contacto:</p><div class="datos-cuenta"><span><?= e($_SESSION['cliente_email'] ?? '') ?></span><span><?= e($_SESSION['cliente_telefono'] ?? '') ?></span></div>
      <a class="button button-wine boton-cuenta" href="<?= e(cliente_destino_seguro($_GET['destino'] ?? null)) ?>">Volver a mi selección ↗</a>
      <form method="post" class="salida-cuenta"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="accion" value="salir"><button type="submit">Cerrar sesión</button></form>
    <?php else: ?>
      <nav class="navegacion-cuenta" aria-label="Acceso a tu cuenta"><a <?= $modo === 'ingresar' ? 'aria-current="page"' : '' ?> class="<?= $modo === 'ingresar' ? 'active' : '' ?>" href="cuenta.php?modo=ingresar&amp;destino=<?= rawurlencode($destino) ?>">Iniciar sesión</a><a <?= $modo === 'crear' ? 'aria-current="page"' : '' ?> class="<?= $modo === 'crear' ? 'active' : '' ?>" href="cuenta.php?modo=crear&amp;destino=<?= rawurlencode($destino) ?>">Crear cuenta</a></nav>
      <h2><?= $modo === 'crear' ? 'Empecemos por conocerte.' : 'Qué bueno tenerte de vuelta.' ?></h2>
      <p class="texto-cuenta"><?= $modo === 'crear' ? 'Creá tu cuenta para preparar tus pedidos con tus datos a mano.' : 'Ingresá para continuar con tu pedido.' ?></p>
      <form method="post" class="formulario-cuenta"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="accion" value="<?= e($modo) ?>"><input type="hidden" name="destino" value="<?= e($destino) ?>">
        <?php if ($modo === 'crear'): ?><label>Nombre y apellido<input name="nombre" required minlength="2" maxlength="100" autocomplete="name" value="<?= e($_POST['nombre'] ?? '') ?>"></label><?php endif; ?>
        <label>Email<input type="email" name="email" required maxlength="140" autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>"></label>
        <?php if ($modo === 'crear'): ?><label>Teléfono<input type="tel" name="telefono" required minlength="6" maxlength="40" autocomplete="tel" value="<?= e($_POST['telefono'] ?? '') ?>"></label><?php endif; ?>
        <label>Contraseña<input type="password" name="password" required <?= $modo === 'crear' ? 'minlength="10" autocomplete="new-password"' : 'autocomplete="current-password"' ?>></label>
        <?php if ($modo === 'crear'): ?><label>Repetí la contraseña<input type="password" name="password_confirmar" required minlength="10" autocomplete="new-password"></label><?php endif; ?>
        <?php if ($modo === 'crear'): ?><label class="confirmacion-edad"><input type="checkbox" name="mayor_edad" value="si" required> Confirmo que soy mayor de 18 años.</label><?php endif; ?>
        <button class="button button-wine boton-cuenta" type="submit"><?= $modo === 'crear' ? 'Crear mi cuenta' : 'Iniciar sesión' ?> ↗</button>
      </form>
      <p class="nota-cuenta">Solo necesitás iniciar sesión para hacer un pedido. El catálogo y la información de la tienda siguen abiertos para todos.</p>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
