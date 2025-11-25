<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500">Histórico</p>
            <h2 class="text-2xl font-bold text-slate-800">Lista de documentos preparados</h2>
        </div>
        <a href="/certificates/create" class="text-sm text-indigo-600 font-semibold hover:underline">Nuevo documento</a>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">Paciente</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600">Ruta prevista</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $doc): ?>
                        <tr>
                            <td class="px-4 py-3 text-sm text-slate-700">#<?php echo htmlspecialchars($doc['id']); ?></td>
                            <td class="px-4 py-3 text-sm text-slate-700"><?php echo htmlspecialchars($doc['patient']); ?></td>
                            <td class="px-4 py-3 text-sm text-slate-700 capitalize"><?php echo htmlspecialchars($doc['type']); ?></td>
                            <td class="px-4 py-3 text-sm text-slate-700"><?php echo htmlspecialchars($doc['created_at']); ?></td>
                            <td class="px-4 py-3 text-sm text-slate-600 font-mono"><?php echo htmlspecialchars($doc['path']); ?></td>
                            <td class="px-4 py-3 text-sm text-right">
                                <a href="/certificates/download?id=<?php echo urlencode($doc['id']); ?>" class="text-indigo-600 hover:text-indigo-500 font-medium">Descargar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Sin documentos preparados aún.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
