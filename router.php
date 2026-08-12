<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    // Served directly by PHP's built-in server, bypassing Laravel's
    // middleware/CORS config -- add the header here so the frontend
    // (a different Railway subdomain) can fetch these public assets
    // (plato photos, GLB/USDZ models) via model-viewer/fetch.
    if (str_starts_with($uri, '/storage/')) {
        header('Access-Control-Allow-Origin: *');
    }

    return false;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/public/index.php';
