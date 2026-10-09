<?php

// Cargar .env manualmente de forma correcta
$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);

        // Ignorar líneas vacías o comentarios que empiecen por #
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        // Parsear clave=valor
        if (str_contains($line, '=')) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'"); // Limpiar espacios y comillas

            $_ENV[$key] = $value;
            putenv("{$key}={$value}");
        }
    }
}

require_once("db.php");

require_once("controllers/mainController.php");
