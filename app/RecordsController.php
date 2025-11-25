<?php

require_once __DIR__ . '/Record.php';

class RecordsController
{
    private string $storagePath;

    public function __construct(?string $storagePath = null)
    {
        $this->storagePath = $storagePath ?? __DIR__ . '/../data/records.json';
        $this->bootstrapStorage();
    }

    private function bootstrapStorage(): void
    {
        $directory = dirname($this->storagePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (!file_exists($this->storagePath)) {
            file_put_contents($this->storagePath, json_encode([]));
        }
    }

    /**
     * @return Record[]
     */
    public function all(?string $patientId = null): array
    {
        $content = file_get_contents($this->storagePath);
        $items = json_decode($content, true) ?? [];

        $records = array_map(fn ($item) => Record::fromArray($item), $items);

        if ($patientId) {
            $records = array_filter(
                $records,
                fn (Record $record) => $record->patientId === $patientId
            );
        }

        return array_values($records);
    }

    public function find(string $id): ?Record
    {
        foreach ($this->all() as $record) {
            if ($record->id === $id) {
                return $record;
            }
        }

        return null;
    }

    public function store(array $payload): Record
    {
        $patientId = trim($payload['patient_id'] ?? '');
        $patientName = trim($payload['patient_name'] ?? '');
        $specialty = trim($payload['specialty'] ?? '');
        $formData = $payload['form_data'] ?? [];

        $customLabels = $payload['custom_field_label'] ?? [];
        $customValues = $payload['custom_field_value'] ?? [];

        $customFields = [];
        foreach ($customLabels as $index => $label) {
            $label = trim($label);
            $value = $customValues[$index] ?? '';
            if ($label !== '') {
                $customFields[$label] = $value;
            }
        }

        if (!empty($customFields)) {
            $formData['custom_fields'] = $customFields;
        }

        $record = new Record($patientId, $patientName, $specialty, $formData);

        $existing = json_decode(file_get_contents($this->storagePath), true) ?? [];
        $existing[] = $record->toArray();
        file_put_contents($this->storagePath, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $record;
    }

    public function specialties(): array
    {
        return [
            'ginecologia' => 'Ginecología',
            'obstetricia' => 'Obstetricia',
            'pediatria' => 'Pediatría',
            'neonatologia' => 'Neonatología',
            'traumatologia' => 'Traumatología',
            'cirugia_plastica' => 'Cirugía plástica',
        ];
    }
}
