const page = document.querySelector('.page');
const tenantId = page?.dataset.tenant || 'default';
const calendarEl = document.getElementById('calendar');
const modal = document.getElementById('appointment-modal');
const form = document.getElementById('appointment-form');
const modalTitle = document.getElementById('modal-title');
const btnNew = document.getElementById('new-appointment');
const btnCancel = document.getElementById('cancel-modal');
const filterPatient = document.getElementById('filter-patient');
const filterStart = document.getElementById('filter-start');
const filterEnd = document.getElementById('filter-end');
const btnApplyFilters = document.getElementById('apply-filters');

const statusClass = (estado) => {
    switch ((estado || '').toLowerCase()) {
        case 'confirmada': return 'event-confirmada';
        case 'en sala': return 'event-en-sala';
        case 'atendida': return 'event-atendida';
        case 'reprogramada': return 'event-reprogramada';
        case 'no asistio': return 'event-no-asistio';
        default: return 'event-programada';
    }
};

let calendar;

function toggleModal(show, data = null) {
    modal.classList.toggle('active', show);
    if (show && data) {
        modalTitle.textContent = 'Editar cita';
        form.id.value = data.id;
        form.paciente_id.value = data.paciente_id;
        form.tipo.value = data.tipo || '';
        form.estado.value = (data.estado || '').toLowerCase();
        form.inicio.value = data.inicio?.replace('Z', '');
        form.fin.value = data.fin?.replace('Z', '');
        form.notas.value = data.notas || '';
    } else if (show) {
        modalTitle.textContent = 'Nueva cita';
        form.reset();
        form.id.value = '';
    }
}

function request(action, payload = {}) {
    const options = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ tenant_id: tenantId, action, ...payload })
    };
    return fetch('./AppointmentsController.php', options).then(async (res) => {
        const data = await res.json();
        if (!res.ok || !data.success) {
            throw new Error(data.message || 'Error en la solicitud');
        }
        return data;
    });
}

function fetchEvents(info, success, failure) {
    const params = {
        start: filterStart.value || info.startStr,
        end: filterEnd.value || info.endStr,
        paciente_id: filterPatient.value || null,
    };
    request('list', params)
        .then(({ data }) => success(data.map(toEvent)))
        .catch((err) => failure(err));
}

function toEvent(item) {
    return {
        id: item.id,
        title: `Paciente ${item.paciente_id} · ${item.tipo}`,
        start: item.inicio,
        end: item.fin,
        extendedProps: item,
        classNames: [statusClass(item.estado)],
    };
}

function renderCalendar() {
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        locale: 'es',
        themeSystem: 'standard',
        editable: true,
        droppable: true,
        selectable: true,
        eventResizableFromStart: true,
        events: fetchEvents,
        eventClick: (info) => toggleModal(true, info.event.extendedProps),
        dateClick: (info) => {
            form.inicio.value = `${info.dateStr}T09:00`;
            form.fin.value = `${info.dateStr}T09:30`;
            toggleModal(true);
        },
        eventDrop: handleMove,
        eventResize: handleMove,
    });
    calendar.render();
}

function handleMove(info) {
    const payload = {
        id: info.event.id,
        inicio: info.event.start.toISOString(),
        fin: info.event.end?.toISOString() || info.event.start.toISOString(),
    };
    request('move', payload)
        .then(() => calendar.refetchEvents())
        .catch((err) => {
            alert(err.message);
            info.revert();
        });
}

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const payload = {
        id: form.id.value || undefined,
        paciente_id: form.paciente_id.value,
        tipo: form.tipo.value,
        estado: form.estado.value,
        inicio: form.inicio.value,
        fin: form.fin.value,
        notas: form.notas.value,
    };
    const action = payload.id ? 'update' : 'create';
    request(action, payload)
        .then(() => {
            toggleModal(false);
            calendar.refetchEvents();
        })
        .catch((err) => alert(err.message));
});

btnNew?.addEventListener('click', () => toggleModal(true));
btnCancel?.addEventListener('click', () => toggleModal(false));
btnApplyFilters?.addEventListener('click', () => calendar.refetchEvents());

renderCalendar();
