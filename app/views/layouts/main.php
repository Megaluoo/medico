<?php
$appConfig = require __DIR__ . '/../../../config/config.php';
$appName = $appConfig['app']['name'] ?? 'Clinio';
$pageTitle = $pageTitle ?? $appName;
?>
<!DOCTYPE html>
<html lang="es" x-data="themeController()" :class="darkMode ? 'dark' : ''" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> · <?= htmlspecialchars($appName) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        accent: '#0ea5e9',
                        ink: '#0f172a',
                    },
                    boxShadow: {
                        soft: '0 20px 45px rgba(15,23,42,0.12)',
                        glass: '0 10px 30px rgba(15,23,42,0.08)'
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="/assets/css/tailwind.css">
</head>
<body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100 selection:bg-primary/20 selection:text-primary">
    <div class="min-h-screen flex" x-data="sidebar()">
        <aside class="hidden md:flex flex-col w-72 bg-white/80 dark:bg-slate-800/70 backdrop-blur border-r border-slate-100 dark:border-slate-700 shadow-glass">
            <div class="flex items-center gap-3 px-6 py-6 border-b border-slate-100 dark:border-slate-700">
                <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-soft">
                    <span class="text-white text-xl font-semibold">C</span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-300">SaaS Médico</p>
                    <h1 class="text-xl font-semibold text-slate-900 dark:text-white">Clinio</h1>
                </div>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <?php $links = [
                    ['Dashboard', '/dashboard', 'M12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16z'],
                    ['Pacientes', '/pacientes', 'M8 7h8M8 12h8M8 17h8'],
                    ['Citas', '/citas', 'M5 8h14M5 12h14M5 16h14'],
                    ['Pagos', '/pagos', 'M12 6v12m-6-6h12'],
                    ['Historias clínicas', '/historias', 'M6 4h12v16H6z'],
                    ['Recetas', '/recetas', 'M8 5h8l-2 5 2 5H8']
                ]; ?>
                <?php foreach ($links as [$label, $href, $path]): ?>
                    <a href="<?= $href ?>" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-200 hover:text-primary hover:bg-primary/10 dark:hover:bg-white/5 transition">
                        <span class="h-10 w-10 rounded-lg bg-slate-100 dark:bg-slate-700/70 flex items-center justify-center text-slate-400 group-hover:text-primary transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="<?= $path ?>" />
                            </svg>
                        </span>
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                <div class="bg-gradient-to-br from-slate-900 to-slate-700 dark:from-slate-700 dark:to-slate-600 text-white rounded-2xl p-4 shadow-soft">
                    <p class="text-sm text-white/70 mb-1">Agenda inteligente</p>
                    <p class="text-lg font-semibold mb-3">Optimiza tus citas</p>
                    <button class="w-full py-2 rounded-lg bg-white/20 hover:bg-white/30 transition text-sm font-medium">Ver demo</button>
                </div>
            </div>
        </aside>
        <div class="flex-1 flex flex-col">
            <header class="sticky top-0 z-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur border-b border-slate-100 dark:border-slate-800">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button class="md:hidden p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" @click="toggle()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Vista</p>
                            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($pageTitle) ?></h2>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden lg:flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 border border-transparent focus-within:border-primary">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" /></svg>
                            <input type="search" placeholder="Buscar paciente, cita, nota..." class="bg-transparent focus:outline-none text-sm w-64 placeholder:text-slate-400">
                        </div>
                        <button @click="toggleMode" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-200 hover:text-primary transition" :aria-pressed="darkMode">
                            <template x-if="!darkMode">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3.5a1 1 0 0 1 1 1V6a1 1 0 1 1-2 0V4.5a1 1 0 0 1 1-1zM10 12a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/><path fill-rule="evenodd" d="M10 18a1 1 0 0 1-1-1v-1.5a1 1 0 1 1 2 0V17a1 1 0 0 1-1 1zM4.964 15.036a1 1 0 0 1 0-1.414l1.06-1.06a1 1 0 1 1 1.415 1.414l-1.06 1.06a1 1 0 0 1-1.415 0zM3.5 10a1 1 0 0 1 1-1H6a1 1 0 1 1 0 2H4.5a1 1 0 0 1-1-1zm2.025-5.46a1 1 0 0 1 1.414 0l1.06 1.061a1 1 0 0 1-1.414 1.415L5.525 5.955a1 1 0 0 1 0-1.415zM14 4.5a1 1 0 0 1 1-1h.5a1 1 0 1 1 0 2H15a1 1 0 0 1-1-1zM16.975 14.54a1 1 0 0 1-1.414 0l-1.06-1.061a1 1 0 0 1 1.414-1.415l1.06 1.06a1 1 0 0 1 0 1.416zM15 10a1 1 0 0 1 1-1h1.5a1 1 0 1 1 0 2H16a1 1 0 0 1-1-1z" clip-rule="evenodd"/></svg>
                            </template>
                            <template x-if="darkMode">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 0 1 6.707 2.707a8 8 0 1 0 10.586 10.586z"/></svg>
                            </template>
                        </button>
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary to-accent text-white flex items-center justify-center font-semibold shadow-soft">DR</div>
                    </div>
                </div>
            </header>
            <main class="flex-1 p-6 bg-slate-50 dark:bg-slate-900">
                <div class="max-w-6xl mx-auto">
                    <?= $content ?>
                </div>
            </main>
            <footer class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 backdrop-blur">
                <div class="max-w-6xl mx-auto flex items-center justify-between text-sm text-slate-500 dark:text-slate-300">
                    <span><?= htmlspecialchars($appName) ?> · Experiencia premium para consultorios</span>
                    <span>Modo <span x-text="darkMode ? 'oscuro' : 'claro'"></span></span>
                </div>
            </footer>
        </div>

        <div class="md:hidden fixed inset-0 z-30 bg-black/40" x-show="open" x-transition.opacity @click="toggle()"></div>
        <aside class="md:hidden fixed inset-y-0 left-0 z-40 w-72 bg-white dark:bg-slate-900 shadow-soft transform" x-show="open" x-transition @click.away="open=false">
            <div class="p-6 flex items-center gap-3 border-b border-slate-100 dark:border-slate-800">
                <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-soft">
                    <span class="text-white text-xl font-semibold">C</span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-300">SaaS Médico</p>
                    <h1 class="text-xl font-semibold text-slate-900 dark:text-white">Clinio</h1>
                </div>
            </div>
            <nav class="p-4 space-y-1">
                <?php foreach ($links as [$label, $href, $path]): ?>
                    <a href="<?= $href ?>" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-200 hover:text-primary hover:bg-primary/10 dark:hover:bg-white/5 transition">
                        <span class="h-10 w-10 rounded-lg bg-slate-100 dark:bg-slate-700/70 flex items-center justify-center text-slate-400 group-hover:text-primary transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="<?= $path ?>" />
                            </svg>
                        </span>
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </aside>
    </div>
    <script>
        function sidebar() {
            return {
                open: false,
                toggle() { this.open = !this.open; }
            }
        }
        function themeController() {
            return {
                darkMode: false,
                toggleMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('clinio-theme', this.darkMode ? 'dark' : 'light');
                },
                init() {
                    const saved = localStorage.getItem('clinio-theme');
                    this.darkMode = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
                }
            }
        }
    </script>
</body>
</html>
