# Uppy AWS S3 Example

This project demonstrates how to use Uppy, a file uploader, with AWS S3 (or compatible services like DigitalOcean Spaces) in PHP. It provides a simple example of how to configure Uppy to upload files directly to an S3 bucket from the browser.

## Prerequisites

- PHP 7.1 or higher
- Composer
- AWS S3 or compatible service (e.g., DigitalOcean Spaces)

## Installation

1. Clone the repository:

```
git clone https://github.com/dimisdas/uppy-aws-s3-example.git
cd uppy-aws-s3-example
```

2. Install the required dependencies using Composer:

```
composer require aws/aws-sdk-php
composer require vlucas/phpdotenv
```

3. Create a `.env` file in the project root and add your AWS S3 (or compatible service) credentials:

```
SCW_ACCESS_KEY=your_access_key
SCW_SECRET_KEY=your_secret_key
SCW_REGION=your_region
BUCKET_NAME=your_bucket_name
```

Replace `your_access_key`, `your_secret_key`, `your_region`, and `your_bucket_name` with your actual credentials and bucket information.

4. Start a local PHP server:

```
php -S localhost:8000
```

5. Open your browser and visit `http://localhost:8000` to see the Uppy file uploader in action.

## Usage

1. Click on the "Choose Files" button or drag and drop files into the Uppy dashboard.
2. The selected files will be automatically uploaded to your specified S3 bucket.
3. Upon successful upload, you will see a success message in the browser console.

## File Structure

- `index.html`: The main HTML file that includes the Uppy file uploader.
- `sign.php`: The PHP script that handles signing the S3 upload request.
- `.env`: The environment file to store your sensitive credentials (not included in the repository).
- `.env.example`: An example environment file to show the required variables.
- `vendor/`: The directory containing the installed Composer dependencies.

## Make sure CORS is set correctly

1. Use [s3cmd](https://docs.digitalocean.com/products/spaces/reference/s3cmd/) (lookup online for instructions on how to configure it with your s3 provider).
2. Allow your domain, or all origins, using the template below (save as cors.xml):
```
<?xml version="1.0" encoding="UTF-8"?>
<CORSConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <CORSRule>
        <AllowedOrigin>*</AllowedOrigin>
        <AllowedMethod>POST</AllowedMethod>
        <AllowedMethod>PUT</AllowedMethod>
        <AllowedMethod>DELETE</AllowedMethod>
        <MaxAgeSeconds>3000</MaxAgeSeconds>
        <ExposeHeader>ETag</ExposeHeader>
        <AllowedHeader>*</AllowedHeader>
    </CORSRule>
</CORSConfiguration>
```
3. Upload cors configuration using `s3cmd setcors cors.xml s3://your-bucket-name-here`

## Dependencies

- [AWS SDK for PHP](https://github.com/aws/aws-sdk-php): The official AWS SDK for PHP, used for interacting with AWS S3.
- [PHP-DotEnv](https://github.com/vlucas/phpdotenv): A library for loading environment variables from a `.env` file.
