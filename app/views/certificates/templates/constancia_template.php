<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 40px; color: #0f172a; }
        h1 { font-size: 22px; margin-bottom: 10px; }
        .meta { color: #475569; font-size: 12px; margin-bottom: 20px; }
        .box { border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; }
        .label { text-transform: uppercase; letter-spacing: 0.08em; font-size: 12px; color: #475569; }
        .content { font-size: 14px; line-height: 1.6; margin-top: 8px; }
        .footer { margin-top: 32px; font-size: 12px; color: #475569; }
    </style>
</head>
<body>
    <h1>Constancia médica</h1>
    <div class="meta">Emitida por <?php echo htmlspecialchars($doctor ?? ''); ?> el <?php echo htmlspecialchars($date ?? ''); ?></div>

    <div class="box">
        <div class="label">Paciente</div>
        <div class="content"><?php echo htmlspecialchars($patient ?? ''); ?></div>
        <div class="label" style="margin-top: 12px;">Detalle</div>
        <div class="content"><?php echo nl2br(htmlspecialchars($content ?? '')); ?></div>
    </div>

    <?php if (!empty($notes)): ?>
        <div class="footer">
            Notas adicionales: <?php echo nl2br(htmlspecialchars($notes)); ?>
        </div>
    <?php endif; ?>

    <div class="footer">Sello institucional: /imagenes/sello.png (placeholder)</div>
</body>
</html>
