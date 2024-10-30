<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; 

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $error = 'El usuario ya existe.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role]);
        $success = 'Registro exitoso. Puedes iniciar sesión.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar</title>
</head>
<link rel="stylesheet" href="css/cetis.css">
<style>body {
   font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
   background-color: #B2DFDB;   
    margin: 0;
}

.container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    width: 350px;
    margin: 50px auto; 
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

input[type="text"],
input[type="password"],
select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
input[type="password"]:focus,
select:focus {
    border-color: #4a90e2;
    outline: none;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #4a90e2;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #357ab8;
}

label {
    margin: 10px 0;
    display: block; /
    color: #666;
}

.error {
    color: red;
    text-align: center;
    margin-top: 10px;
}

.success {
    color: green;
    text-align: center;
    margin-top: 10px;
}

p {
    text-align: center;
}
</style>
<body>
    <div class="container">
        <h1>Registrar</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <label for="role">Selecciona el rol:</label>
            <select name="role" id="role" required>
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
            </select>
            <button type="submit">Registrar</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <p><a href="index.php">Ya tengo una cuenta. Iniciar sesión.</a></p>
    </div>
</body>
</html>
