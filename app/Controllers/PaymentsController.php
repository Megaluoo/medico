<?php

require_once __DIR__ . '/../Models/Payment.php';
require_once __DIR__ . '/../Models/Service.php';

class PaymentsController
{
    private string $paymentsPath;
    private string $servicesPath;
    private array $patients;

    public function __construct(string $paymentsPath, string $servicesPath)
    {
        $this->paymentsPath = $paymentsPath;
        $this->servicesPath = $servicesPath;
        $this->patients = [
            'Ana García',
            'Carlos Pérez',
            'María Rodríguez',
            'Juan Torres',
        ];
    }

    public function list(): array
    {
        $payments = Payment::all($this->paymentsPath);
        $totals = Payment::totals($payments);

        return compact('payments', 'totals');
    }

    public function services(): array
    {
        return ['services' => Service::all($this->servicesPath)];
    }

    public function create(): array
    {
        return [
            'services' => Service::all($this->servicesPath),
            'patients' => $this->patients,
            'methods' => $this->paymentMethods(),
        ];
    }

    public function store(array $data): Payment
    {
        $service = Service::find($this->servicesPath, $data['service']);
        return Payment::create($this->paymentsPath, $data, $service);
    }

    public function storeService(array $data): Service
    {
        return Service::create($this->servicesPath, $data);
    }

    public function show(string $paymentId): ?Payment
    {
        return Payment::find($this->paymentsPath, $paymentId);
    }

    public function addInstallment(string $paymentId, array $data): ?Payment
    {
        $payment = $this->show($paymentId);
        if ($payment) {
            $payment->addInstallment($this->paymentsPath, $data);
        }

        return $payment;
    }

    public function reports(): array
    {
        $payments = Payment::all($this->paymentsPath);

        return [
            'payments' => $payments,
            'incomeToday' => Payment::incomesByDate($payments, date('Y-m-d')),
            'incomeByMonth' => Payment::incomesByMonth($payments),
            'methodStats' => Payment::paymentMethodStats($payments),
        ];
    }

    public function dashboard(): array
    {
        $payments = Payment::all($this->paymentsPath);
        return [
            'recent' => Payment::recentIncomes($payments, 7),
            'totals' => Payment::totals($payments),
        ];
    }

    public function paymentMethods(): array
    {
        return [
            'USD',
            'Bs',
            'Zelle',
            'Pago móvil',
            'Transferencia',
            'Efectivo',
            'Punto',
        ];
    }
}
