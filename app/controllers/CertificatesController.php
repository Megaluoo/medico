<?php

class CertificatesController
{
    /**
     * Displays the certificates dashboard landing page.
     */
    public function index(): void
    {
        $this->render('certificates/index.php');
    }

    /**
     * Shows the form to create a new certificate or prescription document.
     */
    public function create(): void
    {
        $this->render('certificates/create.php');
    }

    /**
     * Handles creation of the PDF-ready HTML for the requested document type.
     * The PDF generation is delegated to a helper and no binary is produced here.
     */
    public function generate(): void
    {
        $type = $_POST['type'] ?? 'receta';
        $data = [
            'patient' => $_POST['patient'] ?? '',
            'doctor' => $_POST['doctor'] ?? '',
            'content' => $_POST['content'] ?? '',
            'date' => $_POST['date'] ?? date('Y-m-d'),
            'notes' => $_POST['notes'] ?? '',
        ];

        $template = $this->resolveTemplate($type);
        $html = $this->renderTemplate($template, $data);

        require_once __DIR__ . '/../helpers/pdf.php';
        $result = render_pdf_html($html, $type);

        $this->render('certificates/index.php', [
            'status' => 'preview',
            'document_type' => $type,
            'html' => $html,
            'storage' => $result['path'],
        ]);
    }

    /**
     * Lists previously prepared certificates or prescriptions.
     */
    public function list(): void
    {
        // Placeholder static list until storage is wired up.
        $documents = [
            [
                'id' => 1,
                'patient' => 'Paciente Demo',
                'type' => 'receta',
                'created_at' => date('Y-m-d'),
                'path' => '/storage/certificates/receta-demo.pdf',
            ],
        ];

        $this->render('certificates/list.php', ['documents' => $documents]);
    }

    /**
     * Prepares the download response. Actual file streaming is deferred until storage exists.
     */
    public function download(): void
    {
        $id = $_GET['id'] ?? null;
        $message = $id ? "Descarga preparada para el documento #{$id}." : 'Documento no encontrado.';

        $this->render('certificates/index.php', [
            'status' => 'download',
            'message' => $message,
        ]);
    }

    /**
     * Resolves the correct template path for the given document type.
     */
    private function resolveTemplate(string $type): string
    {
        $available = [
            'receta' => __DIR__ . '/../views/certificates/templates/receta_template.php',
            'constancia' => __DIR__ . '/../views/certificates/templates/constancia_template.php',
            'reposo' => __DIR__ . '/../views/certificates/templates/reposo_template.php',
            'informe' => __DIR__ . '/../views/certificates/templates/informe_template.php',
        ];

        return $available[$type] ?? $available['receta'];
    }

    /**
     * Renders a template and returns the resulting HTML string.
     */
    private function renderTemplate(string $template, array $data): string
    {
        ob_start();
        extract($data, EXTR_SKIP);
        include $template;
        return (string) ob_get_clean();
    }

    /**
     * Basic render helper to include a view file with provided data.
     */
    private function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $contentView = __DIR__ . '/../views/' . $view;
        include __DIR__ . '/../views/layouts/sidebar.php';
    }
}
