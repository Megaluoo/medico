<?php
class AppointmentsController extends Controller
{
    public function index(): void
    {
        $this->render('appointments/index', ['pageTitle' => 'Citas']);
    }
}
