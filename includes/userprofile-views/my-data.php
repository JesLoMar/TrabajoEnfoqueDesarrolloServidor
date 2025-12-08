<?php
if (isset($_SESSION['user_id'])):
    $name = $_SESSION['name'] ?? '';
    $surname1 = $_SESSION['surname1'] ?? '';
    $surname2  = $_SESSION['surname2'] ?? '';
    $address = $_SESSION['address'] ?? '';
    $city = $_SESSION['city'] ?? '';
    $zip_code = $_SESSION['zip_code'] ?? '';
endif;
?>
<section class="main-mydata">
    <h1 class="mydata-title">Mis datos</h1>
    <h3 class="mydata-subtitle">Edite la información de su cuenta</h3>

    <?php if (isset($_GET['status'])): ?>

        <?php if ($_GET['status'] === 'success'): ?>
            <div class="alert-success" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; text-align: center;">
                Datos actualizados correctamente.
            </div>

        <?php elseif ($_GET['status'] === 'error' && isset($_SESSION['errores_update'])): ?>
            <div class="alert-error" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                <?php foreach ($_SESSION['errores_update'] as $error): ?>
                    <p style="margin: 0; padding: 2px;">Error <?php echo $error; ?></p>
                <?php endforeach; ?>

                <?php unset($_SESSION['errores_update']); ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <div class="mydata-personaldata">
        <form action="actions/update_profile.php" method="POST" class="mydata-form">
            <h3>Datos personales</h3>
            <div class="form-row">
                <label for="name">Nombre:</label>
                <input id="name" name="name" type="text"
                    value="<?php echo htmlspecialchars($name); ?>">
            </div>

            <div class="form-row">
                <label for="surname1">Primer apellido:</label>
                <input id="surname1" name="surname1" type="text"
                    value="<?php echo htmlspecialchars($surname1); ?>">
            </div>

            <div class="form-row">
                <label for="surname2">Segundo apellido:</label>
                <input id="surname2" name="surname2" type="text"
                    value="<?php echo htmlspecialchars($surname2); ?>">
            </div>

            <h3>Datos de envío</h3>
            <div class="form-row">
                <label for="address">Dirección:</label>
                <input id="address" name="address" type="text"
                    value="<?php echo htmlspecialchars($address); ?>">
            </div>

            <div class="form-row">
                <label for="city">Ciudad:</label>
                <input id="city" name="city" type="text"
                    value="<?php echo htmlspecialchars($city); ?>">
            </div>

            <div class="form-row">
                <label for="zip_code">Código postal:</label>
                <input id="zip_code" name="zip_code" type="text"
                    value="<?php echo htmlspecialchars($zip_code); ?>">
            </div>

            <button type="submit" class="btn-save">Guardar Cambios</button>
        </form>
    </div>
</section>