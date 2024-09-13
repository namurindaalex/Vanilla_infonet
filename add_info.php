<?php
session_start();
$phone_number = $_SESSION['phone'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=VANILLA_INFONET", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_POST['add_district'])) {
            $district = $_POST['district'];
            $stmt = $pdo->prepare("UPDATE Details SET DISTRICT = :district WHERE PHONE_NUMBER = :phone_number");
            $stmt->bindParam(':district', $district);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->execute();
        }

        if (isset($_POST['add_email'])) {
            $email = $_POST['email'];
            $stmt = $pdo->prepare("UPDATE Details SET Email = :email WHERE PHONE_NUMBER = :phone_number");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->execute();
        }

        // Redirect back to the profile page after updating
        header("Location: profile.php");

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
