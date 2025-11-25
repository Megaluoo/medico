<section class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    <div class="col-span-full bg-white/80 dark:bg-slate-800/70 rounded-3xl p-6 shadow-soft border border-slate-100 dark:border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Resumen</p>
                <h3 class="text-2xl font-semibold">Panel de control</h3>
            </div>
            <span class="px-3 py-1 text-xs rounded-full bg-primary/10 text-primary">Tiempo real</span>
        </div>
        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">Bienvenido a Clinio. El dashboard mostrará tus citas, métricas y actividad en vivo. Integraremos Chart.js y FullCalendar en esta sección.</p>
    </div>
    <?php
    $cards = [
        ['Pacientes activos', '1,240', '+12%'],
        ['Citas hoy', '36', '+3%'],
        ['Ingresos proyectados', '$12,400', '+8%'],
    ];
    ?>
    <?php foreach ($cards as [$title, $value, $badge]): ?>
        <div class="bg-white/80 dark:bg-slate-800/70 rounded-3xl p-6 shadow-soft border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-slate-500 dark:text-slate-400"><?= htmlspecialchars($title) ?></p>
                <span class="text-xs px-2 py-1 rounded-full bg-primary/10 text-primary font-medium"><?= htmlspecialchars($badge) ?></span>
            </div>
            <p class="text-3xl font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($value) ?></p>
            <div class="h-16 mt-4 bg-gradient-to-r from-primary/10 to-accent/10 rounded-2xl"></div>
        </div>
    <?php endforeach; ?>
</section>
