<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
</head>
<link rel="stylesheet" href="css/cetis.css">
<style>
body {
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    background-color: #B2DFDB; 
    color: #333;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 800px;
    margin: auto;
    padding: 30px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    opacity: 0.95;
}

h1 {
    font-family: 'Verdana', sans-serif;
    text-align: center;
    color: #333;
    margin-bottom: 25px;
    font-weight: 600;
}

a {
    display: block;
    background-color: #80CBC4; 
    color: white;
    text-decoration: none;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
    margin: 12px 0;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

a:hover {
    background-color: #4DB6AC;
    transform: scale(1.05); 
}

.logout {
    text-align: center;
    margin-top: 25px;
    font-style: italic;
    color: #666;
}


</style>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['user']; ?></h1>
    <a href="gestion_productos.php">Gestionar Productos</a><br>
    <a href="gestion_usuarios.php">Gestionar Usuarios</a><br>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
