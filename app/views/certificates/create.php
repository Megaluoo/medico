<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500">Nuevo documento</p>
            <h2 class="text-2xl font-bold text-slate-800">Redactar receta o certificado</h2>
        </div>
        <a href="/certificates" class="text-sm text-indigo-600 font-semibold hover:underline">Volver al panel</a>
    </div>

    <form action="/certificates/generate" method="POST" class="bg-white rounded-xl shadow border border-slate-100 p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Paciente</span>
                <input type="text" name="patient" class="mt-1 w-full rounded-lg border-slate-200 focus:ring-2 focus:ring-indigo-500" placeholder="Nombre del paciente" required>
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Médico</span>
                <input type="text" name="doctor" class="mt-1 w-full rounded-lg border-slate-200 focus:ring-2 focus:ring-indigo-500" placeholder="Nombre del médico" required>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <label class="block md:col-span-2">
                <span class="text-sm font-medium text-slate-700">Notas</span>
                <input type="text" name="notes" class="mt-1 w-full rounded-lg border-slate-200 focus:ring-2 focus:ring-indigo-500" placeholder="Observaciones adicionales">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-slate-700">Fecha</span>
                <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" class="mt-1 w-full rounded-lg border-slate-200 focus:ring-2 focus:ring-indigo-500">
            </label>
        </div>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Tipo de documento</span>
            <select name="type" class="mt-1 w-full rounded-lg border-slate-200 focus:ring-2 focus:ring-indigo-500">
                <option value="receta">Receta médica</option>
                <option value="constancia">Constancia</option>
                <option value="reposo">Reposo</option>
                <option value="informe">Informe</option>
            </select>
        </label>
        <label class="block">
            <span class="text-sm font-medium text-slate-700">Contenido</span>
            <textarea name="content" rows="8" class="mt-1 w-full rounded-lg border-slate-200 focus:ring-2 focus:ring-indigo-500" placeholder="Indicaciones, diagnósticos o detalles del informe" required></textarea>
        </label>
        <div class="flex items-center justify-end space-x-3">
            <a href="/certificates/list" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">Ver lista</a>
            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-500">Generar HTML para PDF</button>
        </div>
        <p class="text-sm text-slate-500">La generación real del PDF queda pendiente. No se crean archivos binarios en esta fase.</p>
    </form>
</div>
