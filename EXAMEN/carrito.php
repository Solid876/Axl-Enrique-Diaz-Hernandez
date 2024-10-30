<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprar'])) {
   
    $_SESSION['carrito'] = [];
    echo "<div class='message'>Compra realizada con éxito.</div>";
}

$carrito = $_SESSION['carrito'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="css/cetis.css">
    <style>
        .container { width: 80%; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .message { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; }
        .logout { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Carrito de Compras</h1>
        <h2><center><p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?>.</p></center></h2>

        <?php if (empty($carrito)): ?>
            <p>No hay productos en el carrito.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalGeneral = 0;
                    foreach ($carrito as $producto_id => $producto): 
                        $total = $producto['precio'] * $producto['cantidad'];
                        $totalGeneral += $total;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td>$<?php echo htmlspecialchars($producto['precio']); ?></td>
                        <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                        <td>$<?php echo htmlspecialchars($total); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"><strong>Total General:</strong></td>
                        <td>$<?php echo htmlspecialchars($totalGeneral); ?></td>
                    </tr>
                </tbody>
            </table>
            <form method="POST">
                <button type="submit" name="comprar">Comprar</button>
            </form>
        <?php endif; ?>

        <div class="logout">
            <a href="logout.php">Cerrar Sesión</a>
        </div>
        <div class="back-button">
        <a href="catalogo.php">Volver al catalogo</a>
    </div>
    </div>
</body>
</html>
