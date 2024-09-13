<?php
header('Content-Type: application/json');
include 'db.php';

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


// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the request method
$request_method = $_SERVER['REQUEST_METHOD'];

// Function to retrieve FAQs
function getFAQs($pdo) {
    try {
        $sql = 'SELECT * FROM faqs';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($faqs) {
            echo json_encode($faqs);
        } else {
            echo json_encode(['message' => 'No FAQs found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
    }
}

// Function to add a new FAQ
function addFAQ($pdo) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['question']) && isset($input['answer'])) {
        $question = $input['question'];
        $answer = $input['answer'];

        try {
            $sql = 'INSERT INTO faqs (question, answer) VALUES (:question, :answer)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['question' => $question, 'answer' => $answer]);

            echo json_encode(['message' => 'FAQ added successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid input.']);
    }
}

// Function to update an existing FAQ
function updateFAQ($pdo) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $input = json_decode(file_get_contents('php://input'), true);

    if ($id && json_last_error() === JSON_ERROR_NONE && isset($input['question']) && isset($input['answer'])) {
        $question = $input['question'];
        $answer = $input['answer'];

        try {
            $sql = 'UPDATE faqs SET question = :question, answer = :answer WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['question' => $question, 'answer' => $answer, 'id' => $id]);

            echo json_encode(['message' => 'FAQ updated successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid input or missing ID.']);
    }
}

// Function to delete an FAQ
function deleteFAQ($pdo) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($id) {
        try {
            $sql = 'DELETE FROM faqs WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);

            http_response_code(204);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid ID.']);
    }
}

// Routing based on request method
switch ($request_method) {
    case 'GET':
        // Display FAQs
        getFAQs($pdo);
        break;
    case 'POST':
        // Add new FAQ
        addFAQ($pdo);
        break;
    case 'PUT':
        // Update FAQ
        updateFAQ($pdo);
        break;
    case 'DELETE':
        // Delete FAQ
        deleteFAQ($pdo);
        break;
    default:
        echo json_encode(['error' => 'Unsupported request method.']);
        break;
}
?>
