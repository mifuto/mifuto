<?php

require_once('classes/sendMailClass.php');
require_once('classes/DashboardClass.php');
require_once('config.php');
require_once('classes/vendor/autoload.php');

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$s3Client = new S3Client([
    'version'     => 'latest',
    'region'      => 'ap-south-2', // e.g., us-east-1
    'credentials' => [
        'key'    => AWS_KEY,
        'secret' => AWS_SECRET,
    ],
]);

$bucket = AWS_BUCKET;
$folder = isset($_GET['folder']) ? $_GET['folder'] : '';

if (empty($folder)) {
    die('No folder specified');
}

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
if($mode == 2) $folder = $folder ."/vedios";
else $folder = $folder ."/images";

$localDir = 'downloads/' . uniqid() . '/';

if (!file_exists($localDir)) {
    mkdir($localDir, 0777, true);
}

try {
    $objects = $s3Client->listObjectsV2([
        'Bucket' => $bucket,
        'Prefix' => $folder,
    ]);

    if ($objects['KeyCount'] > 0) {
        foreach ($objects['Contents'] as $object) {
            $key = $object['Key'];
            $fileName = basename($key);
            $localFilePath = $localDir . $fileName;

            $s3Client->getObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'SaveAs' => $localFilePath,
            ]);
        }
    } else {
        die("No objects found in the specified folder.");
    }

    $zipFileName = $localDir . 'folder.zip';
    createZip($localDir, $zipFileName);
    downloadZip($zipFileName);

} catch (AwsException $e) {
    die("Error: " . $e->getMessage());
}

function createZip($localDir, $zipFileName) {
    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        die("Cannot open <$zipFileName>");
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($localDir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($localDir));
            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();
}

function downloadZip($zipFileName) {
    if (file_exists($zipFileName)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
        header('Content-Length: ' . filesize($zipFileName));
        readfile($zipFileName);
        deleteDir(dirname($zipFileName));
        exit;
    } else {
        die("File <$zipFileName> does not exist.");
    }
}

function deleteDir($dirPath) {
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

?>
