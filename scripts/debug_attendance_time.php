<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

$session = AttendanceSession::find(1);
if (! $session) {
    echo "AttendanceSession id=1 not found\n";
    exit(1);
}

$tz = Config::get('app.timezone');
$now = Carbon::now();
$nowUtc = Carbon::now('UTC');

echo "APP TIMEZONE: " . $tz . "\n";
echo "now (app tz): " . $now->toDateTimeString() . " (" . $now->getTimezone()->getName() . ")\n";
echo "now (UTC): " . $nowUtc->toDateTimeString() . "\n\n";

echo "session open_at (raw): " . ($session->open_at? $session->open_at->toDateTimeString() : 'null') . " (tz: " . ($session->open_at? $session->open_at->getTimezone()->getName() : 'n/a') . ")\n";
echo "session close_at (raw): " . ($session->close_at? $session->close_at->toDateTimeString() : 'null') . " (tz: " . ($session->close_at? $session->close_at->getTimezone()->getName() : 'n/a') . ")\n\n";

$cmpOpen = $session->open_at? $session->open_at->lte($now) : 'no-open';
$cmpClose = $session->close_at? $session->close_at->gte($now) : 'no-close';

echo "open_at <= now()? -> "; var_export($cmpOpen); echo "\n";
echo "close_at >= now()? -> "; var_export($cmpClose); echo "\n";

// Also print same comparisons in UTC
$cmpOpenUtc = $session->open_at? $session->open_at->setTimezone('UTC')->lte($nowUtc) : 'no-open';
$cmpCloseUtc = $session->close_at? $session->close_at->setTimezone('UTC')->gte($nowUtc) : 'no-close';

echo "open_at <= now() (UTC compare)? -> "; var_export($cmpOpenUtc); echo "\n";
echo "close_at >= now() (UTC compare)? -> "; var_export($cmpCloseUtc); echo "\n";
