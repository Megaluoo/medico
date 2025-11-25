<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 40px; color: #0f172a; }
        h1 { font-size: 22px; margin-bottom: 12px; }
        .info { font-size: 14px; color: #334155; margin-bottom: 16px; }
        .block { border: 1px dashed #cbd5e1; padding: 16px; border-radius: 10px; }
        .label { font-weight: 600; font-size: 12px; color: #475569; letter-spacing: 0.04em; text-transform: uppercase; }
        .content { margin-top: 8px; font-size: 14px; line-height: 1.6; }
        .footer { margin-top: 28px; font-size: 12px; color: #475569; }
    </style>
</head>
<body>
    <h1>Reposo médico</h1>
    <div class="info">Paciente: <?php echo htmlspecialchars($patient ?? ''); ?> | Fecha: <?php echo htmlspecialchars($date ?? ''); ?></div>

    <div class="block">
        <div class="label">Diagnóstico y recomendaciones</div>
        <div class="content"><?php echo nl2br(htmlspecialchars($content ?? '')); ?></div>
    </div>

    <div class="info" style="margin-top: 12px;">Médico tratante: <?php echo htmlspecialchars($doctor ?? ''); ?></div>

    <?php if (!empty($notes)): ?>
        <div class="footer">Observaciones: <?php echo nl2br(htmlspecialchars($notes)); ?></div>
    <?php endif; ?>

    <div class="footer">Sello digital: /imagenes/sello-digital.png (placeholder)</div>
</body>
</html>
