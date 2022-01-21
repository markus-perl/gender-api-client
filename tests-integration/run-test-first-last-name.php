<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/CsvReader.php';

if (!isset($argv[1], $argv[2], $argv[3])) {
    echo 'Usage: ' . $argv[0] . ' <csv file to run tests against> <apiKey> <apiUrl>';
    exit(1);
}

$testFile = $argv[1];
$apiKey = $argv[2];
$apiUrl = $argv[3];

if (!file_exists($testFile)) {
    echo 'file ' . $testFile . ' not found.' . PHP_EOL;
    exit(1);
}

$csvFile = new CsvReader($testFile);
$client = new \GenderApi\Client($apiKey);
$client->setApiUrl($apiUrl);

$successes = 0;
$fails = 0;
$failures = [];
foreach ($csvFile as $line) {
    if ($line && isset($line['name']) && $line['name']) {
        echo $line['name'] . ' - ';
        $result = $client->getByFirstNameAndLastName($line['name']);
        if ($result->getFirstName() == $line['correct_first_name'] && $result->getLastName() == $line['correct_last_name']) {
            $successes++;
            echo '✓';
        } else {
            $fails++;
            echo 'X';

            $failures[] = [
                'r' => $result,
                'l' => $line,
            ];
        }
        echo PHP_EOL;
    }
}

if ($successes == $fails) {
    echo 'Something went wrong. No names were tested' . PHP_EOL;
    exit;
}

echo '==================================' . PHP_EOL;

if (count($failures)) {
    echo 'Failures:' . PHP_EOL;
    foreach ($failures as $failure) {
        echo 'Expected: ' . $failure['l']['correct_first_name'] . ', ' . $failure['l']['correct_last_name'] . ' - Got: ' . $failure['r']->getFirstName() . ', ' . $failure['r']->getLastName() . PHP_EOL;
    }
}


echo 'Fails: ' . $fails . '. Result quality: ' . round(100 / ($successes + $fails) * $successes, 1) . '%' . PHP_EOL;