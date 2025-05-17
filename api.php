<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$dbHost = 'sql202.infinityfree.com';
$dbUser = 'if0_39004783';
$dbPass = 'wvqFo5dcogk4GOw';
$dbName = 'if0_39004783_names_generator';

// Connect to database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
}

// Handle POST requests (logging names)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    parse_str($input, $data);
    
    if (isset($data['action']) && $data['action'] === 'log' && isset($data['name']) && isset($data['gender'])) {
        $name = trim($data['name']);
        $gender = $data['gender'] === 'male' ? 'male' : 'female';
        
        try {
            $stmt = $pdo->prepare("INSERT INTO generated_names (name, gender) VALUES (:name, :gender)");
            $stmt->execute([':name' => $name, ':gender' => $gender]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Failed to log name: ' . $e->getMessage()]);
        }
        exit;
    }
}

// Handle GET requests (generating names)
if (isset($_GET['type']) && in_array($_GET['type'], ['male', 'female'])) {
    $type = $_GET['type'];
    
    // Read names from files
    try {
        $firstNameFile = $type . '_names.txt';
        $lastNameFile = $type . '_last_names.txt';
        
        if (!file_exists($firstNameFile) {
            throw new Exception("First name file not found");
        }
        
        if (!file_exists($lastNameFile)) {
            throw new Exception("Last name file not found");
        }
        
        $firstNames = file($firstNameFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lastNames = file($lastNameFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if (empty($firstNames) {
            throw new Exception("No first names available");
        }
        
        if (empty($lastNames)) {
            throw new Exception("No last names available");
        }
        
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        
        echo json_encode([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'fullName' => $firstName . ' ' . $lastName
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>