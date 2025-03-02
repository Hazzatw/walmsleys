<?php
// Set the XML file path
$xmlFile = 'comments.xml';

// Function to get comments from XML
function getComments() {
    global $xmlFile;
    if (file_exists($xmlFile)) {
        return simplexml_load_file($xmlFile);
    } else {
        // If file doesn't exist, create an empty XML structure
        $xml = new SimpleXMLElement('<comments></comments>');
        return $xml;
    }
}

// Function to save new comment to XML
function saveComment($name, $text) {
    global $xmlFile;

    $xml = getComments();

    // Add new comment
    $comment = $xml->addChild('comment');
    $comment->addChild('name', htmlspecialchars($name));
    $comment->addChild('text', htmlspecialchars($text));
    $comment->addChild('timestamp', date('c')); // ISO 8601 format for timestamp

    // Save XML file
    $xml->asXML($xmlFile);
}

// Check if a comment is being posted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['comment'])) {
    // Sanitize inputs to prevent XSS
    $name = htmlspecialchars($_POST['name']);
    $comment = htmlspecialchars($_POST['comment']);

    // Save the comment
    saveComment($name, $comment);
    echo 'Comment saved successfully';
    exit;
}

// Output the current comments as JSON for the frontend
$comments = getComments();
header('Content-Type: application/json');
echo json_encode($comments);
?>
