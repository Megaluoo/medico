<?php

class Record
{
    public string $id;
    public string $patientId;
    public string $patientName;
    public string $specialty;
    public array $data;
    public string $createdAt;

    public function __construct(string $patientId, string $patientName, string $specialty, array $data)
    {
        $this->id = uniqid('rec_', true);
        $this->patientId = $patientId;
        $this->patientName = $patientName;
        $this->specialty = $specialty;
        $this->data = $data;
        $this->createdAt = date('c');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patientId,
            'patient_name' => $this->patientName,
            'specialty' => $this->specialty,
            'data' => $this->data,
            'created_at' => $this->createdAt,
        ];
    }

    public static function fromArray(array $payload): self
    {
        $record = new self(
            $payload['patient_id'] ?? '',
            $payload['patient_name'] ?? '',
            $payload['specialty'] ?? '',
            $payload['data'] ?? []
        );
        $record->id = $payload['id'] ?? uniqid('rec_', true);
        $record->createdAt = $payload['created_at'] ?? date('c');

        return $record;
    }
}
