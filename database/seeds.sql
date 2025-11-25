-- Datos de ejemplo para Clinio
-- Ejecutar después de aplicar schema.sql

WITH demo_tenant AS (
  INSERT INTO tenants (name, slug, contact_email, phone, address)
  VALUES ('Clinio Demo', 'demo', 'contacto@clinio.demo', '+1-555-0100', '123 Demo Street, City')
  RETURNING id
), doctor AS (
  INSERT INTO users (tenant_id, role, full_name, email, hashed_password, phone)
  SELECT id, 'doctor', 'Dra. Ana Torres', 'ana.torres@clinio.demo', 'hashedpassword-doctor', '+1-555-0101'
  FROM demo_tenant
  RETURNING id, tenant_id
), secretary AS (
  INSERT INTO users (tenant_id, role, full_name, email, hashed_password, phone)
  SELECT id, 'secretary', 'María López', 'maria.lopez@clinio.demo', 'hashedpassword-secretary', '+1-555-0102'
  FROM demo_tenant
  RETURNING id, tenant_id
), services AS (
  INSERT INTO services (tenant_id, name, description, default_duration_minutes, price, currency)
  SELECT dt.id, s.name, s.description, s.duration, s.price, s.currency
  FROM demo_tenant dt
  CROSS JOIN (VALUES
    ('Consulta General', 'Evaluación y diagnóstico general', 30, 50.00, 'USD'),
    ('Consulta de Control', 'Seguimiento de tratamiento', 20, 40.00, 'USD'),
    ('Limpieza Dental', 'Servicio básico de higiene dental', 45, 80.00, 'USD')
  ) AS s(name, description, duration, price, currency)
  RETURNING id, tenant_id, name
), patients AS (
  INSERT INTO patients (tenant_id, full_name, identifier, birth_date, gender, email, phone, address, primary_doctor_id)
  SELECT dt.id, p.full_name, p.identifier, p.birth_date, p.gender, p.email, p.phone, p.address, d.id
  FROM demo_tenant dt
  CROSS JOIN doctor d
  CROSS JOIN (VALUES
    ('Juan Pérez', 'PID-001', '1985-04-12', 'male', 'juan.perez@example.com', '+1-555-0201', '456 Elm Street'),
    ('Luisa García', 'PID-002', '1990-08-20', 'female', 'luisa.garcia@example.com', '+1-555-0202', '789 Oak Avenue'),
    ('Carlos Ruiz', 'PID-003', '1975-02-03', 'male', 'carlos.ruiz@example.com', '+1-555-0203', '321 Pine Road')
  ) AS p(full_name, identifier, birth_date, gender, email, phone, address)
  RETURNING id, tenant_id, full_name
), record_templates AS (
  INSERT INTO record_templates (tenant_id, name, version, description, fields_schema)
  SELECT dt.id, t.name, t.version, t.description, t.schema
  FROM demo_tenant dt
  CROSS JOIN (VALUES
    ('Historia Clínica', 1, 'Plantilla básica de historia clínica', '{"fields":[{"name":"motivo_consulta","type":"text"},{"name":"antecedentes","type":"text"},{"name":"plan","type":"text"}]}'),
    ('Evolución', 1, 'Seguimiento de evolución de paciente', '{"fields":[{"name":"sintomas","type":"text"},{"name":"diagnostico","type":"text"},{"name":"indicaciones","type":"text"}]}')
  ) AS t(name, version, description, schema)
  RETURNING id, tenant_id, name
), appointments AS (
  INSERT INTO appointments (tenant_id, patient_id, doctor_id, service_id, scheduled_at, duration_minutes, status, notes, created_by_user_id)
  SELECT dt.id, p.id, d.id, s.id, a.scheduled_at, a.duration, a.status, a.notes, sec.id
  FROM demo_tenant dt
  JOIN doctor d ON d.tenant_id = dt.id
  JOIN secretary sec ON sec.tenant_id = dt.id
  JOIN patients p ON p.tenant_id = dt.id
  JOIN services s ON s.tenant_id = dt.id AND s.name = 'Consulta General'
  CROSS JOIN (VALUES
    ('2024-07-10 10:00:00+00', 30, 'scheduled', 'Primera consulta de valoración'),
    ('2024-07-11 14:30:00+00', 20, 'scheduled', 'Control de tratamiento')
  ) AS a(scheduled_at, duration, status, notes)
  ORDER BY p.full_name
  LIMIT 2
  RETURNING id, tenant_id, patient_id
), payments AS (
  INSERT INTO payments (tenant_id, patient_id, appointment_id, amount_total, currency, method, status, paid_at, notes)
  SELECT dt.id, appt.patient_id, appt.id, pay.amount, 'USD', pay.method, pay.status, pay.paid_at, pay.notes
  FROM demo_tenant dt
  JOIN appointments appt ON appt.tenant_id = dt.id
  CROSS JOIN (VALUES
    (50.00, 'card', 'paid', '2024-07-10 11:00:00+00', 'Pago completo en tarjeta'),
    (40.00, 'cash', 'pending', NULL, 'Pago pendiente en efectivo')
  ) AS pay(amount, method, status, paid_at, notes)
  RETURNING id, tenant_id, status
), installments AS (
  INSERT INTO payment_installments (tenant_id, payment_id, due_date, amount, status, paid_at)
  SELECT dt.id, pay.id, due_date, amount, status, paid_at
  FROM demo_tenant dt
  JOIN payments pay ON pay.tenant_id = dt.id
  CROSS JOIN (VALUES
    (CURRENT_DATE + INTERVAL '7 day', 20.00, 'pending', NULL),
    (CURRENT_DATE + INTERVAL '14 day', 20.00, 'pending', NULL)
  ) AS inst(due_date, amount, status, paid_at)
  WHERE pay.status = 'pending'
  RETURNING id
), medical_records AS (
  INSERT INTO medical_records (tenant_id, patient_id, appointment_id, template_id, author_id, record_date, summary, content)
  SELECT dt.id, appt.patient_id, appt.id, rt.id, d.id, DATE(appt.scheduled_at), 'Valoración inicial',
         '{"motivo_consulta":"Dolor de cabeza","plan":"Analgesia y reposo"}'::jsonb
  FROM demo_tenant dt
  JOIN doctor d ON d.tenant_id = dt.id
  JOIN appointments appt ON appt.tenant_id = dt.id
  JOIN record_templates rt ON rt.tenant_id = dt.id AND rt.name = 'Historia Clínica'
  LIMIT 1
  RETURNING id
)
INSERT INTO notifications (tenant_id, user_id, channel, subject, body, status)
SELECT dt.id, sec.id, 'app', 'Nueva cita creada', 'Se registró una nueva cita para el paciente.', 'sent'
FROM demo_tenant dt
JOIN secretary sec ON sec.tenant_id = dt.id;
