<?php
// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=VANILLA_INFONET', 'root', '');

// Check if post_id and action are set
if (isset($_POST['post_id']) && isset($_POST['action'])) {
    $postId = (int)$_POST['post_id'];
    $action = $_POST['action'];

    if ($action === 'like') {
        $pdo->query("UPDATE Posts SET Liking = Liking + 1 WHERE id = $postId");
        $stmt = $pdo->prepare("SELECT Liking FROM Posts WHERE id = ?");
        $stmt->execute([$postId]);
        $likes = $stmt->fetchColumn();
        echo $likes;
    } elseif ($action === 'share') {
        $pdo->query("UPDATE Posts SET Share = Share + 1 WHERE id = $postId");
        $stmt = $pdo->prepare("SELECT Share FROM Posts WHERE id = ?");
        $stmt->execute([$postId]);
        $shares = $stmt->fetchColumn();
        echo $shares;
    }
}
?>
