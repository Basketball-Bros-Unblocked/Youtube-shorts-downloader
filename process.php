<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['url'])) {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$url = $_POST['url'];

// Validate URL
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(['error' => 'Invalid URL']);
    exit;
}

// Execute Python script to get video info
$command = "python3 get_video_info.py " . escapeshellarg($url);
$output = shell_exec($command);

if (empty($output)) {
    echo json_encode(['error' => 'Failed to retrieve video information']);
    exit;
}

$videoInfo = json_decode($output, true);

if (empty($videoInfo) || isset($videoInfo['error'])) {
    echo json_encode(['error' => $videoInfo['error'] ?? 'Failed to parse video information']);
    exit;
}

echo json_encode($videoInfo);

