<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'VANILLA_INFONET';

// Create a connection
$connection = new mysqli($host, $user, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch FAQs from the database
$sql = "SELECT question, answer FROM faqs";
$result = $connection->query($sql);

$faqs = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
}

// Close the connection
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs</title>
    <link rel="stylesheet" href="readfaq.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get FAQs from PHP embedded in the page
            const faqs = <?php echo json_encode($faqs); ?>;
            const faqContainer = document.querySelector('.faq-container');
            faqs.forEach(faq => {
                const faqItem = document.createElement('div');
                faqItem.className = 'faq-item';
                faqItem.innerHTML = `
                    <h2 class="faq-question">${faq.question}</h2>
                    <p class="faq-answer">${faq.answer}</p>
                `;
                faqContainer.appendChild(faqItem);
            });
        });
    </script>
</head>
<body>
    <header>
        <h1>Frequently Asked Questions</h1>
    </header>
    <main>
        <section class="faq-container">
            <!-- FAQ items will be inserted here by JavaScript -->
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Vanilla Portal. All rights reserved.</p>
    </footer>
</body>
</html>
