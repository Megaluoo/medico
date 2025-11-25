<?php
require_once __DIR__ . '/../layout.php';
renderHeader('Nuevo pago');
?>
<h2>Registrar pago</h2>
<form method="POST" action="/index.php">
    <input type="hidden" name="action" value="create_payment">
    <label>Paciente</label>
    <select name="patient" required>
        <?php foreach ($patients as $patient): ?>
            <option value="<?= htmlspecialchars($patient) ?>"><?= htmlspecialchars($patient) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Servicio</label>
    <select name="service" required>
        <?php foreach ($services as $service): ?>
            <option value="<?= $service->id ?>" data-price="<?= $service->price ?>">
                <?= htmlspecialchars($service->name) ?> - $<?= number_format($service->price, 2) ?> (<?= htmlspecialchars($service->type) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Método de pago</label>
    <select name="payment_method" required>
        <?php foreach ($methods as $method): ?>
            <option value="<?= $method ?>"><?= $method ?></option>
        <?php endforeach; ?>
    </select>

    <label>Monto pagado</label>
    <input type="number" step="0.01" name="amount_paid" required>

    <button type="submit">Guardar pago</button>
</form>
<?php renderFooter(); ?>
