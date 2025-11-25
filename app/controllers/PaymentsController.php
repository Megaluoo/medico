<?php
class PaymentsController extends Controller
{
    public function index(): void
    {
        $this->render('payments/index', ['pageTitle' => 'Pagos']);
    }
}
