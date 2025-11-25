<?php $pageTitle = 'Editar paciente'; ?>
<section class="card">
    <div class="card-header">
        <div>
            <p class="eyebrow">Edición</p>
            <h1><?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></h1>
            <p class="subtitle">Actualiza datos clínicos y adjunta nuevos archivos.</p>
        </div>
        <a class="btn ghost" href="index.php?action=show&id=<?php echo $patient['id']; ?>&tenant_id=<?php echo $tenantId; ?>">Volver</a>
    </div>

    <form action="index.php?action=update&tenant_id=<?php echo $tenantId; ?>" method="post" enctype="multipart/form-data" class="stacked">
        <input type="hidden" name="id" value="<?php echo $patient['id']; ?>">
        <?php include __DIR__ . '/_form.php'; ?>
        <?php if (!empty($patient['files'])): ?>
            <div class="existing-files">
                <p class="eyebrow">Archivos existentes</p>
                <ul>
                    <?php foreach (json_decode($patient['files'], true) as $file): ?>
                        <li><a href="/storage/uploads/patients/<?php echo rawurlencode($file); ?>" target="_blank"><?php echo htmlspecialchars($file); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="form-actions">
            <a class="btn ghost" href="index.php?action=show&id=<?php echo $patient['id']; ?>&tenant_id=<?php echo $tenantId; ?>">Cancelar</a>
            <button type="submit" class="btn primary">Guardar cambios</button>
        </div>
    </form>
</section>
