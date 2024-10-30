<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Usuario</title>
    <link rel="stylesheet" href="css/cetis.css"> 
</head>
<style></style>
<body>
    <h1>Bienvenido, Usuario <?php echo htmlspecialchars($_SESSION['user']); ?></h1>
    <h2>Productos disponibles</h2>
    
    <p>
        <a href="catalogo.php">
            <button style="padding: 10px 20px; background-color: #4a90e2; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Entrar al Catálogo
            </button>
        </a>
    </p>
    
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
