<?php

require '../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$fileName = $_GET['fileName'] ?? null;

## Retrieves fileName from query parameters
if (!$fileName) {
    http_response_code(400);
    echo "Error: File name is required.";
    exit;
}
