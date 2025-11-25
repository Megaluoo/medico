<?php
$tenantId = $_GET['tenant'] ?? 'default';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-ydI+eI5B2S/bRMyCV2wE8p4nH3UX0DpD+s/COM24kTx5cDIeEJD7BqXc9EjoP6KDAdAm8YGtS+wGGyYlJ3L3SQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --background: #f5f7fb;
            --panel: #ffffff;
            --accent: #6ca0f6;
            --accent-strong: #4b7be5;
            --text-primary: #1c1c1e;
            --text-secondary: #6e6e73;
            --shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            --border: #e5e5ea;
            --success: #34c759;
            --warning: #ff9f0a;
            --danger: #ff3b30;
            --info: #5ac8fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 50%, #e5e9f0 100%);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
        }

        .page {
            max-width: 1200px;
            margin: 24px auto 48px;
            padding: 0 16px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            gap: 12px;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        .filters {
            background: var(--panel);
            padding: 16px;
            border-radius: 18px;
            box-shadow: var(--shadow);
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filters label {
            display: flex;
            flex-direction: column;
            font-size: 12px;
            color: var(--text-secondary);
            gap: 6px;
        }

        .filters input,
        .filters select,
        .filters button,
        .filters textarea {
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 10px 12px;
            background: #f8f9fc;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
            color: var(--text-primary);
            outline: none;
            min-width: 140px;
            transition: border 0.2s, box-shadow 0.2s, transform 0.1s;
        }

        .filters input:focus,
        .filters select:focus,
        .filters textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(108, 160, 246, 0.2);
            background: #fff;
        }

        .filters button {
            background: linear-gradient(180deg, var(--accent) 0%, var(--accent-strong) 100%);
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .filters button:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .card {
            background: var(--panel);
            border-radius: 18px;
            box-shadow: var(--shadow);
            padding: 12px;
        }

        .fc {
            --fc-border-color: var(--border);
            --fc-daygrid-event-dot-width: 10px;
            --fc-event-border-color: transparent;
            --fc-today-bg-color: #e9f1ff;
        }

        .fc-toolbar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .fc-button-primary {
            background: linear-gradient(180deg, var(--accent) 0%, var(--accent-strong) 100%);
            border: none;
            border-radius: 10px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4);
        }

        .fc-button-primary:hover {
            background: var(--accent-strong);
        }

        .fc-event {
            border-radius: 12px;
            padding: 4px 8px;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            color: #fff;
            font-weight: 600;
        }

        .event-programada { background: linear-gradient(135deg, #6ca0f6, #4b7be5); }
        .event-confirmada { background: linear-gradient(135deg, #34c759, #2fab4f); }
        .event-en-sala { background: linear-gradient(135deg, #ff9f0a, #d98200); }
        .event-atendida { background: linear-gradient(135deg, #5ac8fa, #35a4d4); }
        .event-reprogramada { background: linear-gradient(135deg, #a970ff, #7b4fe2); }
        .event-no-asistio { background: linear-gradient(135deg, #ff3b30, #e0281c); }

        .legend {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-secondary);
            background: #fff;
            padding: 6px 10px;
            border-radius: 12px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.6), var(--shadow);
            border: 1px solid #f0f0f5;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            z-index: 99;
        }

        .modal.active { display: flex; }

        .modal-card {
            background: var(--panel);
            border-radius: 18px;
            padding: 20px;
            width: min(520px, 100%);
            box-shadow: var(--shadow);
        }

        .modal-card h3 { margin: 0 0 12px; }
        .modal-card form { display: grid; gap: 12px; }
        .modal-card textarea { min-height: 80px; }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 6px;
        }

        .secondary-btn {
            background: #f1f2f6 !important;
            color: var(--text-primary) !important;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            background: #f1f3f8;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <div class="page" data-tenant="<?php echo htmlspecialchars($tenantId, ENT_QUOTES); ?>">
        <div class="header">
            <div class="title">Citas</div>
            <div class="status-pill">
                <i class="fa-regular fa-calendar-check"></i>
                FullCalendar</div>
        </div>
        <div class="filters">
            <label>
                Paciente ID
                <input type="text" id="filter-patient" placeholder="Ej. 123">
            </label>
            <label>
                Fecha desde
                <input type="date" id="filter-start">
            </label>
            <label>
                Fecha hasta
                <input type="date" id="filter-end">
            </label>
            <button id="apply-filters"><i class="fa-solid fa-filter"></i> Filtrar</button>
            <button id="new-appointment"><i class="fa-solid fa-plus"></i> Nueva cita</button>
        </div>

        <div class="card" style="margin-top:14px;">
            <div id="calendar"></div>
            <div class="legend">
                <div class="legend-item"><span class="legend-dot event-programada"></span> Programada</div>
                <div class="legend-item"><span class="legend-dot event-confirmada"></span> Confirmada</div>
                <div class="legend-item"><span class="legend-dot event-en-sala"></span> En sala</div>
                <div class="legend-item"><span class="legend-dot event-atendida"></span> Atendida</div>
                <div class="legend-item"><span class="legend-dot event-reprogramada"></span> Reprogramada</div>
                <div class="legend-item"><span class="legend-dot event-no-asistio"></span> No asistió</div>
            </div>
        </div>
    </div>

    <div class="modal" id="appointment-modal">
        <div class="modal-card">
            <h3 id="modal-title">Nueva cita</h3>
            <form id="appointment-form">
                <input type="hidden" name="id" id="appointment-id">
                <label>Paciente ID
                    <input type="text" name="paciente_id" id="paciente-id" required>
                </label>
                <label>Tipo de cita
                    <input type="text" name="tipo" id="tipo" placeholder="Consulta, control..." required>
                </label>
                <label>Estado
                    <select name="estado" id="estado">
                        <option value="programada">Programada</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="en sala">En sala</option>
                        <option value="atendida">Atendida</option>
                        <option value="reprogramada">Reprogramada</option>
                        <option value="no asistio">No asistió</option>
                    </select>
                </label>
                <label>Inicio
                    <input type="datetime-local" name="inicio" id="inicio" required>
                </label>
                <label>Fin
                    <input type="datetime-local" name="fin" id="fin" required>
                </label>
                <label>Notas
                    <textarea name="notas" id="notas" placeholder="Observaciones"></textarea>
                </label>
                <div class="modal-actions">
                    <button type="button" class="secondary-btn" id="cancel-modal">Cancelar</button>
                    <button type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="./AppointmentsController.js"></script>
</body>
</html>
