<?php
class Controller
{
    protected function render(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View {$view} not found");
        }

        extract($data);

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        include __DIR__ . '/../views/layouts/main.php';
    }
}
