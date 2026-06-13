<?php
$val = '2025-10-14T00:00:00.000000Z';
$ts = strtotime($val);
echo "strtotime result: ";
var_dump($ts);
if ($ts !== false) {
    echo "date result: " . date('Y-m-d', $ts) . "\n";
} else {
    echo "strtotime FAILED\n";
    // Try with DateTime
    try {
        $dt = new DateTime($val);
        echo "DateTime result: " . $dt->format('Y-m-d') . "\n";
    } catch (Exception $e) {
        echo "DateTime FAILED: " . $e->getMessage() . "\n";
    }
}

// Also test without microseconds
$val2 = '2025-10-14T00:00:00Z';
$ts2 = strtotime($val2);
echo "\nWithout microseconds:\n";
var_dump($ts2);
