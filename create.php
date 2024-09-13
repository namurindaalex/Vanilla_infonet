<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'VANILLA_INFONET';

try {
    // Connect to MySQL server without specifying a database
    $pdo = new PDO("mysql:host=$db_host;", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the database exists, if not create it
    $createDbQuery = "CREATE DATABASE IF NOT EXISTS $db_name";
    $pdo->exec($createDbQuery);

    // Connect to the newly created database
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create the "Details" table if it does not exist
        $createDetailsTableQuery = "
        CREATE TABLE IF NOT EXISTS Details (
            id INT AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255),
            ROLE VARCHAR(255),
            PHONE_NUMBER VARCHAR(255),
            PASSWORD VARCHAR(255),
            DISTRICT VARCHAR(255),
            Email VARCHAR(255),
            Price DECIMAL(20, 2),  /* Price field before profile_photo */
            profile_photo VARCHAR(255)
        ) ENGINE=InnoDB;
        ";
        $pdo->exec($createDetailsTableQuery);

    // Create the "Events" table if it does not exist
    $createEventsTableQuery = "
        CREATE TABLE IF NOT EXISTS Events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            day INT,
            month VARCHAR(255),
            Description TEXT,
            Venue VARCHAR(255),
            More_info TEXT
        ) ENGINE=InnoDB;
    ";
    $pdo->exec($createEventsTableQuery);

    // Create the "Posts" table if it does not exist
    $createPostsTableQuery = "
        CREATE TABLE IF NOT EXISTS Posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            IMAGE VARCHAR(255),
            Comment TEXT,
            Liking INT,
            Share INT
        ) ENGINE=InnoDB;
    ";
    $pdo->exec($createPostsTableQuery);

    // Create the "FAQS" table if it does not exist
    $createFaqsTableQuery = "
        CREATE TABLE IF NOT EXISTS FAQS (
            id INT AUTO_INCREMENT PRIMARY KEY,
            Question TEXT,
            Answer TEXT
        ) ENGINE=InnoDB;
    ";
    $pdo->exec($createFaqsTableQuery);

} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
