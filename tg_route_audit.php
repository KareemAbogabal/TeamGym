<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$wanted = ['login', 'sign', 'forget', 'verify', 'reset', 'log-out', 'qr', 'record', 'destroy', 'insert-pay', 'lineage', 'save-data', 'save-img', 'users', 'update-employee', 'add-employees', 'search', 'health', 'schedule', 'get-exercises', 'my-qr', 'myQr', 'update-profile', 'update-client', 'contact'];
foreach (app('router')->getRoutes() as $r) {
    $uri = $r->uri();
    $hit = false;
    foreach ($wanted as $w) { if (stripos($uri, $w) !== false) { $hit = true; break; } }
    if (!$hit) continue;
    $mws = collect($r->gatherMiddleware())->map(fn($m) => is_object($m) ? get_class($m) : $m)->implode(', ');
    echo str_pad($r->methods()[0], 6) . ' ' . str_pad($uri, 45) . ' [' . $r->getAction('as') . ']' . PHP_EOL . '   MW: ' . $mws . PHP_EOL;
}