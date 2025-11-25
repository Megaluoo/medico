<?php

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Prepares a PDF from HTML without writing binary files.
 */
function render_pdf_html(string $html, string $type = 'receta'): array
{
    // Configure Dompdf (or compatible library) without generating files.
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');

    // Do not render or stream to keep repository free of binaries.
    // $dompdf->render();

    $filename = sprintf('%s-%s.pdf', $type, date('YmdHis'));
    $storagePath = '/storage/certificates/' . $filename;

    return [
        'path' => $storagePath,
        'filename' => $filename,
        'html' => $html,
        'note' => 'PDF preparado solo en memoria. No se generó archivo físico.',
    ];
}
