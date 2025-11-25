<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recetas y Certificados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-white shadow-sm border-r border-slate-200">
            <div class="p-4 border-b border-slate-200">
                <h1 class="text-lg font-semibold">Panel Médico</h1>
                <p class="text-sm text-slate-500">Gestión de documentos</p>
            </div>
            <nav class="p-4 space-y-2">
                <a href="/certificates" class="flex items-center px-3 py-2 rounded-lg bg-indigo-50 text-indigo-600 font-medium hover:bg-indigo-100">
                    <span class="ml-2">Recetas y Certificados</span>
                </a>
                <a href="/certificates/create" class="block px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">Crear documento</a>
                <a href="/certificates/list" class="block px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-100">Lista de documentos</a>
            </nav>
        </aside>
        <main class="flex-1 p-8">
            <?php include $contentView; ?>
        </main>
    </div>
</body>
</html>
