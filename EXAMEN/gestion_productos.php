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
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];

        $stmt = $conn->prepare("INSERT INTO productos (nombre, precio) VALUES (?, ?)");
        $stmt->bind_param("sd", $product_name, $price);

        if ($stmt->execute()) {
            echo "<div class='message'>Producto agregado exitosamente.</div>";
        } else {
            echo "<div class='error'>Error al agregar el producto: " . $conn->error . "</div>";
        }

        $stmt->close();
    } elseif ($_POST['action'] === 'edit') {
        $id = $_POST['id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];

        $stmt = $conn->prepare("UPDATE productos SET nombre=?, precio=? WHERE id=?");
        $stmt->bind_param("sdi", $product_name, $price, $id);

        if ($stmt->execute()) {
            echo "<div class='message'>Producto actualizado exitosamente.</div>";
        } else {
            echo "<div class='error'>Error al actualizar el producto: " . $conn->error . "</div>";
        }

        $stmt->close();
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM productos WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<div class='message'>Producto eliminado exitosamente.</div>";
    } else {
        echo "<div class='error'>Error al eliminar el producto: " . $conn->error . "</div>";
    }

    $stmt->close();
}

$result = $conn->query("SELECT * FROM productos");

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="css/cetis.css">
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
    <div class="back-button">
        <a href="admin.php">Volver a la Administración</a>
    </div>
    
    <h1>Gestión de Productos</h1>
    <form method="POST">
        <input type="hidden" name="id" id="product_id" value="">
        <input type="text" name="product_name" id="product_name" placeholder="Nombre del Producto" required>
        <input type="number" name="price" id="product_price" placeholder="Precio" required>
        <input type="hidden" name="action" value="add">
        <button type="submit">Agregar Producto</button>
    </form>

    <h2>Lista de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['precio']; ?></td>
                <td class="actions">
                    <button onclick="editProduct(<?php echo $row['id']; ?>, '<?php echo $row['nombre']; ?>', <?php echo $row['precio']; ?>)">Editar</button>
                    <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');" style="text-decoration: none; color: red;">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function editProduct(id, name, price) {
            document.getElementById('product_id').value = id;
            document.getElementById('product_name').value = name;
            document.getElementById('product_price').value = price;
            document.querySelector('input[name="action"]').value = 'edit';
        }
    </script>

    <?php
    $conn->close();
    ?>
</body>
</html>
