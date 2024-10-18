<?php

function parseEnv($filePath)
{
    $contents = file_get_contents($filePath);
    $lines = explode("\n", $contents);
    $env = [];

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $env[$key] = $value;
    }

    return $env;
}

$envFilePath = __DIR__ . '/.env';
if (!file_exists($envFilePath)) {
    die('.env file not found');
}
$env = parseEnv($envFilePath);

$conn = mysqli_connect($env['DB_HOST'], $env['DB_USER'], $env['DB_PASS'], $env['DB_NAME']);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

