<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? null;
$tenantId = $input['tenant_id'] ?? 'default';

$file = __DIR__ . '/data/appointments.json';
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

function loadAppointments(string $file): array
{
    $raw = file_get_contents($file);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function saveAppointments(string $file, array $data): bool
{
    $fp = fopen($file, 'c+');
    if (!$fp) {
        return false;
    }
    flock($fp, LOCK_EX);
    ftruncate($fp, 0);
    fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return true;
}

function respond(bool $success, string $message, $data = null, int $code = 200): void
{
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
    ]);
    exit;
}

function filterAppointments(array $appointments, string $tenantId, ?string $start, ?string $end, ?string $patientId): array
{
    return array_values(array_filter($appointments, function ($appt) use ($tenantId, $start, $end, $patientId) {
        if (($appt['tenant_id'] ?? 'default') !== $tenantId) {
            return false;
        }
        if ($patientId && ($appt['paciente_id'] ?? '') !== $patientId) {
            return false;
        }
        if ($start && strtotime($appt['inicio']) < strtotime($start)) {
            return false;
        }
        if ($end && strtotime($appt['fin']) > strtotime($end . ' 23:59:59')) {
            return false;
        }
        return true;
    }));
}

function validateAppointment(array $input): array
{
    $required = ['paciente_id', 'inicio', 'fin'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            respond(false, "El campo {$field} es requerido", null, 422);
        }
    }

    $estado = strtolower($input['estado'] ?? 'programada');
    $allowedStates = ['programada', 'confirmada', 'en sala', 'atendida', 'reprogramada', 'no asistio'];
    if (!in_array($estado, $allowedStates, true)) {
        respond(false, 'Estado inválido', null, 422);
    }

    $inicio = date(DATE_ATOM, strtotime($input['inicio']));
    $fin = date(DATE_ATOM, strtotime($input['fin']));
    if ($fin <= $inicio) {
        respond(false, 'La fecha de fin debe ser mayor al inicio', null, 422);
    }

    return [
        'paciente_id' => (string) $input['paciente_id'],
        'tipo' => trim($input['tipo'] ?? 'Consulta'),
        'estado' => $estado,
        'inicio' => $inicio,
        'fin' => $fin,
        'notas' => trim($input['notas'] ?? ''),
    ];
}

$appointments = loadAppointments($file);

switch ($action) {
    case 'list':
        $start = $input['start'] ?? null;
        $end = $input['end'] ?? null;
        $patient = $input['paciente_id'] ?? null;
        $filtered = filterAppointments($appointments, $tenantId, $start, $end, $patient);
        respond(true, 'Listado de citas', $filtered);
        break;

    case 'create':
        $data = validateAppointment($input);
        $data['tenant_id'] = $tenantId;
        $nextId = empty($appointments) ? 1 : (max(array_column($appointments, 'id')) + 1);
        $data['id'] = $nextId;
        $appointments[] = $data;
        saveAppointments($file, $appointments);
        respond(true, 'Cita creada', $data, 201);
        break;

    case 'update':
        if (empty($input['id'])) {
            respond(false, 'ID requerido', null, 422);
        }
        $data = validateAppointment($input);
        $updated = false;
        foreach ($appointments as &$appt) {
            if ((string)($appt['id'] ?? '') === (string)$input['id'] && ($appt['tenant_id'] ?? 'default') === $tenantId) {
                $appt = array_merge($appt, $data);
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            respond(false, 'Cita no encontrada', null, 404);
        }
        saveAppointments($file, $appointments);
        respond(true, 'Cita actualizada', $data);
        break;

    case 'move':
        if (empty($input['id']) || empty($input['inicio']) || empty($input['fin'])) {
            respond(false, 'Datos insuficientes', null, 422);
        }
        $inicio = date(DATE_ATOM, strtotime($input['inicio']));
        $fin = date(DATE_ATOM, strtotime($input['fin']));
        $moved = false;
        foreach ($appointments as &$appt) {
            if ((string)$appt['id'] === (string)$input['id'] && ($appt['tenant_id'] ?? 'default') === $tenantId) {
                $appt['inicio'] = $inicio;
                $appt['fin'] = $fin;
                $moved = true;
                break;
            }
        }
        if (!$moved) {
            respond(false, 'Cita no encontrada', null, 404);
        }
        saveAppointments($file, $appointments);
        respond(true, 'Cita movida');
        break;

    case 'status':
        if (empty($input['id']) || empty($input['estado'])) {
            respond(false, 'Datos insuficientes', null, 422);
        }
        $estado = strtolower($input['estado']);
        $allowedStates = ['programada', 'confirmada', 'en sala', 'atendida', 'reprogramada', 'no asistio'];
        if (!in_array($estado, $allowedStates, true)) {
            respond(false, 'Estado inválido', null, 422);
        }
        $updated = false;
        foreach ($appointments as &$appt) {
            if ((string)$appt['id'] === (string)$input['id'] && ($appt['tenant_id'] ?? 'default') === $tenantId) {
                $appt['estado'] = $estado;
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            respond(false, 'Cita no encontrada', null, 404);
        }
        saveAppointments($file, $appointments);
        respond(true, 'Estado actualizado');
        break;

    default:
        respond(false, 'Acción no soportada', null, 400);
}
