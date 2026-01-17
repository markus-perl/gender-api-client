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
foreach ($csvFile as $line) {
    if (isset($line['name']) && $line['name']) {
        echo $line['name'] . ' - ';
        if (isset($line['country'])) {
            $result = $client->getByFirstNameAndCountry($line['name'], $line['country']);
        } else {
            $result = $client->getByFirstName($line['name']);
        }

        $expectedGender = $line['gender'] ?? $line['correct_gender'] ?? 'unknown';

        if ($expectedGender == 'unisex')
            $expectedGender = 'unknown';

        if ($result->getGender() == $expectedGender) {
            $successes++;
            echo '✓';
        } else {
            $fails++;
            echo 'X';
        }
        echo PHP_EOL;
    }
}

echo '==================================' . PHP_EOL;
echo 'Fails: ' . $fails . '. Result quality: ' . round(100 / ($successes + $fails) * $successes, 1) . '%';