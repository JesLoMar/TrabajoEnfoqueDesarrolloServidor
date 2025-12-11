<?php
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    echo "<div class='alert-error'>Acceso denegado.</div>";
    exit;
}
require_once 'config/db.php';
$users = [];
try { //Consulta para traer todos los usuarios con sus datos.
    $sql = "SELECT user_id, username, email, name, surname1, rol FROM user ORDER BY rol ASC, username ASC";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert-error'>Error al cargar usuarios.</div>";
}
?>
<section class="main-mydata">
    <h1 class="mydata-title">Gestión de Usuarios</h1>
    <h3 class="mydata-subtitle">Administra los permisos y elimina usuarios</h3>
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success'): ?>
            <div class="alert-success" style="padding: 10px; margin-bottom: 15px; border-radius: 5px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; text-align: center;">
                Rol actualizado correctamente.
            </div>
        <?php elseif ($_GET['status'] === 'deleted'): ?>
            <div class="alert-success" style="padding: 10px; margin-bottom: 15px; border-radius: 5px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; text-align: center;">
                Usuario y sus pedidos eliminados correctamente.
            </div>
        <?php elseif ($_GET['status'] === 'error_self'): ?>
            <div class="alert-error" style="padding: 10px; margin-bottom: 15px; border-radius: 5px; background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; text-align: center;">
                No puedes eliminar tu propia cuenta de administrador.
            </div>
        <?php elseif ($_GET['status'] === 'error'): ?>
            <div class="alert-error" style="padding: 10px; margin-bottom: 15px; border-radius: 5px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; text-align: center;">
                Ocurrió un error al procesar la solicitud.
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th style="min-width: 250px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="text-mono"><?php echo $user['user_id']; ?></td>
                        <td style="font-weight: bold;">
                            <?php echo htmlspecialchars($user['username']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($user['name'] . ' ' . $user['surname1']); ?>
                        </td>
                        <td>
                            <?php if ($user['rol'] == 1): ?>
                                <span class="badge badge-admin" style="background:green; color:white; padding:2px 6px; border-radius:4px; font-size:0.8em;">ADMIN</span>
                            <?php else: ?>
                                <span class="badge badge-user" style="background:#eee; color:#333; padding:2px 6px; border-radius:4px; font-size:0.8em;">USUARIO</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <!-- Formulario para permitir el cambio de rol -->
                                <form action="actions/change_role.php" method="POST" style="display: flex; gap: 5px; align-items: center; margin: 0;">
                                    <input type="hidden" name="target_user_id" value="<?php echo $user['user_id']; ?>">
                                    <select name="new_rol" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                                        <option value="2" <?php echo ($user['rol'] == 2) ? 'selected' : ''; ?>>Usuario</option>
                                        <option value="1" <?php echo ($user['rol'] == 1) ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-details" style="border: none; cursor: pointer; background: #007bff; color: white; padding: 6px 10px; border-radius: 4px;">
                                        Guardar
                                    </button>
                                </form>
                                <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                    <form action="actions/delete_user.php" method="POST" onsubmit="return confirm('¿Estás SEGURO de eliminar a este usuario?\n\n¡SE BORRARÁN TODOS SUS PEDIDOS!\nEsta acción es irreversible.');" style="margin: 0;">
                                        <input type="hidden" name="target_user_id" value="<?php echo $user['user_id']; ?>">
                                        <button type="submit" style="border: none; cursor: pointer; background: #dc3545; color: white; padding: 6px 10px; border-radius: 4px;">
                                            Eliminar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>