<?php

declare(strict_types=1);

class Patient
{
    private \PDO $db;

    public function __construct()
    {
        $dbPath = BASE_PATH . '/storage/database.sqlite';
        $this->db = new \PDO('sqlite:' . $dbPath);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->exec('PRAGMA foreign_keys = ON;');
        $this->migrate();
    }

    private function migrate(): void
    {
        $this->db->exec(
            'CREATE TABLE IF NOT EXISTS patients (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tenant_id INTEGER NOT NULL,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL,
                birth_date TEXT NOT NULL,
                sex TEXT NOT NULL,
                phone TEXT,
                email TEXT,
                address TEXT,
                allergies TEXT,
                history TEXT,
                notes TEXT,
                files TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );'
        );
    }

    public function all(int $tenantId, array $filters = []): array
    {
        $query = 'SELECT * FROM patients WHERE tenant_id = :tenant_id';
        $params = ['tenant_id' => $tenantId];

        if (!empty($filters['search'])) {
            $query .= ' AND (first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR phone LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['sex'])) {
            $query .= ' AND sex = :sex';
            $params['sex'] = $filters['sex'];
        }

        $query .= ' ORDER BY last_name COLLATE NOCASE ASC, first_name COLLATE NOCASE ASC';
        $statement = $this->db->prepare($query);
        $statement->execute($params);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $tenantId, int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM patients WHERE id = :id AND tenant_id = :tenant_id');
        $statement->execute(['id' => $id, 'tenant_id' => $tenantId]);
        $patient = $statement->fetch(\PDO::FETCH_ASSOC);

        return $patient ?: null;
    }

    public function create(int $tenantId, array $data): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO patients (tenant_id, first_name, last_name, birth_date, sex, phone, email, address, allergies, history, notes, files, created_at, updated_at)
             VALUES (:tenant_id, :first_name, :last_name, :birth_date, :sex, :phone, :email, :address, :allergies, :history, :notes, :files, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)'
        );

        $statement->execute([
            'tenant_id' => $tenantId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birth_date' => $data['birth_date'],
            'sex' => $data['sex'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'allergies' => $data['allergies'] ?? null,
            'history' => $data['history'] ?? null,
            'notes' => $data['notes'] ?? null,
            'files' => isset($data['files']) ? json_encode($data['files']) : null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $tenantId, int $id, array $data): bool
    {
        $patient = $this->find($tenantId, $id);

        if (!$patient) {
            return false;
        }

        $files = isset($data['files']) ? $data['files'] : ($patient['files'] ? json_decode($patient['files'], true) : []);
        $statement = $this->db->prepare(
            'UPDATE patients
             SET first_name = :first_name,
                 last_name = :last_name,
                 birth_date = :birth_date,
                 sex = :sex,
                 phone = :phone,
                 email = :email,
                 address = :address,
                 allergies = :allergies,
                 history = :history,
                 notes = :notes,
                 files = :files,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id AND tenant_id = :tenant_id'
        );

        return $statement->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birth_date' => $data['birth_date'],
            'sex' => $data['sex'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'allergies' => $data['allergies'] ?? null,
            'history' => $data['history'] ?? null,
            'notes' => $data['notes'] ?? null,
            'files' => $files ? json_encode($files) : null,
            'id' => $id,
            'tenant_id' => $tenantId,
        ]);
    }

    public function delete(int $tenantId, int $id): bool
    {
        $patient = $this->find($tenantId, $id);

        if (!$patient) {
            return false;
        }

        $this->removeFiles($patient['files']);

        $statement = $this->db->prepare('DELETE FROM patients WHERE id = :id AND tenant_id = :tenant_id');
        return $statement->execute(['id' => $id, 'tenant_id' => $tenantId]);
    }

    public function mergeFiles(array $existing, array $incoming): array
    {
        $merged = array_values(array_filter(array_merge($existing, $incoming)));

        return array_unique($merged);
    }

    private function removeFiles(?string $fileJson): void
    {
        if (!$fileJson) {
            return;
        }

        $files = json_decode($fileJson, true) ?: [];

        foreach ($files as $file) {
            $path = BASE_PATH . '/storage/uploads/patients/' . $file;

            if (is_file($path)) {
                @unlink($path);
            }
        }
    }
}
