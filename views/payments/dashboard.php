<?php
require_once __DIR__ . '/../layout.php';
renderHeader('Dashboard');
?>
<h2>Dashboard</h2>
<div class="stats">
    <div class="card"><strong>Total cobrado:</strong> $<?= number_format($totals['total_paid'], 2) ?></div>
    <div class="card"><strong>Saldo pendiente:</strong> $<?= number_format($totals['total_balance'], 2) ?></div>
</div>
<h3>Ingresos recientes</h3>
<table>
    <thead><tr><th>Fecha</th><th>Paciente</th><th>Servicio</th><th>Monto</th><th>Método</th></tr></thead>
    <tbody>
    <?php foreach ($recent as $income): ?>
        <tr>
            <td><?= date('d/m/Y H:i', strtotime($income['date'])) ?></td>
            <td><?= htmlspecialchars($income['patient']) ?></td>
            <td><?= htmlspecialchars($income['service']) ?></td>
            <td>$<?= number_format($income['amount'], 2) ?></td>
            <td><?= htmlspecialchars($income['method']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php renderFooter(); ?>
