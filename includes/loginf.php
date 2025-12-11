<link rel="stylesheet" href="assets/css/auth.css">
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px;">
        <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
    </div>
<?php endif; ?>
<main>
    <div class="auth-card">
        <h2>Inicio de sesión</h2>
        <form id="loginForm" name="LoginUser" method="post" action="">
            <div class="form-row">
                <label for="username">Usuario:</label>
                <input id="username" name="username" type="text" required
                    placeholder="Usuario / E-mail">
            </div>
            <div class="form-row">
                <label for="password">Contraseña:</label>
                <input id="password" name="password" type="password" required>
            </div>
            <input class="enviarform" type="submit" name="btn_registro" value="Iniciar sesión">
        </form>
        <div class="create-account-access">
            <p>¿No tienes una cuenta? <a href="index.php?var=register">Regístrate aquí</a></p>
            <p>¿No te acuerdas de la contraseña? Una lástima no hay forma de recuperarla por el momento.</p>
        </div>
    </div>
</main>