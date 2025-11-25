<?php
require_once __DIR__ . '/../app/RecordsController.php';
require_once __DIR__ . '/../templates/base_form.php';

$controller = new RecordsController();
$action = $_GET['action'] ?? 'list';
$patientIdFilter = $_GET['patient_id'] ?? '';
$message = '';

if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $record = $controller->store($_POST);
    $message = 'Historia guardada para ' . htmlspecialchars($record->patientName) . ' (' . htmlspecialchars($record->specialty) . ').';
    $action = 'list';
}

function render_header(string $title): void
{
    echo '<!DOCTYPE html>';
    echo '<html lang="es">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . htmlspecialchars($title) . '</title>';
    echo '<style>' . file_get_contents(__DIR__ . '/../public/styles.css') . '</style>';
    echo '</head><body>';
    echo '<header class="topbar">';
    echo '<div class="logo">Historia Clínica</div>';
    echo '<nav><a href="?action=list">Listado</a> <a href="?action=create">Nueva historia</a></nav>';
    echo '</header>';
    echo '<main class="container">';
}

function render_footer(): void
{
    echo '</main>';
    echo '<script>' . file_get_contents(__DIR__ . '/../public/scripts.js') . '</script>';
    echo '</body></html>';
}

if ($action === 'view') {
    $recordId = $_GET['id'] ?? '';
    $record = $controller->find($recordId);

    render_header('Ver historia');
    if (!$record) {
        echo '<p>No se encontró la historia solicitada.</p>';
        echo '<a class="button" href="?action=list">Volver</a>';
        render_footer();
        exit;
    }

    echo '<h1>Historia de ' . htmlspecialchars($record->patientName) . '</h1>';
    echo '<p><strong>ID paciente:</strong> ' . htmlspecialchars($record->patientId) . '</p>';
    echo '<p><strong>Especialidad:</strong> ' . htmlspecialchars($controller->specialties()[$record->specialty] ?? $record->specialty) . '</p>';
    echo '<p><strong>Fecha:</strong> ' . htmlspecialchars($record->createdAt) . '</p>';
    echo '<pre class="json-view">' . json_encode($record->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
    echo '<a class="button" href="?action=list">Volver al listado</a>';
    render_footer();
    exit;
}

if ($action === 'create') {
    $selectedSpecialty = $_GET['specialty'] ?? 'ginecologia';
    $sections = get_specialty_sections($selectedSpecialty);
    render_header('Nueva historia clínica');
    echo '<h1>Nueva historia clínica</h1>';
    echo '<form method="post" action="?action=store" class="card">';
    echo '<div class="split">';
    echo '<label><span>Paciente (ID)</span><input type="text" name="patient_id" required /></label>';
    echo '<label><span>Paciente (Nombre)</span><input type="text" name="patient_name" required /></label>';
    echo '</div>';
    echo '<label><span>Especialidad</span><select name="specialty" id="specialty-select">';
    foreach ($controller->specialties() as $key => $label) {
        $selected = $selectedSpecialty === $key ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($key) . '" ' . $selected . '>' . htmlspecialchars($label) . '</option>';
    }
    echo '</select></label>';

    echo '<div id="specialty-form">';
    render_sections($sections);
    echo '</div>';

    echo '<div class="custom-fields">';
    echo '<div class="custom-fields-header">Campos personalizados</div>';
    echo '<div id="custom-fields-container"></div>';
    echo '<button type="button" class="secondary" id="add-custom-field">Agregar campo</button>';
    echo '</div>';

    echo '<button type="submit" class="primary">Guardar historia</button>';
    echo '</form>';
    echo '<p class="muted">Usa campos personalizados para capturar información específica de cada paciente o especialidad.</p>';
    render_footer();
    exit;
}

render_header('Listado de historias');

echo '<h1>Historias clínicas</h1>';
if ($message) {
    echo '<div class="alert">' . $message . '</div>';
}

echo '<form class="card filters" method="get">';
    echo '<input type="hidden" name="action" value="list" />';
    echo '<label><span>Filtrar por ID de paciente</span><input type="text" name="patient_id" value="' . htmlspecialchars($patientIdFilter) . '" /></label>';
    echo '<button class="secondary" type="submit">Aplicar filtro</button>';
    echo '<a class="link" href="?action=create">Crear nueva historia</a>';
    echo '</form>';

$records = $controller->all($patientIdFilter ?: null);

if (empty($records)) {
    echo '<p>No hay historias registradas.</p>';
    render_footer();
    exit;
}

echo '<div class="record-list">';
foreach ($records as $record) {
    echo '<article class="card record">';
    echo '<div class="record-header">';
    echo '<div><div class="record-title">' . htmlspecialchars($record->patientName) . '</div>'; 
    echo '<div class="record-meta">ID: ' . htmlspecialchars($record->patientId) . ' · ' . htmlspecialchars($controller->specialties()[$record->specialty] ?? $record->specialty) . '</div></div>';
    echo '<div class="record-date">' . htmlspecialchars(date('d M Y', strtotime($record->createdAt))) . '</div>';
    echo '</div>';
    echo '<pre class="json-snippet">' . htmlspecialchars(substr(json_encode($record->data, JSON_UNESCAPED_UNICODE), 0, 140)) . '...</pre>';
    echo '<a class="button" href="?action=view&id=' . htmlspecialchars($record->id) . '">Ver historia</a>';
    echo '</article>';
}

echo '</div>';

render_footer();
