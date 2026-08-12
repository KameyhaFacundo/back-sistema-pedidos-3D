<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Storage files (plato photos, GLB/USDZ models) need CORS since the
// frontend lives on a different Railway subdomain. Headers set with
// header() before `return false` are discarded once the built-in
// server takes over to serve the file itself, so serve it manually here.
if (str_starts_with($uri, '/storage/')) {
    $path = __DIR__ . '/public' . $uri;

    if (is_file($path)) {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: ' . (mime_content_type($path) ?: 'application/octet-stream'));
        header('Content-Length: ' . filesize($path));
        // Uploads get a fresh random filename every time (see
        // storeWithOriginalExtension in PlatoController), so a given URL's
        // content never changes -- safe to cache indefinitely and lets the
        // frontend's <link rel="prefetch"> actually pay off.
        header('Cache-Control: public, max-age=31536000, immutable');
        readfile($path);
    } else {
        http_response_code(404);
    }

    return true;
}

if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/public/index.php';
