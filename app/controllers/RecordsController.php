<?php
class RecordsController extends Controller
{
    public function index(): void
    {
        $this->render('records/index', ['pageTitle' => 'Historias clínicas']);
    }
}
