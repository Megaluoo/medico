<?php
class PrescriptionsController extends Controller
{
    public function index(): void
    {
        $this->render('prescriptions/index', ['pageTitle' => 'Recetas']);
    }
}
