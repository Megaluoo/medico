<?php

declare(strict_types=1);

require_once BASE_PATH . '/app/Models/Patient.php';

class PatientsController
{
    private Patient $patients;

    public function __construct()
    {
        $this->patients = new Patient();
    }

    public function index(): void
    {
        $tenantId = $this->tenantId();
        $filters = [
            'search' => $_GET['q'] ?? '',
            'sex' => $_GET['sex'] ?? '',
        ];

        $patients = $this->patients->all($tenantId, $filters);
        $this->render('patients/index', [
            'patients' => $patients,
            'filters' => $filters,
            'tenantId' => $tenantId,
        ]);
    }

    public function create(): void
    {
        $tenantId = $this->tenantId();
        $this->render('patients/create', ['tenantId' => $tenantId]);
    }

    public function store(): void
    {
        $tenantId = $this->tenantId();
        $data = $this->validatedData();
        $data['files'] = $this->uploadFiles();

        $id = $this->patients->create($tenantId, $data);
        $_SESSION['flash'] = 'Paciente creado correctamente.';
        $this->redirect('index.php?action=show&id=' . $id . '&tenant_id=' . $tenantId);
    }

    public function show(): void
    {
        $tenantId = $this->tenantId();
        $patient = $this->patients->find($tenantId, (int) ($_GET['id'] ?? 0));

        if (!$patient) {
            $this->redirect('index.php?tenant_id=' . $tenantId);
        }

        $this->render('patients/show', ['patient' => $patient, 'tenantId' => $tenantId]);
    }

    public function edit(): void
    {
        $tenantId = $this->tenantId();
        $patient = $this->patients->find($tenantId, (int) ($_GET['id'] ?? 0));

        if (!$patient) {
            $this->redirect('index.php?tenant_id=' . $tenantId);
        }

        $this->render('patients/edit', ['patient' => $patient, 'tenantId' => $tenantId]);
    }

    public function update(): void
    {
        $tenantId = $this->tenantId();
        $id = (int) ($_POST['id'] ?? 0);
        $patient = $this->patients->find($tenantId, $id);

        if (!$patient) {
            $this->redirect('index.php?tenant_id=' . $tenantId);
        }

        $data = $this->validatedData();

        $existingFiles = $patient['files'] ? json_decode($patient['files'], true) : [];
        $uploaded = $this->uploadFiles();
        $data['files'] = $this->patients->mergeFiles($existingFiles, $uploaded);

        $this->patients->update($tenantId, $id, $data);
        $_SESSION['flash'] = 'Cambios guardados correctamente.';
        $this->redirect('index.php?action=show&id=' . $id . '&tenant_id=' . $tenantId);
    }

    public function destroy(): void
    {
        $tenantId = $this->tenantId();
        $id = (int) ($_POST['id'] ?? 0);

        if ($id) {
            $this->patients->delete($tenantId, $id);
            $_SESSION['flash'] = 'Paciente eliminado.';
        }

        $this->redirect('index.php?tenant_id=' . $tenantId);
    }

    private function validatedData(): array
    {
        $required = ['first_name', 'last_name', 'birth_date', 'sex'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['flash'] = 'Por favor completa todos los campos obligatorios.';
                $this->redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
            }
        }

        return [
            'first_name' => trim($_POST['first_name']),
            'last_name' => trim($_POST['last_name']),
            'birth_date' => trim($_POST['birth_date']),
            'sex' => trim($_POST['sex']),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'allergies' => trim($_POST['allergies'] ?? ''),
            'history' => trim($_POST['history'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ];
    }

    private function uploadFiles(): array
    {
        if (empty($_FILES['attachments']) || !is_array($_FILES['attachments']['name'])) {
            return [];
        }

        $uploads = [];
        $total = count($_FILES['attachments']['name']);
        $destination = BASE_PATH . '/storage/uploads/patients/';

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        for ($i = 0; $i < $total; $i++) {
            if ($_FILES['attachments']['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $original = basename((string) $_FILES['attachments']['name'][$i]);
            $extension = pathinfo($original, PATHINFO_EXTENSION);
            $slug = pathinfo($original, PATHINFO_FILENAME);
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $slug);
            $filename = $safeName ? $safeName : 'archivo';
            $finalName = $filename . '-' . uniqid() . ($extension ? '.' . $extension : '');
            $target = $destination . $finalName;

            if (move_uploaded_file($_FILES['attachments']['tmp_name'][$i], $target)) {
                $uploads[] = $finalName;
            }
        }

        return $uploads;
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        $pageTitle = $pageTitle ?? 'Pacientes';
        ob_start();
        include BASE_PATH . '/views/' . $view . '.php';
        $content = ob_get_clean();
        include BASE_PATH . '/views/layout.php';
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    private function tenantId(): int
    {
        return isset($_GET['tenant_id']) ? (int) $_GET['tenant_id'] : 1;
    }
}
