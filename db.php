<?php
/**
 * @OA\Get(
 *     path="/get_faqs.php",
 *     summary="Retrieve a list of FAQs",
 *     @OA\Response(
 *         response=200,
 *         description="A list of FAQs",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="question", type="string"),
 *                 @OA\Property(property="answer", type="string")
 *             )
 *         )
 *     )
 * )
 */

$host = 'localhost';
$dbname = 'vanilla_infonet';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
