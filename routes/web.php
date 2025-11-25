<?php

return [
    '/certificates' => [CertificatesController::class, 'index'],
    '/certificates/create' => [CertificatesController::class, 'create'],
    '/certificates/list' => [CertificatesController::class, 'list'],
    '/certificates/generate' => [CertificatesController::class, 'generate'],
    '/certificates/download' => [CertificatesController::class, 'download'],
];
