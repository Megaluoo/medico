<?php
function renderHeader(string $title)
{
    echo "<!doctype html><html lang='es'><head><meta charset='UTF-8'><title>{$title} - Pagos</title>";
    echo "<style>body{font-family:Arial, sans-serif;margin:0;padding:0;background:#f7f9fb;}header{background:#0a5c83;color:#fff;padding:16px;}nav a{color:#fff;margin-right:12px;text-decoration:none;font-weight:bold;}main{padding:20px;}table{width:100%;border-collapse:collapse;margin-top:12px;}th,td{border:1px solid #e0e0e0;padding:8px;text-align:left;}th{background:#f0f6fa;}form{background:#fff;padding:16px;border:1px solid #eaeaea;border-radius:6px;}input,select,textarea{width:100%;padding:8px;margin:6px 0 12px;border:1px solid #d0d7de;border-radius:4px;}button{background:#0a5c83;color:#fff;border:none;padding:10px 16px;border-radius:4px;cursor:pointer;}button.secondary{background:#6c757d;} .pill{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;} .pill.success{background:#d1e7dd;color:#0f5132;} .pill.warning{background:#fff3cd;color:#664d03;} .stats{display:flex;gap:16px;flex-wrap:wrap;} .card{background:#fff;border:1px solid #eaeaea;border-radius:6px;padding:12px;flex:1;min-width:180px;} .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;} .muted{color:#6c757d;font-size:14px;} </style></head><body>";
    echo "<header><div style='display:flex;justify-content:space-between;align-items:center'><div><strong>Dashboard de Pagos</strong></div><nav><a href='/index.php?view=payments'>Pagos</a><a href='/index.php?view=create'>Nuevo pago</a><a href='/index.php?view=services'>Servicios</a><a href='/index.php?view=reports'>Reportes</a><a href='/index.php?view=dashboard'>Dashboard</a></nav></div></header><main>";
}

function renderFooter()
{
    echo "</main></body></html>";
}
