<?php
require_once __DIR__ . '/../layout.php';
renderHeader('Pagos');
?>
<h2>Pagos registrados</h2>
<div class="stats">
    <div class="card"><strong>Total cobrado:</strong><div>$<?= number_format($totals['total_paid'], 2) ?></div></div>
    <div class="card"><strong>Saldo pendiente:</strong><div>$<?= number_format($totals['total_balance'], 2) ?></div></div>
</div>
<table>
    <thead>
        <tr>
            <th>Paciente</th>
            <th>Servicio</th>
            <th>Método inicial</th>
            <th>Pagado</th>
            <th>Saldo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($payments)): ?>
            <tr><td colspan="6">No hay pagos registrados.</td></tr>
        <?php else: ?>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= htmlspecialchars($payment->patient) ?></td>
                    <td><?= htmlspecialchars($payment->serviceName) ?></td>
                    <td><?= htmlspecialchars($payment->paymentMethod) ?></td>
                    <td>$<?= number_format($payment->amountPaid, 2) ?></td>
                    <td>
                        <?php if ($payment->balance <= 0): ?>
                            <span class="pill success">Cancelado</span>
                        <?php else: ?>
                            <span class="pill warning">$<?= number_format($payment->balance, 2) ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/index.php?view=installments&id=<?= urlencode($payment->id) ?>">Ver abonos</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<?php renderFooter(); ?>
