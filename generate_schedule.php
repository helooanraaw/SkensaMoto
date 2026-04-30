<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$now = \Carbon\Carbon::now()->startOfWeek();
for ($i = 0; $i < 14; $i++) {
    $date = $now->copy()->addDays($i);
    // Skip Saturday and Sunday
    if ($date->isWeekend()) continue;
    
    // update or create
    \App\Models\WorkshopSchedule::updateOrCreate(
        ['date' => $date->format('Y-m-d')],
        [
            'start_time' => '08:00:00',
            'end_time' => '15:00:00',
            'quota' => rand(2, 15),
            'status' => rand(0, 5) === 0 ? 'libur' : 'tersedia'
        ]
    );
}

echo "Schedules generated.";
