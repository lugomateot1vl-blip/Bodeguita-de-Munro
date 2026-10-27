<?php
declare(strict_types=1);

final class PedidoDAO
{
    public function __construct(private PDO $db) {}

    public function crear(array $cliente, array $items): int
    {
        $this->db->beginTransaction();
        try {
            $total = 0.0;
            $normalizados = [];
            $consulta = $this->db->prepare('SELECT id,nombre,precio,stock FROM bebidas WHERE id=? AND activa=1 FOR UPDATE');
            foreach ($items as $item) {
                if (!isset($item['id'], $item['cantidad'])) {
                    throw new RuntimeException('La selección contiene un producto no válido.');
                }
                $id = (int)$item['id'];
                $consulta->execute([$id]);
                $bebida = $consulta->fetch();
                $cantidad = max(1, (int)$item['cantidad']);
                if (!$bebida) {
                    throw new RuntimeException('Uno de los productos ya no está disponible.');
                }
                $cantidadTotal = ($normalizados[$id]['cantidad'] ?? 0) + $cantidad;
                if ($cantidadTotal > 20 || (int)$bebida['stock'] < $cantidadTotal) {
                    throw new RuntimeException('Uno de los productos ya no tiene stock suficiente.');
                }
                $normalizados[$id] = ['id' => $id, 'cantidad' => $cantidadTotal, 'nombre' => $bebida['nombre'], 'precio' => (float)$bebida['precio']];
            }
            foreach ($normalizados as $item) $total += $item['precio'] * $item['cantidad'];
            $this->db->prepare('INSERT INTO pedidos (cliente_nombre,cliente_email,cliente_telefono,modalidad,domicilio,nota,total) VALUES (?,?,?,?,?,?,?)')
                ->execute([$cliente['nombre'], $cliente['email'], $cliente['telefono'], $cliente['modalidad'], $cliente['domicilio'], $cliente['nota'], $total]);
            $pedidoId = (int)$this->db->lastInsertId();
            $linea = $this->db->prepare('INSERT INTO pedido_items (pedido_id,bebida_id,nombre_bebida,cantidad,precio_unitario) VALUES (?,?,?,?,?)');
            $stock = $this->db->prepare('UPDATE bebidas SET stock=stock-? WHERE id=?');
            foreach ($normalizados as $item) {
                $linea->execute([$pedidoId, $item['id'], $item['nombre'], $item['cantidad'], $item['precio']]);
                $stock->execute([$item['cantidad'], $item['id']]);
            }
            $this->db->commit();
            return $pedidoId;
        } catch (Throwable $error) {
            $this->db->rollBack();
            throw $error;
        }
    }

    public function listar(): array
    {
        $pedidos = $this->db->query('SELECT * FROM pedidos ORDER BY creado_en DESC')->fetchAll();
        $lineas = $this->db->prepare('SELECT nombre_bebida,cantidad,precio_unitario FROM pedido_items WHERE pedido_id=?');
        foreach ($pedidos as &$pedido) {
            $lineas->execute([$pedido['id']]);
            $pedido['items'] = $lineas->fetchAll();
        }
        return $pedidos;
    }

    public function actualizarEstado(int $id, string $estado): void
    {
        $estados = ['Pendiente', 'Confirmado', 'Preparando', 'Listo para retirar', 'En camino', 'Entregado', 'Cancelado'];
        if (!in_array($estado, $estados, true)) {
            throw new RuntimeException('El estado elegido no es válido.');
        }
        $this->db->beginTransaction();
        try {
            $buscar = $this->db->prepare('SELECT estado FROM pedidos WHERE id=? FOR UPDATE');
            $buscar->execute([$id]);
            $pedido = $buscar->fetch();
            if (!$pedido) throw new RuntimeException('No encontramos ese pedido.');
            if ($pedido['estado'] !== $estado && ($pedido['estado'] === 'Cancelado' || $estado === 'Cancelado')) {
                $lineas = $this->db->prepare('SELECT bebida_id,cantidad FROM pedido_items WHERE pedido_id=? AND bebida_id IS NOT NULL');
                $lineas->execute([$id]);
                foreach ($lineas->fetchAll() as $linea) {
                    if ($estado === 'Cancelado') {
                        $this->db->prepare('UPDATE bebidas SET stock=stock+? WHERE id=?')->execute([(int)$linea['cantidad'], (int)$linea['bebida_id']]);
                    } else {
                        $stock = $this->db->prepare('UPDATE bebidas SET stock=stock-? WHERE id=? AND stock>=?');
                        $stock->execute([(int)$linea['cantidad'], (int)$linea['bebida_id'], (int)$linea['cantidad']]);
                        if ($stock->rowCount() !== 1) throw new RuntimeException('No hay stock suficiente para reactivar este pedido.');
                    }
                }
            }
            $this->db->prepare('UPDATE pedidos SET estado=? WHERE id=?')->execute([$estado, $id]);
            $this->db->commit();
        } catch (Throwable $error) {
            $this->db->rollBack();
            throw $error;
        }
    }
}
