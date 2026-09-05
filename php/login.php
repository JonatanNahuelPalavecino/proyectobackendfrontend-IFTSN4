<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email = "" && $password = "") {
        echo "Por favor, ingrese su correo electrónico y contraseña.";
    } else {
        echo "Correo electrónico: " . $email . "<br>";
        echo "Contraseña: " . $password;
    }
}
?>

<?php
    include "header.php";
?>

<main>
    <form method="post">
        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Iniciar sesión</button>
    </form>
</main>


<?php
    include "footer.php";

?>