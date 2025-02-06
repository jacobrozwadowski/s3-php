# S3 SignedURL Generator (PHP)

This PHP script generates presigned URLs for accessing objects stored in Cloudflare R2. Presigned URLs provide temporary access to private objects without requiring authentication for each request.

## Prerequisites

- **PHP:** PHP 7.4 or higher is recommended.
- **Composer:** A dependency manager for PHP. See installation instructions below.
- **AWS SDK for PHP:** This script uses the AWS SDK for PHP to interact with Cloudflare R2.
- **Cloudflare R2 Bucket:** You need an existing R2 bucket and the necessary credentials to access it.

## Installation

1.  **Install Composer:**

    - **Linux/macOS:**

      ```bash
      curl -sS https://getcomposer.org/installer | php
      sudo mv composer.phar /usr/local/bin/composer
      ```

    - **Windows:** Download and run the Composer installer from [https://getcomposer.org/download/](https://getcomposer.org/download/)

2.  **Clone the repository (or create a new project):**

    ```bash
    git clone <https://github.com/jacobrozwadowski/s3-php.git>  # If applicable
    cd <s3-php>
    ```

3.  **Install Dependencies:**

    Run the following command in your project directory to install the AWS SDK for PHP:

    ```bash
    composer require aws/aws-sdk-php
    ```

    This will create a `vendor` directory containing the AWS SDK and its dependencies, as well as `composer.json` and `composer.lock` files.

## Configuration

1.  **Set Environment Variables:**

    The script relies on the following environment variables for authentication and bucket configuration. **Do not hardcode these values in your script!**

    - `S3_ACCESS_KEY`: Your Cloudflare R2 / AWS S3 access key ID.
    - `S3_SECRET_KEY`: Your Cloudflare R2 / AWS S3 secret access key.
    - `S3_BUCKET_NAME`: The name of your Cloudflare R2 / AWS S3 bucket.
    - `S3_ENDPOINT`: The R2/S3 endpoint URL (e.g., `https://<your-account-id>.r2.cloudflarestorage.com`).

    How you set these environment variables depends on your environment (e.g., `.env` file, web server configuration, system environment variables).

## Usage

1.  **Save the PHP script:**

    Save the provided PHP code (e.g., as `getsignedurl.php`) in your project directory.

2.  **Include the autoloader:**

    Ensure that the following line is present at the beginning of your PHP script:

    ```php
    <?php

    require 'vendor/autoload.php';

    // Code here
    ```

3.  **Access the script via your web server:**

    Access the script through your web server, passing the `fileName` as a query parameter:

    ```
    https://example.com/getsignedurl.php?fileName=test.txt
    ```

    Replace `example.com` with your server's address and `test.txt` with the name of the object you want to generate a presigned URL for.

4.  **Response:**

    The script will return a JSON response containing the presigned URL:

    ```json
    {
      "url": "https://<your-r2-endpoint>/<your-bucket>/your-file.txt?AWSAccessKeyId=...&Signature=...&Expires=..."
    }
    ```

    You can then use this URL to access the object for a limited time (5 minutes by default).

## Error Handling

The script includes error handling to catch potential issues:

- **Missing Filename:** Returns a 400 status code if the `fileName` query parameter is missing.
- **S3 Exceptions:** Catches exceptions related to S3 operations (e.g., invalid credentials, bucket not found) and returns a 500 status code with an error message.
- **General Exceptions:** Catches other exceptions and returns a 500 status code with an error message.
- **Logging:** Errors are logged using `error_log()` for debugging purposes. Check your server's error logs for details.

## Security Considerations

- **Environment Variables:** Always store your R2 credentials in environment variables, not directly in the code.
- **Presigned URL Expiration:** The script generates presigned URLs that expire after 5 minutes. Adjust the expiration time as needed, but keep it as short as possible for security reasons.
- **HTTPS:** Ensure that your web server is configured to use HTTPS to protect the presigned URLs during transmission.
- **Principle of Least Privilege:** Grant your R2 access key only the necessary permissions to access the required buckets and objects.

## Contributing

Contributions are welcome! Please submit a pull request with your changes.
