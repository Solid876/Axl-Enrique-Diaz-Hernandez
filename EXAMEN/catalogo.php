<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->query('SELECT * FROM productos');
$productos = $stmt->fetchAll();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
    $producto_id = (int)$_POST['producto_id'];

    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id]['cantidad']++;
    } else {
        $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ?');
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch();

        if ($producto) {
            $_SESSION['carrito'][$producto_id] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => 1
            ];
        }
    }
    header('Location: carrito.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="css/cetis.css">
    <style>
        .container { width: 80%; margin: 0 auto; }
        .productos { display: flex; flex-wrap: wrap; gap: 20px; }
        .producto { border: 1px solid #ccc; border-radius: 5px; padding: 10px; width: calc(33.333% - 20px); }
        .precio { font-weight: bold; }
        .logout { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Catálogo de Productos</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?>.</p>
        <div class="productos">
            <?php foreach ($productos as $producto): ?>
                <div class="producto">
                    <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                    <p class="precio">Precio: $<?php echo htmlspecialchars($producto['precio']); ?></p>
                    <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                        <button type="submit">Añadir al carrito</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="logout">
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
    </div>
</body>
</html>
