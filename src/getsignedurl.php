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

# AWS credentials and bucket configuration
$bucket = getenv('S3_BUCKET_NAME');
$region = 'auto';
$endpoint = getenv('S3_ENDPOINT');
$accessKeyId = getenv('S3_ACCESS_KEY');
$secretAccessKey = getenv('S3_SECRET_KEY');

try {
    $s3Client = new S3Client([
        'region' => $region,
        'version' => 'latest',
        'endpoint' => $endpoint,
        'use_path_style_endpoint' => true, # Required if you're using Cloudflare R2
        'credentials' => [
            'key' => $accessKeyId,
            'secret' => $secretAccessKey,
        ],
    ]);

    # Command to get the object
    $command = $s3Client->getCommand("GetObject", [
        'Bucket' => $bucket,
        'Key' => $fileName,
    ]);

    // create presigned URL
    $request = $s3Client->createPresignedRequest($command, '+5 minutes');

    # presigned url 
    $signedUrl = (string) $request->getUri();

    # response
    $responseBody = ['url' => $signedUrl];

    # set response headers
    header('Content-Type: application/json');
    http_response_code(200); # OK

    # send json response
    echo json_encode($responseBody);
} catch (S3Exception $e) {
    # log error
    error_log('Error generating signed URL:' . $e->getMessage());

    # set error response
    http_response_code(500);
    echo "Error generating signed URL: " . $e->getMessage();
} catch (Exception $e) {
    # handle diverse exceptions
    error_log('General error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}
