<?php
/** @var string $content */
/** @var string $pageTitle */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> | Clínio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <header class="topbar">
        <div class="brand">
            <div class="logo">◉</div>
            <div>
                <div class="brand-name">Clínio</div>
                <div class="brand-subtitle">Gestión de pacientes</div>
            </div>
        </div>
        <div class="top-actions">
            <span class="badge">Módulo Pacientes</span>
        </div>
    </header>

    <main class="page">
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="flash">
                <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>
        <?php echo $content; ?>
    </main>
</body>
</html>
