<?php $user = currentUser(); ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
