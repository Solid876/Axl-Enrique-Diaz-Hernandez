<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}


$host = 'localhost'; 
$db = 'papeleria'; 
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

        $stmt = $conn->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "<div class='message'>Usuario agregado exitosamente.</div>";
        } else {
            echo "<div class='error'>Error al agregar el usuario: " . $conn->error . "</div>";
        }

        $stmt->close();
    } elseif ($_POST['action'] === 'edit') {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        if ($password) {
            $stmt = $conn->prepare("UPDATE usuarios SET username=?, password=? WHERE id=?");
            $stmt->bind_param("ssi", $username, $password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET username=? WHERE id=?");
            $stmt->bind_param("si", $username, $id);
        }

        if ($stmt->execute()) {
            echo "<div class='message'>Usuario actualizado exitosamente.</div>";
        } else {
            echo "<div class='error'>Error al actualizar el usuario: " . $conn->error . "</div>";
        }

        $stmt->close();
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<div class='message'>Usuario eliminado exitosamente.</div>";
    } else {
        echo "<div class='error'>Error al eliminar el usuario: " . $conn->error . "</div>";
    }

    $stmt->close();
}

$result = $conn->query("SELECT * FROM usuarios");

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <style>
body {
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    background-color: #E0F7FA;
    color: #333;
    margin: 0;
    padding: 20px;
}


h1 {
    text-align: center;
    color: #00796B; 
    margin-bottom: 25px;
}


form {
    background-color: #FFFFFF;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

input[type="text"],
input[type="number"] {
    width: calc(100% - 22px);
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #B2DFDB;
    border-radius: 8px;
}

input[type="text"]:focus,
input[type="number"]:focus {
    border-color: #80CBC4;
    outline: none;
}

button {
    padding: 12px 20px;
    background-color: #00796B;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #004D40;
    transform: scale(1.05);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #B2DFDB;
}

th {
    background-color: #00796B;
    color: white;
}

tr:hover {
    background-color: #E0F2F1;
}

.actions {
    display: flex;
    gap: 10px;
}

.message, .error {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}

.message {
    background-color: #D0F9E0;
    color: #256C46;
}

.error {
    background-color: #F8D7DA;
    color: #9C1C24;
}

.back-button {
    text-align: center;
    margin-bottom: 20px;
}

.back-button a {
    padding: 10px 18px;
    background-color: #78909C;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.back-button a:hover {
    background-color: #546E7A;
}
    </style>
</head>
<body>
    <h1>Gestión de Usuarios</h1>
    <form method="POST">
        <input type="hidden" name="id" id="user_id" value="">
        <input type="text" name="username" id="username" placeholder="Usuario" required>
        <input type="password" name="password" id="password" placeholder="Contraseña (dejar vacío para no cambiar)">
        <input type="hidden" name="action" value="add">
        <button type="submit">Agregar Usuario</button>
    </form>

    <div class="back-button">
        <a href="admin.php">Volver a la Administración</a>
    </div>

    <h2>Lista de Usuarios</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td class="actions">
                    <button onclick="editUser(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['username']); ?>')">Editar</button>
                    <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');" style="text-decoration: none; color: red;">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function editUser(id, username) {
            document.getElementById('user_id').value = id;
            document.getElementById('username').value = username;
            document.querySelector('input[name="action"]').value = 'edit';
            document.getElementById('password').value = ''; 
    </script>

    <?php
    $conn->close();
    ?>
</body>
</html>
