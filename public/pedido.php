<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/config/Conexion.php';
require_once __DIR__ . '/../app/helpers/funciones.php';
require_once __DIR__ . '/../app/dao/PedidoDAO.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
validar_csrf();
$nombre = trim($_POST['nombre'] ?? ''); $email = trim($_POST['email'] ?? ''); $telefono = trim($_POST['telefono'] ?? '');
$modalidad = $_POST['modalidad'] ?? ''; $domicilio = trim($_POST['domicilio'] ?? ''); $nota = trim($_POST['nota'] ?? '');
$items = json_decode($_POST['items'] ?? '', true);
$errores = [];
if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 100) $errores[] = 'Ingresá tu nombre.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'Ingresá un email válido.';
if (mb_strlen($telefono) < 6 || mb_strlen($telefono) > 40) $errores[] = 'Ingresá un teléfono de contacto válido.';
if (($_POST['mayor_edad'] ?? '') !== 'si') $errores[] = 'Confirmá que sos mayor de 18 años para continuar.';
if (!in_array($modalidad, ['Retiro por el local','Envío a domicilio'], true)) $errores[] = 'Elegí una modalidad de entrega.';
if ($modalidad === 'Envío a domicilio' && mb_strlen($domicilio) < 5) $errores[] = 'Completá el domicilio para el envío.';
if (!is_array($items) || !$items || count($items) > 30) $errores[] = 'Tu selección está vacía o no es válida.';
$pedidoId = null;
if (!$errores) {
    try {
        $pedidoId = (new PedidoDAO(Conexion::obtener()))->crear(['nombre'=>$nombre,'email'=>$email,'telefono'=>$telefono,'modalidad'=>$modalidad,'domicilio'=>$modalidad==='Envío a domicilio'?$domicilio:'','nota'=>$nota], $items);
        echo '<!doctype html><html lang="es"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><link rel="stylesheet" href="css/estilos.css"><title>Pedido recibido · La Bodeguita de Munro</title><body class="result-page"><main class="result-card"><span class="result-icon">✳</span><p class="eyebrow">GRACIAS POR ELEGIRNOS</p><h1>Tu pedido ya está<br>en <em>la Bodeguita.</em></h1><p>Recibimos tu solicitud <strong>#'.(int)$pedidoId.'</strong>. Te contactaremos para confirmar stock, forma de pago y coordinación de '.($modalidad==='Envío a domicilio'?'la entrega':'el retiro').'.</p><p class="order-warning">El pedido no se cobra en línea y queda pendiente de confirmación por parte de la tienda.</p><a class="button button-dark" href="index.php">Volver a la tienda ↗</a></main></body></html>';
        exit;
    } catch (Throwable $error) {
        $errores[] = $error instanceof RuntimeException ? $error->getMessage() : 'No pudimos registrar el pedido. Intentá nuevamente en unos minutos.';
    }
}
?>
<!doctype html><html lang="es"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><link rel="stylesheet" href="css/estilos.css"><title>No pudimos registrar el pedido</title><body class="result-page"><main class="result-card"><p class="eyebrow">REVISÁ TU PEDIDO</p><h1>Nos falta un <em>detalle.</em></h1><ul class="error-list"><?php foreach ($errores as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul><a class="button button-dark" href="index.php">Volver a la tienda ↗</a></main></body></html>
