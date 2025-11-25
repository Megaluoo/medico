<?php $pageTitle = 'Nuevo paciente'; ?>
<section class="card">
    <div class="card-header">
        <div>
            <p class="eyebrow">Crear</p>
            <h1>Nuevo paciente</h1>
            <p class="subtitle">Completa la ficha con datos mínimos y adjunta documentos opcionales.</p>
        </div>
        <a class="btn ghost" href="index.php?tenant_id=<?php echo $tenantId; ?>">Volver</a>
    </div>

    <form action="index.php?action=store&tenant_id=<?php echo $tenantId; ?>" method="post" enctype="multipart/form-data" class="stacked">
        <?php include __DIR__ . '/_form.php'; ?>
        <div class="form-actions">
            <a class="btn ghost" href="index.php?tenant_id=<?php echo $tenantId; ?>">Cancelar</a>
            <button type="submit" class="btn primary">Guardar</button>
        </div>
    </form>
</section>
