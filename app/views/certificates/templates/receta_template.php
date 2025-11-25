<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 40px; color: #0f172a; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .logo { font-weight: 700; font-size: 18px; letter-spacing: 0.02em; }
        .meta { font-size: 12px; color: #475569; }
        .card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 24px; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .label { font-size: 12px; color: #475569; text-transform: uppercase; letter-spacing: 0.08em; }
        .content { line-height: 1.6; font-size: 14px; }
        .signature { margin-top: 40px; text-align: right; font-size: 14px; color: #475569; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="logo">Clínica Ejemplo</div>
            <div class="meta">Receta médica - <?php echo htmlspecialchars($date ?? ''); ?></div>
        </div>
        <div class="meta">Ruta de firma: /imagenes/firma-medico.png (placeholder)</div>
    </div>

    <div class="card">
        <div class="label">Paciente</div>
        <div class="title"><?php echo htmlspecialchars($patient ?? ''); ?></div>
        <div class="label mt-2">Médico</div>
        <div class="content"><?php echo htmlspecialchars($doctor ?? ''); ?></div>
    </div>

    <div class="card">
        <div class="label">Indicaciones</div>
        <div class="content"><?php echo nl2br(htmlspecialchars($content ?? '')); ?></div>
        <?php if (!empty($notes)): ?>
            <div class="label" style="margin-top: 12px;">Notas</div>
            <div class="content"><?php echo nl2br(htmlspecialchars($notes)); ?></div>
        <?php endif; ?>
    </div>

    <div class="signature">_________________________<br>Firma médica (placeholder)</div>
</body>
</html>
