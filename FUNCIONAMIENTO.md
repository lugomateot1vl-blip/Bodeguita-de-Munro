# Cómo está organizada La Bodeguita de Munro

La estructura sigue una organización por responsabilidades similar a Logística Delta: cada pantalla PHP presenta y procesa su recorrido; los DAO concentran las consultas SQL; la conexión PDO y las funciones de sesión se comparten desde archivos centrales.

## Recorridos principales

### Catálogo y carrito

`public/index.php` consulta las bebidas activas a `BebidaDAO`, genera las tarjetas del catálogo y entrega su información al carrito de `public/js/tienda.js`. Los filtros por tipo, la búsqueda, el menú móvil y la selección viven en el navegador. El carrito se conserva con `localStorage`.

### Solicitud de compra

Al enviar el formulario, `public/pedido.php` valida datos de contacto y modalidad de entrega. `PedidoDAO` vuelve a consultar y bloquear las bebidas solicitadas, valida el stock vigente y guarda el pedido, sus renglones y el descuento de inventario dentro de una transacción. El total se calcula en el servidor con los precios actuales, sin confiar en el navegador.

La solicitud queda pendiente de confirmación. El comercio coordina el pago y la entrega o retiro con el cliente. No se procesa un pago en línea.

### Administración

`public/admin.php` permite crear el primer usuario administrador y luego iniciar sesión con contraseña protegida por `password_hash`. El panel puede agregar o editar bebidas, cambiar visibilidad, revisar pedidos y actualizar su estado. Los formularios usan token CSRF y las operaciones de administración requieren una sesión válida.

### Base de datos

`database/bodeguita_munro.sql` crea tablas de administradores, bebidas, pedidos y renglones de pedido. También inserta etiquetas de muestra para que la tienda se vea completa al instalarla; sus precios y existencias son ficticios y deben reemplazarse.

## Archivos para recorrer el proyecto

- `app/config/Conexion.php`: configuración MySQL compartida.
- `app/helpers/funciones.php`: escape HTML, sesión, CSRF y permisos.
- `app/dao/BebidaDAO.php`: catálogo y mantenimiento de bebidas.
- `app/dao/PedidoDAO.php`: pedidos, stock y renglones.
- `public/index.php`: página de tienda.
- `public/pedido.php`: creación de pedidos.
- `public/admin.php`: administración del catálogo y ventas.
- `public/css/estilos.css` y `public/js/tienda.js`: interfaz adaptable e interacciones.
