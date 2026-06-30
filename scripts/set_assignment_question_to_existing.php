<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Assignment;

$a = Assignment::find(1);
if (!$a) {
    echo "Assignment id=1 not found\n";
    exit(1);
}

$a->question_file = 'assignments/questions/Tugas Jarkom.pdf';
$a->save();

echo "Updated assignment->question_file = " . $a->question_file . "\n";
