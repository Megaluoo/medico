<?php
require_once __DIR__ . '/../layout.php';
renderHeader('Servicios');
?>
<h2>Servicios configurables</h2>
<form method="POST" action="/index.php">
    <input type="hidden" name="action" value="create_service">
    <label>Nombre</label>
    <input type="text" name="name" required>
    <label>Precio</label>
    <input type="number" step="0.01" name="price" required>
    <label>Tipo</label>
    <input type="text" name="type" required>
    <button type="submit">Agregar servicio</button>
</form>
<h3>Servicios existentes</h3>
<table>
    <thead><tr><th>Nombre</th><th>Precio</th><th>Tipo</th></tr></thead>
    <tbody>
    <?php foreach ($services as $service): ?>
        <tr>
            <td><?= htmlspecialchars($service->name) ?></td>
            <td>$<?= number_format($service->price, 2) ?></td>
            <td><?= htmlspecialchars($service->type) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php renderFooter(); ?>
