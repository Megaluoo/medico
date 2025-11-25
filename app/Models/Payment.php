<?php

class Payment
{
    public string $id;
    public string $patient;
    public string $serviceId;
    public string $serviceName;
    public float $servicePrice;
    public string $paymentMethod;
    public float $amountPaid;
    public float $balance;
    public array $installments;
    public string $createdAt;

    public function __construct(
        string $id,
        string $patient,
        string $serviceId,
        string $serviceName,
        float $servicePrice,
        string $paymentMethod,
        float $amountPaid,
        float $balance,
        array $installments,
        string $createdAt
    ) {
        $this->id = $id;
        $this->patient = $patient;
        $this->serviceId = $serviceId;
        $this->serviceName = $serviceName;
        $this->servicePrice = $servicePrice;
        $this->paymentMethod = $paymentMethod;
        $this->amountPaid = $amountPaid;
        $this->balance = $balance;
        $this->installments = $installments;
        $this->createdAt = $createdAt;
    }

    public static function all(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }

        $contents = json_decode(file_get_contents($path), true) ?: [];
        return array_map(function ($item) {
            return new Payment(
                $item['id'],
                $item['patient'],
                $item['service_id'],
                $item['service_name'],
                (float) $item['service_price'],
                $item['payment_method'],
                (float) $item['amount_paid'],
                (float) $item['balance'],
                $item['installments'] ?? [],
                $item['created_at'] ?? date('c')
            );
        }, $contents);
    }

    public static function create(string $path, array $data, ?Service $service = null): Payment
    {
        $payments = self::all($path);
        $servicePrice = $service ? $service->price : (float) ($data['total'] ?? 0);
        $initialAmount = (float) $data['amount_paid'];
        $balance = max(0, $servicePrice - $initialAmount);

        $payment = new Payment(
            uniqid('pay_', true),
            trim($data['patient']),
            $service ? $service->id : '',
            $service ? $service->name : ($data['service_name'] ?? 'Servicio'),
            $servicePrice,
            $data['payment_method'],
            $initialAmount,
            $balance,
            [
                [
                    'amount' => $initialAmount,
                    'method' => $data['payment_method'],
                    'note' => 'Pago inicial',
                    'date' => date('c'),
                ],
            ],
            date('c')
        );

        $payments[] = $payment;
        self::saveAll($path, $payments);

        return $payment;
    }

    public static function find(string $path, string $id): ?Payment
    {
        foreach (self::all($path) as $payment) {
            if ($payment->id === $id) {
                return $payment;
            }
        }

        return null;
    }

    public function addInstallment(string $path, array $data): void
    {
        $amount = (float) $data['amount'];
        $method = $data['payment_method'];
        $note = trim($data['note'] ?? 'Abono');

        $this->installments[] = [
            'amount' => $amount,
            'method' => $method,
            'note' => $note,
            'date' => date('c'),
        ];

        $this->amountPaid += $amount;
        $this->balance = max(0, $this->servicePrice - $this->amountPaid);

        $this->persistUpdate($path);
    }

    public function persistUpdate(string $path): void
    {
        $payments = self::all($path);
        foreach ($payments as $index => $existing) {
            if ($existing->id === $this->id) {
                $payments[$index] = $this;
                break;
            }
        }

        self::saveAll($path, $payments);
    }

    public static function saveAll(string $path, array $payments): void
    {
        $payload = array_map(function (Payment $payment) {
            return [
                'id' => $payment->id,
                'patient' => $payment->patient,
                'service_id' => $payment->serviceId,
                'service_name' => $payment->serviceName,
                'service_price' => $payment->servicePrice,
                'payment_method' => $payment->paymentMethod,
                'amount_paid' => $payment->amountPaid,
                'balance' => $payment->balance,
                'installments' => $payment->installments,
                'created_at' => $payment->createdAt,
            ];
        }, $payments);

        file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT));
    }

    public static function totals(array $payments): array
    {
        $totalPaid = array_sum(array_map(fn ($p) => $p->amountPaid, $payments));
        $totalBalance = array_sum(array_map(fn ($p) => $p->balance, $payments));

        return [
            'total_paid' => $totalPaid,
            'total_balance' => $totalBalance,
        ];
    }

    public static function incomesByDate(array $payments, string $date): float
    {
        $targetDate = date('Y-m-d', strtotime($date));
        $total = 0;

        foreach ($payments as $payment) {
            foreach ($payment->installments as $installment) {
                if (date('Y-m-d', strtotime($installment['date'])) === $targetDate) {
                    $total += (float) $installment['amount'];
                }
            }
        }

        return $total;
    }

    public static function incomesByMonth(array $payments): array
    {
        $totals = [];
        foreach ($payments as $payment) {
            foreach ($payment->installments as $installment) {
                $month = date('Y-m', strtotime($installment['date']));
                $totals[$month] = ($totals[$month] ?? 0) + (float) $installment['amount'];
            }
        }

        ksort($totals);
        return $totals;
    }

    public static function paymentMethodStats(array $payments): array
    {
        $stats = [];
        foreach ($payments as $payment) {
            foreach ($payment->installments as $installment) {
                $method = $installment['method'];
                $stats[$method] = ($stats[$method] ?? 0) + 1;
            }
        }

        arsort($stats);
        return $stats;
    }

    public static function recentIncomes(array $payments, int $limit = 5): array
    {
        $entries = [];
        foreach ($payments as $payment) {
            foreach ($payment->installments as $installment) {
                $entries[] = [
                    'patient' => $payment->patient,
                    'service' => $payment->serviceName,
                    'amount' => (float) $installment['amount'],
                    'method' => $installment['method'],
                    'date' => $installment['date'],
                ];
            }
        }

        usort($entries, function ($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });

        return array_slice($entries, 0, $limit);
    }
}
