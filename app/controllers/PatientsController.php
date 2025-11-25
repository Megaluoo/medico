<?php
class PatientsController extends Controller
{
    public function index(): void
    {
        $this->render('patients/index', ['pageTitle' => 'Pacientes']);
    }
}
