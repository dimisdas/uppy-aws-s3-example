<?php

/* Ideally, you want to add some kind of user authentication here, unless you want *any* user to be able to upload files to your bucket. */

require __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Aws\S3\PostObjectV4;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$scwAccessKey = $_ENV['SCW_ACCESS_KEY'];
$scwSecretKey = $_ENV['SCW_SECRET_KEY'];
$scwRegion = $_ENV['SCW_REGION'];
$bucketName = $_ENV['BUCKET_NAME'];

$s3Client = new S3Client([
 'version' => 'latest',
 'region' => $scwRegion,
 'endpoint' => "https://s3.{$scwRegion}.amazonaws.com",
 // For other providers (i.e. DigitalOcean Spaces, or Scaleway), change this line:
 //'endpoint' => "https://{$bucketName}.{$scwRegion}.digitaloceanspaces.com",
 'credentials' => new Credentials($scwAccessKey, $scwSecretKey)
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $requestBody = file_get_contents('php://input');
 $data = json_decode($requestBody, true);
 $filename = $data['filename'];
 $contentType = $data['contentType'];
 $key = 'uploads/' . $filename; /* Edit here to change the path. Assume that files with the same filename will be overwritten, so develop a filepath strategy for your own use case */
 $expires = '+2 hours'; // If you expect uploads to take longer than 2 hours, increase this. 

 $options = [
 ['acl' => 'private'],
 ['bucket' => $bucketName],
 ['starts-with', '$key', $key],
 ['success_action_status' => '201'],
 ];

 $postObject = new PostObjectV4(
 $s3Client,
 $bucketName,
 ['key' => $key],
 $options,
 $expires
 );

 $formAttributes = $postObject->getFormAttributes();
 $formInputs = $postObject->getFormInputs();

 $response = [
 'method' => 'POST',
 'url' => $formAttributes['action'],
 'fields' => array_merge($formInputs, [
 'acl' => 'private',
 'success_action_status' => '201',
 ]),
 'headers' => [
 'Content-Type' => $contentType,
 ],
 ];

 header('Content-Type: application/json');
 echo json_encode($response);
 exit;
}

?>
