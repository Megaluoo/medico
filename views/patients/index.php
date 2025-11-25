<?php $pageTitle = 'Pacientes'; ?>
<section class="card">
    <div class="card-header">
        <div>
            <p class="eyebrow">Panel principal</p>
            <h1>Pacientes</h1>
            <p class="subtitle">Visualiza, filtra y gestiona pacientes por tenant.</p>
        </div>
        <button class="btn primary" id="openModal">+ Nuevo paciente</button>
    </div>

    <form class="filters" method="get" action="index.php">
        <input type="hidden" name="tenant_id" value="<?php echo htmlspecialchars((string) $tenantId); ?>">
        <label class="field inline">
            <span>Búsqueda</span>
            <input type="search" name="q" value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" placeholder="Nombre, email o teléfono">
        </label>
        <label class="field inline">
            <span>Sexo</span>
            <select name="sex">
                <option value="">Todos</option>
                <option value="F" <?php echo (($filters['sex'] ?? '') === 'F') ? 'selected' : ''; ?>>Femenino</option>
                <option value="M" <?php echo (($filters['sex'] ?? '') === 'M') ? 'selected' : ''; ?>>Masculino</option>
                <option value="O" <?php echo (($filters['sex'] ?? '') === 'O') ? 'selected' : ''; ?>>Otro</option>
            </select>
        </label>
        <button class="btn ghost" type="submit">Aplicar</button>
    </form>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha nacimiento</th>
                    <th>Sexo</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($patients)): ?>
                    <tr>
                        <td colspan="6" class="empty">No hay pacientes para este tenant.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td>
                                <div class="avatar-row">
                                    <div class="avatar"><?php echo strtoupper(substr($patient['first_name'], 0, 1) . substr($patient['last_name'], 0, 1)); ?></div>
                                    <div>
                                        <div class="title"><?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></div>
                                        <div class="muted">Tenant #<?php echo htmlspecialchars((string) $patient['tenant_id']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($patient['birth_date']); ?></td>
                            <td><?php echo htmlspecialchars($patient['sex']); ?></td>
                            <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                            <td><?php echo htmlspecialchars($patient['email']); ?></td>
                            <td class="actions">
                                <a class="btn ghost" href="index.php?action=show&id=<?php echo $patient['id']; ?>&tenant_id=<?php echo $tenantId; ?>">Ver</a>
                                <a class="btn ghost" href="index.php?action=edit&id=<?php echo $patient['id']; ?>&tenant_id=<?php echo $tenantId; ?>">Editar</a>
                                <form method="post" action="index.php?action=destroy&tenant_id=<?php echo $tenantId; ?>" onsubmit="return confirm('¿Eliminar este paciente?');">
                                    <input type="hidden" name="id" value="<?php echo $patient['id']; ?>">
                                    <button class="btn danger" type="submit">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div class="modal" id="createModal" aria-hidden="true">
    <div class="modal-backdrop" id="closeModal"></div>
    <div class="modal-content">
        <header class="modal-header">
            <div>
                <p class="eyebrow">Nuevo registro</p>
                <h3>Crear paciente</h3>
            </div>
            <button class="btn ghost" id="closeModalBtn">Cerrar</button>
        </header>
        <form action="index.php?action=store&tenant_id=<?php echo $tenantId; ?>" method="post" enctype="multipart/form-data">
            <?php include __DIR__ . '/_form.php'; ?>
            <div class="form-actions">
                <button type="button" class="btn ghost" id="closeModalBtn2">Cancelar</button>
                <button type="submit" class="btn primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('createModal');
    const openModal = document.getElementById('openModal');
    const closeButtons = [document.getElementById('closeModal'), document.getElementById('closeModalBtn'), document.getElementById('closeModalBtn2')];

    if (openModal && modal) {
        openModal.addEventListener('click', () => {
            modal.setAttribute('aria-hidden', 'false');
        });
    }

    closeButtons.forEach(button => {
        if (button) {
            button.addEventListener('click', () => modal.setAttribute('aria-hidden', 'true'));
        }
    });
</script>
