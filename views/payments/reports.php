<?php
require_once __DIR__ . '/../layout.php';
renderHeader('Reportes');
?>
<h2>Reportes</h2>
<div class="grid">
    <div class="card">
        <h3>Ingresos de hoy</h3>
        <p><strong>$<?= number_format($incomeToday, 2) ?></strong></p>
        <p class="muted">Total sumando pagos y abonos registrados hoy.</p>
    </div>
    <div class="card">
        <h3>Métodos más usados</h3>
        <ul>
            <?php foreach ($methodStats as $method => $count): ?>
                <li><?= htmlspecialchars($method) ?> (<?= $count ?>)</li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<h3>Ingresos por mes</h3>
<table>
    <thead><tr><th>Mes</th><th>Total</th></tr></thead>
    <tbody>
    <?php foreach ($incomeByMonth as $month => $amount): ?>
        <tr>
            <td><?= $month ?></td>
            <td>$<?= number_format($amount, 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php renderFooter(); ?>
