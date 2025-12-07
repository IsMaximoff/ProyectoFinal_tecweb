<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso</title>

    <!-- Estilos -->
    <link rel="stylesheet" href="https://bootswatch.com/4/superhero/bootstrap.min.css">

    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            width: 380px;
            padding: 25px;
            border-radius: 10px;
            background: rgba(0,0,0,0.4);
            box-shadow: 0px 0px 10px #000;
        }
    </style>
</head>

<body>

<div class="login-box">
    <h3 class="text-center mb-4">Iniciar Sesión</h3>

    <div id="errorBox"></div>

    <!-- IMPORTANTE: quitamos action y usamos ID -->
    <form id="loginForm">
        <div class="form-group">
            <label>Usuario:</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary btn-block" type="submit">Entrar</button>

        <div class="text-center mt-3">
            <a href="register.php">¿No tienes cuenta? Regístrate aquí</a>
        </div>
    </form>
</div>

<!-- JS DEL LOGIN -->
<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const res = await fetch('backend/auth-login.php', {
        method: 'POST',
        body: new FormData(e.target)
    });

    const json = await res.json();

    if (json.success) {
        // LOGIN OK → redirige al panel
        window.location = 'index.html';
    } else {
        // MOSTRAR ERROR BONITO
        document.getElementById('errorBox').innerHTML = `
            <div class="alert alert-danger">${json.error}</div>
        `;
    }
});
</script>

</body>
</html>
