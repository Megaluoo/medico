<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 40px; color: #0f172a; }
        h1 { font-size: 24px; margin-bottom: 12px; }
        .subtitle { color: #475569; font-size: 14px; margin-bottom: 20px; }
        .section { margin-bottom: 18px; }
        .section-title { font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
        .section-body { font-size: 14px; line-height: 1.7; color: #334155; }
        .footer { margin-top: 30px; font-size: 12px; color: #475569; }
    </style>
</head>
<body>
    <h1>Informe médico</h1>
    <div class="subtitle">Paciente: <?php echo htmlspecialchars($patient ?? ''); ?> | Médico responsable: <?php echo htmlspecialchars($doctor ?? ''); ?> | Fecha: <?php echo htmlspecialchars($date ?? ''); ?></div>

    <div class="section">
        <div class="section-title">Resumen</div>
        <div class="section-body"><?php echo nl2br(htmlspecialchars($content ?? '')); ?></div>
    </div>

    <?php if (!empty($notes)): ?>
        <div class="section">
            <div class="section-title">Notas adicionales</div>
            <div class="section-body"><?php echo nl2br(htmlspecialchars($notes)); ?></div>
        </div>
    <?php endif; ?>

    <div class="footer">Ruta de anexos: /documentos/anexos.pdf (placeholder). No se incluye archivo.</div>
</body>
</html>
