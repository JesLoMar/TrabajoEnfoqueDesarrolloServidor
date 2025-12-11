<?php if (!empty($errors)): //Muestra de errores.?>
    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px;">
        <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
    </div>
<?php endif; ?>
<link rel="stylesheet" href="assets/css/auth.css">
<main class="auth-main">
    <div class="auth-card">
        <?php if (!empty($errors)): ?>
        <?php endif; ?>
        <h2>Crear nueva cuenta</h2>

        <form id="userForm" name="NewUser" method="post" action="">

            <div class="form-row">
                <label for="username">Nombre de usuario:</label>
                <input id="username" name="username" type="text" placeholder="*" required
                    value="<?php echo isset($data['username']) ? htmlspecialchars($data['username']) : ''; ?>">
            </div>

            <div class="form-row">
                <label for="email">Email:</label>
                <input id="email" name="email" type="email" placeholder="*" required
                    value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>">
            </div>

            <div class="form-row">
                <label for="password">Contraseña:</label>
                <input id="password" name="password" type="password" placeholder="*" required>
            </div>

            <div class="form-row">
                <label for="password_confirm">Confirmar contraseña:</label>
                <input id="password_confirm" name="password_confirm" type="password" placeholder="*" required>
            </div>

            <div class="form-row">
                <label for="name">Nombre:</label>
                <input id="name" name="name" type="text" placeholder="*" required
                    value="<?php echo isset($data['name']) ? htmlspecialchars($data['name']) : ''; ?>">
            </div>

            <div class="form-row">
                <label for="surname1">Primer apellido:</label>
                <input id="surname1" name="surname1" type="text" placeholder="*" required
                    value="<?php echo isset($data['surname1']) ? htmlspecialchars($data['surname1']) : ''; ?>">
            </div>

            <div class="form-row">
                <label for="surname2">Segundo apellido:</label>
                <input id="surname2" name="surname2" type="text"
                    value="<?php echo isset($data['surname2']) ? htmlspecialchars($data['surname2']) : ''; ?>">
            </div>

            <div class="form-row">
                <label for="address">Dirección:</label>
                <input id="address" name="address" type="text"
                    value="<?php echo isset($data['address']) ? htmlspecialchars($data['address']) : ''; ?>">
            </div>

            <div class="form-row">
                <label for="city">Ciudad:</label>
                <input id="city" name="city" type="text"
                    value="<?php echo isset($data['city']) ? htmlspecialchars($data['city']) : ''; ?>">
            </div>

            <div class="form-row" style="padding-bottom: 10px;">
                <label for="zip_code">Código postal:</label>
                <input id="zip_code" name="zip_code" type="text" maxlength="5"
                    value="<?php echo isset($data['zip_code']) ? htmlspecialchars($data['zip_code']) : ''; ?>">
            </div>

            <input class="btn btn-primary btn-full" type="submit" name="btn_registro" value="Crear usuario">
        </form>

        <div class="login-access">
            <p>¿Ya tienes una cuenta? <a href="index.php?var=login">Inicia sesión aquí</a></p>
        </div>
    </div>
</main>