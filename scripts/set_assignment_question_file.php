<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Assignment;

$assignment = Assignment::find(1);
if (!$assignment) {
    echo "Assignment id=1 not found\n";
    exit(1);
}

$assignment->question_file = 'assignments/questions/question-demo.txt';
$assignment->save();

echo "Updated assignment->question_file = " . $assignment->question_file . "\n";
