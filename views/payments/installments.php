<?php
require_once __DIR__ . '/../layout.php';
renderHeader('Abonos');
?>
<h2>Abonos de <?= htmlspecialchars($payment->patient) ?> - <?= htmlspecialchars($payment->serviceName) ?></h2>
<div class="stats">
    <div class="card"><strong>Total servicio:</strong> $<?= number_format($payment->servicePrice, 2) ?></div>
    <div class="card"><strong>Pagado:</strong> $<?= number_format($payment->amountPaid, 2) ?></div>
    <div class="card"><strong>Saldo:</strong> $<?= number_format($payment->balance, 2) ?></div>
</div>
<h3>Registrar abono</h3>
<form method="POST" action="/index.php">
    <input type="hidden" name="action" value="add_installment">
    <input type="hidden" name="payment_id" value="<?= htmlspecialchars($payment->id) ?>">
    <label>Monto</label>
    <input type="number" step="0.01" name="amount" required>
    <label>Método</label>
    <select name="payment_method" required>
        <?php foreach ($methods as $method): ?>
            <option value="<?= $method ?>"><?= $method ?></option>
        <?php endforeach; ?>
    </select>
    <label>Nota</label>
    <textarea name="note" rows="2" placeholder="Detalle del abono"></textarea>
    <button type="submit">Agregar abono</button>
</form>
<h3>Historial</h3>
<table>
    <thead><tr><th>Fecha</th><th>Método</th><th>Monto</th><th>Nota</th></tr></thead>
    <tbody>
        <?php foreach ($payment->installments as $installment): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($installment['date'])) ?></td>
                <td><?= htmlspecialchars($installment['method']) ?></td>
                <td>$<?= number_format($installment['amount'], 2) ?></td>
                <td><?= htmlspecialchars($installment['note']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php renderFooter(); ?>
