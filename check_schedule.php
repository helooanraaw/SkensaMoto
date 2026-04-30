<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schedules = \App\Models\WorkshopSchedule::orderBy('date')->get(['date', 'status', 'quota']);
foreach ($schedules as $s) {
    echo $s->date . ' | ' . $s->status . ' | kuota: ' . $s->quota . PHP_EOL;
}

echo "\nTotal: " . count($schedules) . " rows\n";
echo "Today: " . now()->format('Y-m-d') . "\n";
echo "This Mon: " . now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d') . "\n";
echo "This Fri: " . now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(4)->format('Y-m-d') . "\n";
