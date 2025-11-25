-- Clinio multi-tenant database schema
-- PostgreSQL dialect

CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE OR REPLACE FUNCTION set_updated_at()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Tenants
CREATE TABLE tenants (
  id          BIGSERIAL PRIMARY KEY,
  name        TEXT        NOT NULL,
  slug        TEXT        NOT NULL UNIQUE,
  contact_email TEXT,
  phone       TEXT,
  address     TEXT,
  created_at  TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at  TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE UNIQUE INDEX tenants_name_idx ON tenants (LOWER(name));

-- Users
CREATE TABLE users (
  id           BIGSERIAL PRIMARY KEY,
  tenant_id    BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  role         TEXT        NOT NULL CHECK (role IN ('doctor','secretary','admin','nurse','assistant')),
  full_name    TEXT        NOT NULL,
  email        TEXT        NOT NULL,
  hashed_password TEXT     NOT NULL,
  phone        TEXT,
  is_active    BOOLEAN     NOT NULL DEFAULT TRUE,
  created_at   TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at   TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  UNIQUE (tenant_id, email)
);
CREATE INDEX users_tenant_idx ON users (tenant_id);
CREATE INDEX users_role_idx ON users (tenant_id, role);

-- Patients
CREATE TABLE patients (
  id              BIGSERIAL PRIMARY KEY,
  tenant_id       BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  full_name       TEXT        NOT NULL,
  identifier      TEXT,
  birth_date      DATE,
  gender          TEXT        CHECK (gender IN ('male','female','other','unknown')),
  email           TEXT,
  phone           TEXT,
  address         TEXT,
  primary_doctor_id BIGINT,
  created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  UNIQUE (tenant_id, identifier),
  CONSTRAINT patients_primary_doctor_fk FOREIGN KEY (tenant_id, primary_doctor_id)
    REFERENCES users (tenant_id, id) ON DELETE SET NULL
);
CREATE INDEX patients_tenant_idx ON patients (tenant_id);
CREATE INDEX patients_doctor_idx ON patients (tenant_id, primary_doctor_id);

-- Services
CREATE TABLE services (
  id                 BIGSERIAL PRIMARY KEY,
  tenant_id          BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  name               TEXT        NOT NULL,
  description        TEXT,
  default_duration_minutes INTEGER,
  price              NUMERIC(10,2) NOT NULL DEFAULT 0,
  currency           CHAR(3)     NOT NULL DEFAULT 'USD',
  is_active          BOOLEAN     NOT NULL DEFAULT TRUE,
  created_at         TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at         TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  UNIQUE (tenant_id, name)
);
CREATE INDEX services_tenant_idx ON services (tenant_id);

-- Appointments
CREATE TABLE appointments (
  id                 BIGSERIAL PRIMARY KEY,
  tenant_id          BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  patient_id         BIGINT      NOT NULL,
  doctor_id          BIGINT      NOT NULL,
  service_id         BIGINT,
  scheduled_at       TIMESTAMPTZ NOT NULL,
  duration_minutes   INTEGER     NOT NULL DEFAULT 30,
  status             TEXT        NOT NULL DEFAULT 'scheduled'
                    CHECK (status IN ('scheduled','completed','cancelled','no_show','rescheduled')),
  notes              TEXT,
  created_by_user_id BIGINT,
  created_at         TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at         TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  CONSTRAINT appointments_patient_fk FOREIGN KEY (tenant_id, patient_id)
    REFERENCES patients (tenant_id, id) ON DELETE CASCADE,
  CONSTRAINT appointments_doctor_fk FOREIGN KEY (tenant_id, doctor_id)
    REFERENCES users (tenant_id, id) ON DELETE RESTRICT,
  CONSTRAINT appointments_service_fk FOREIGN KEY (tenant_id, service_id)
    REFERENCES services (tenant_id, id) ON DELETE SET NULL,
  CONSTRAINT appointments_creator_fk FOREIGN KEY (tenant_id, created_by_user_id)
    REFERENCES users (tenant_id, id) ON DELETE SET NULL
);
CREATE INDEX appointments_tenant_idx ON appointments (tenant_id);
CREATE INDEX appointments_scheduled_idx ON appointments (tenant_id, scheduled_at);
CREATE INDEX appointments_status_idx ON appointments (tenant_id, status);

-- Payments
CREATE TABLE payments (
  id             BIGSERIAL PRIMARY KEY,
  tenant_id      BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  patient_id     BIGINT      NOT NULL,
  appointment_id BIGINT,
  amount_total   NUMERIC(12,2) NOT NULL,
  currency       CHAR(3)     NOT NULL DEFAULT 'USD',
  method         TEXT,
  status         TEXT        NOT NULL DEFAULT 'pending'
                CHECK (status IN ('pending','partial','paid','failed','refunded')),
  paid_at        TIMESTAMPTZ,
  notes          TEXT,
  created_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  CONSTRAINT payments_patient_fk FOREIGN KEY (tenant_id, patient_id)
    REFERENCES patients (tenant_id, id) ON DELETE CASCADE,
  CONSTRAINT payments_appointment_fk FOREIGN KEY (tenant_id, appointment_id)
    REFERENCES appointments (tenant_id, id) ON DELETE SET NULL
);
CREATE INDEX payments_tenant_idx ON payments (tenant_id);
CREATE INDEX payments_status_idx ON payments (tenant_id, status);

-- Payment installments
CREATE TABLE payment_installments (
  id             BIGSERIAL PRIMARY KEY,
  tenant_id      BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  payment_id     BIGINT      NOT NULL,
  due_date       DATE        NOT NULL,
  amount         NUMERIC(12,2) NOT NULL,
  status         TEXT        NOT NULL DEFAULT 'pending'
                CHECK (status IN ('pending','paid','overdue','cancelled')),
  paid_at        TIMESTAMPTZ,
  created_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  CONSTRAINT payment_installments_payment_fk FOREIGN KEY (tenant_id, payment_id)
    REFERENCES payments (tenant_id, id) ON DELETE CASCADE
);
CREATE INDEX payment_installments_tenant_idx ON payment_installments (tenant_id);
CREATE INDEX payment_installments_status_idx ON payment_installments (tenant_id, status);

-- Record templates
CREATE TABLE record_templates (
  id             BIGSERIAL PRIMARY KEY,
  tenant_id      BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  name           TEXT        NOT NULL,
  version        INTEGER     NOT NULL DEFAULT 1,
  description    TEXT,
  fields_schema  JSONB,
  is_active      BOOLEAN     NOT NULL DEFAULT TRUE,
  created_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  UNIQUE (tenant_id, name, version)
);
CREATE INDEX record_templates_tenant_idx ON record_templates (tenant_id);
CREATE INDEX record_templates_active_idx ON record_templates (tenant_id, is_active);

-- Medical records
CREATE TABLE medical_records (
  id             BIGSERIAL PRIMARY KEY,
  tenant_id      BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  patient_id     BIGINT      NOT NULL,
  appointment_id BIGINT,
  template_id    BIGINT,
  author_id      BIGINT,
  record_date    DATE        NOT NULL DEFAULT CURRENT_DATE,
  summary        TEXT,
  content        JSONB,
  created_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  CONSTRAINT medical_records_patient_fk FOREIGN KEY (tenant_id, patient_id)
    REFERENCES patients (tenant_id, id) ON DELETE CASCADE,
  CONSTRAINT medical_records_appointment_fk FOREIGN KEY (tenant_id, appointment_id)
    REFERENCES appointments (tenant_id, id) ON DELETE SET NULL,
  CONSTRAINT medical_records_template_fk FOREIGN KEY (tenant_id, template_id)
    REFERENCES record_templates (tenant_id, id) ON DELETE SET NULL,
  CONSTRAINT medical_records_author_fk FOREIGN KEY (tenant_id, author_id)
    REFERENCES users (tenant_id, id) ON DELETE SET NULL
);
CREATE INDEX medical_records_tenant_idx ON medical_records (tenant_id);
CREATE INDEX medical_records_patient_idx ON medical_records (tenant_id, patient_id);

-- Notifications (optional)
CREATE TABLE notifications (
  id             BIGSERIAL PRIMARY KEY,
  tenant_id      BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  user_id        BIGINT,
  channel        TEXT        NOT NULL DEFAULT 'app',
  subject        TEXT,
  body           TEXT,
  status         TEXT        NOT NULL DEFAULT 'pending'
                CHECK (status IN ('pending','sent','failed','read')),
  read_at        TIMESTAMPTZ,
  sent_at        TIMESTAMPTZ DEFAULT NOW(),
  created_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  CONSTRAINT notifications_user_fk FOREIGN KEY (tenant_id, user_id)
    REFERENCES users (tenant_id, id) ON DELETE SET NULL
);
CREATE INDEX notifications_tenant_idx ON notifications (tenant_id);
CREATE INDEX notifications_status_idx ON notifications (tenant_id, status);

-- Logs (optional)
CREATE TABLE logs (
  id             BIGSERIAL PRIMARY KEY,
  tenant_id      BIGINT      NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
  user_id        BIGINT,
  level          TEXT        NOT NULL DEFAULT 'info'
                CHECK (level IN ('info','warning','error','audit')),
  action         TEXT        NOT NULL,
  entity_type    TEXT,
  entity_id      BIGINT,
  message        TEXT,
  metadata       JSONB,
  created_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (tenant_id, id),
  CONSTRAINT logs_user_fk FOREIGN KEY (tenant_id, user_id)
    REFERENCES users (tenant_id, id) ON DELETE SET NULL
);
CREATE INDEX logs_tenant_idx ON logs (tenant_id);
CREATE INDEX logs_level_idx ON logs (tenant_id, level);

-- Updated_at triggers
CREATE TRIGGER tenants_set_updated_at
BEFORE UPDATE ON tenants
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER users_set_updated_at
BEFORE UPDATE ON users
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER patients_set_updated_at
BEFORE UPDATE ON patients
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER services_set_updated_at
BEFORE UPDATE ON services
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER appointments_set_updated_at
BEFORE UPDATE ON appointments
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER payments_set_updated_at
BEFORE UPDATE ON payments
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER payment_installments_set_updated_at
BEFORE UPDATE ON payment_installments
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER record_templates_set_updated_at
BEFORE UPDATE ON record_templates
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER medical_records_set_updated_at
BEFORE UPDATE ON medical_records
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER notifications_set_updated_at
BEFORE UPDATE ON notifications
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TRIGGER logs_set_updated_at
BEFORE UPDATE ON logs
FOR EACH ROW EXECUTE FUNCTION set_updated_at();
