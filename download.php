<?php
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || empty($_GET['url']) || empty($_GET['format'])) {
    die('Invalid request');
}

$url = $_GET['url'];
$format = $_GET['format'];

// Validate URL
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    die('Invalid URL');
}

// Execute Python script to download video
$command = "python3 download_video.py " . escapeshellarg($url) . " " . escapeshellarg($format);
exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    die('Failed to download video: ' . implode("\n", $output));
}

$downloadedFile = trim(end($output));

if (empty($downloadedFile) || !file_exists($downloadedFile)) {
    die('Failed to locate downloaded file');
}

// Set headers for download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($downloadedFile) . '"');
header('Content-Length: ' . filesize($downloadedFile));

// Output file contents
readfile($downloadedFile);

// Delete the file after download
unlink($downloadedFile);

