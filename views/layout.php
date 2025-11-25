<?php
function renderHeader(string $title)
{
    echo "<!doctype html><html lang='es'><head><meta charset='UTF-8'><title>{$title} - Pagos</title>";
    echo "<style>body{font-family:Arial, sans-serif;margin:0;padding:0;background:#f7f9fb;}header{background:#0a5c83;color:#fff;padding:16px;}nav a{color:#fff;margin-right:12px;text-decoration:none;font-weight:bold;}main{padding:20px;}table{width:100%;border-collapse:collapse;margin-top:12px;}th,td{border:1px solid #e0e0e0;padding:8px;text-align:left;}th{background:#f0f6fa;}form{background:#fff;padding:16px;border:1px solid #eaeaea;border-radius:6px;}input,select,textarea{width:100%;padding:8px;margin:6px 0 12px;border:1px solid #d0d7de;border-radius:4px;}button{background:#0a5c83;color:#fff;border:none;padding:10px 16px;border-radius:4px;cursor:pointer;}button.secondary{background:#6c757d;} .pill{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;} .pill.success{background:#d1e7dd;color:#0f5132;} .pill.warning{background:#fff3cd;color:#664d03;} .stats{display:flex;gap:16px;flex-wrap:wrap;} .card{background:#fff;border:1px solid #eaeaea;border-radius:6px;padding:12px;flex:1;min-width:180px;} .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;} .muted{color:#6c757d;font-size:14px;} </style></head><body>";
    echo "<header><div style='display:flex;justify-content:space-between;align-items:center'><div><strong>Dashboard de Pagos</strong></div><nav><a href='/index.php?view=payments'>Pagos</a><a href='/index.php?view=create'>Nuevo pago</a><a href='/index.php?view=services'>Servicios</a><a href='/index.php?view=reports'>Reportes</a><a href='/index.php?view=dashboard'>Dashboard</a></nav></div></header><main>";
}

function renderFooter()
{
    echo "</main></body></html>";
}
/** @var string $content */
/** @var string $pageTitle */
?>
<!DOCTYPE html>
<?php $user = currentUser(); ?>
<!doctype html>
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
    <title><?php echo $title ?? 'Medico'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-black text-slate-100">
    <div class="max-w-5xl mx-auto px-4 py-8">
        <header class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <span class="text-emerald-300 font-bold text-lg">MD</span>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Sistema Médico</p>
                    <h1 class="text-xl font-semibold text-slate-100">Medico</h1>
                </div>
            </div>

            <?php if ($user): ?>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm text-slate-300">Hola, <?php echo htmlspecialchars($user['name']); ?></p>
                        <p class="text-xs text-emerald-300 uppercase tracking-wide">Rol: <?php echo htmlspecialchars($user['role']); ?></p>
                    </div>
                    <form action="/logout" method="POST">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-500 text-slate-900 font-semibold shadow-lg shadow-emerald-500/30 hover:scale-[1.02] transition">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <a href="/login" class="px-4 py-2 rounded-xl bg-white/10 text-slate-100 border border-white/10 hover:border-emerald-400 hover:text-emerald-300 transition">Iniciar sesión</a>
            <?php endif; ?>
        </header>

        <main>
            <?php include __DIR__ . '/' . $template; ?>
        </main>
    </div>
</body>
</html>
