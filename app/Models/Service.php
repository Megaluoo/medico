<?php

class Service
{
    public string $id;
    public string $name;
    public float $price;
    public string $type;

    public function __construct(string $id, string $name, float $price, string $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
    }

    public static function all(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }

        $contents = json_decode(file_get_contents($path), true) ?: [];
        return array_map(function ($item) {
            return new Service($item['id'], $item['name'], (float) $item['price'], $item['type']);
        }, $contents);
    }

    public static function find(string $path, string $id): ?Service
    {
        foreach (self::all($path) as $service) {
            if ($service->id === $id) {
                return $service;
            }
        }

        return null;
    }

    public static function create(string $path, array $data): Service
    {
        $services = self::all($path);
        $service = new Service(
            uniqid('srv_', true),
            trim($data['name']),
            (float) $data['price'],
            trim($data['type'])
        );

        $services[] = $service;
        self::saveAll($path, $services);

        return $service;
    }

    public static function saveAll(string $path, array $services): void
    {
        $payload = array_map(function (Service $service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $service->price,
                'type' => $service->type,
            ];
        }, $services);

        file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT));
    }
}
