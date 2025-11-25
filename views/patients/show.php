<?php $pageTitle = 'Ficha de paciente'; ?>
<section class="card">
    <div class="card-header">
        <div>
            <p class="eyebrow">Ficha</p>
            <h1><?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></h1>
            <p class="subtitle">Revisa los datos clínicos y archivos adjuntos.</p>
        </div>
        <div class="actions">
            <a class="btn ghost" href="index.php?tenant_id=<?php echo $tenantId; ?>">Volver</a>
            <a class="btn primary" href="index.php?action=edit&id=<?php echo $patient['id']; ?>&tenant_id=<?php echo $tenantId; ?>">Editar</a>
        </div>
    </div>

    <div class="info-grid">
        <div>
            <p class="eyebrow">Datos personales</p>
            <ul class="detail-list">
                <li><span>Fecha de nacimiento</span><strong><?php echo htmlspecialchars($patient['birth_date']); ?></strong></li>
                <li><span>Sexo</span><strong><?php echo htmlspecialchars($patient['sex']); ?></strong></li>
                <li><span>Teléfono</span><strong><?php echo htmlspecialchars($patient['phone']); ?></strong></li>
                <li><span>Email</span><strong><?php echo htmlspecialchars($patient['email']); ?></strong></li>
                <li><span>Dirección</span><strong><?php echo htmlspecialchars($patient['address']); ?></strong></li>
            </ul>
        </div>
        <div>
            <p class="eyebrow">Salud</p>
            <ul class="detail-list">
                <li><span>Alergias</span><strong><?php echo htmlspecialchars($patient['allergies']); ?></strong></li>
                <li><span>Antecedentes</span><strong><?php echo htmlspecialchars($patient['history']); ?></strong></li>
                <li><span>Observaciones</span><strong><?php echo nl2br(htmlspecialchars($patient['notes'])); ?></strong></li>
            </ul>
        </div>
    </div>

    <?php if (!empty($patient['files'])): ?>
        <div class="files">
            <p class="eyebrow">Archivos</p>
            <ul class="file-list">
                <?php foreach (json_decode($patient['files'], true) as $file): ?>
                    <li>
                        <a href="/storage/uploads/patients/<?php echo rawurlencode($file); ?>" target="_blank" class="file-chip">
                            <span class="icon">📎</span> <?php echo htmlspecialchars($file); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</section>
