<?php
$patient = $patient ?? null;
?>
<div class="grid two-cols">
    <label class="field">
        <span>Nombre *</span>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($patient['first_name'] ?? ''); ?>" required>
    </label>
    <label class="field">
        <span>Apellido *</span>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($patient['last_name'] ?? ''); ?>" required>
    </label>
</div>
<div class="grid two-cols">
    <label class="field">
        <span>Fecha de nacimiento *</span>
        <input type="date" name="birth_date" value="<?php echo htmlspecialchars($patient['birth_date'] ?? ''); ?>" required>
    </label>
    <label class="field">
        <span>Sexo *</span>
        <select name="sex" required>
            <option value="" disabled <?php echo empty($patient['sex']) ? 'selected' : ''; ?>>Selecciona...</option>
            <option value="F" <?php echo (($patient['sex'] ?? '') === 'F') ? 'selected' : ''; ?>>Femenino</option>
            <option value="M" <?php echo (($patient['sex'] ?? '') === 'M') ? 'selected' : ''; ?>>Masculino</option>
            <option value="O" <?php echo (($patient['sex'] ?? '') === 'O') ? 'selected' : ''; ?>>Otro</option>
        </select>
    </label>
</div>
<div class="grid two-cols">
    <label class="field">
        <span>Teléfono</span>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone'] ?? ''); ?>">
    </label>
    <label class="field">
        <span>Email</span>
        <input type="email" name="email" value="<?php echo htmlspecialchars($patient['email'] ?? ''); ?>">
    </label>
</div>
<label class="field">
    <span>Dirección</span>
    <input type="text" name="address" value="<?php echo htmlspecialchars($patient['address'] ?? ''); ?>">
</label>
<label class="field">
    <span>Alergias</span>
    <textarea name="allergies" rows="2" placeholder="Ej. Penicilina, polen..."><?php echo htmlspecialchars($patient['allergies'] ?? ''); ?></textarea>
</label>
<label class="field">
    <span>Antecedentes</span>
    <textarea name="history" rows="3" placeholder="Cirugías, diagnósticos previos..."><?php echo htmlspecialchars($patient['history'] ?? ''); ?></textarea>
</label>
<label class="field">
    <span>Observaciones</span>
    <textarea name="notes" rows="3" placeholder="Notas adicionales"><?php echo htmlspecialchars($patient['notes'] ?? ''); ?></textarea>
</label>
<label class="field">
    <span>Archivos del paciente</span>
    <input type="file" name="attachments[]" multiple>
    <small class="hint">Se guardan en /storage/uploads/patients/</small>
</label>
