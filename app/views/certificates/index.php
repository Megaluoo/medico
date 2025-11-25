<div class="max-w-5xl mx-auto">
    <header class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Módulo de Recetas y Certificados</h2>
            <p class="text-slate-600">Redacta, guarda y descarga documentos médicos en un solo lugar.</p>
        </div>
        <a href="/certificates/create" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-500">Nueva creación</a>
    </header>

    <?php if (!empty($status) && $status === 'preview' && !empty($html)): ?>
        <section class="bg-white shadow rounded-xl p-6 border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-slate-500">Documento listo para vista previa</p>
                    <h3 class="text-xl font-semibold text-slate-800">Tipo: <?php echo htmlspecialchars($document_type ?? ''); ?></h3>
                </div>
                <span class="px-3 py-1 text-sm rounded-full bg-emerald-50 text-emerald-700">Ruta prevista: <?php echo htmlspecialchars($storage ?? ''); ?></span>
            </div>
            <div class="prose max-w-none bg-slate-50 rounded-lg p-4 overflow-auto" style="max-height: 500px;">
                <?php echo $html; ?>
            </div>
            <div class="mt-4 text-sm text-slate-500">
                El PDF se preparó solo en memoria. No se creó ningún archivo físico.
            </div>
        </section>
    <?php elseif (!empty($status) && $status === 'download'): ?>
        <div class="bg-indigo-50 border border-indigo-100 text-indigo-700 p-4 rounded-lg">
            <?php echo htmlspecialchars($message ?? 'Descarga lista.'); ?>
        </div>
    <?php else: ?>
        <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-6 rounded-xl shadow border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800">Recetas rápidas</h3>
                <p class="text-slate-600 mt-2">Plantillas con indicaciones, posologías y firmas digitales.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800">Constancias y reposos</h3>
                <p class="text-slate-600 mt-2">Genera constancias laborales, reposos médicos y certificados de asistencia.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800">Informes médicos</h3>
                <p class="text-slate-600 mt-2">Prepara informes extensos con estructura profesional y secciones personalizables.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800">Descarga controlada</h3>
                <p class="text-slate-600 mt-2">Rutas de almacenamiento definidas sin generar archivos binarios hasta su despliegue.</p>
            </div>
        </section>
    <?php endif; ?>
</div>
