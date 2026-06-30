<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AttendanceSession;
use Carbon\Carbon;

if ($argc < 2) {
    echo "Usage: php scripts/update_attendance_dates.php <session_id> [<open_at|'null'>] [<close_at|'null'>]\n";
    echo "Example: php scripts/update_attendance_dates.php 1 '2026-06-30 20:13:00' '2026-06-30 22:13:00'\n";
    echo "Use 'null' (without quotes) to clear a date.\n";
    exit(1);
}

$sessionId = (int) $argv[1];
$openArg = $argv[2] ?? null;
$closeArg = $argv[3] ?? null;

$session = AttendanceSession::find($sessionId);
if (! $session) {
    echo "AttendanceSession id={$sessionId} not found.\n";
    exit(1);
}

function parseArg($arg) {
    if (is_null($arg)) return null;
    if (strtolower($arg) === 'null') return null;
    try {
        return Carbon::parse($arg);
    } catch (Throwable $e) {
        echo "Failed to parse date: {$arg}\n";
        exit(1);
    }
}

$openAt = '2026-06-30 20:13:00'; 
$closeAt = '2026-06-30 22:13:00';

$session->open_at = $openAt;
$session->close_at = $closeAt;
$session->save();

echo "Updated AttendanceSession id={$session->id}: open_at=" . ($session->open_at?->toDateTimeString() ?? 'null') . ", close_at=" . ($session->close_at?->toDateTimeString() ?? 'null') . "\n";
