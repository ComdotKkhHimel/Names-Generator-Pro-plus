<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'name_generator';

// Connect to database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'get_stats') {
        // Return statistics
        try {
            $stmt = $pdo->query("SELECT gender, count FROM name_stats");
            $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $result = [
                'success' => true,
                'total' => 0
            ];
            
            foreach ($stats as $stat) {
                $result[$stat['gender']] = $stat['count'];
                $result['total'] += $stat['count'];
            }
            
            echo json_encode($result);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Failed to get stats: ' . $e->getMessage()]);
        }
        exit;
    }
    
    // Handle name generation requests
    if (isset($_GET['type']) && in_array($_GET['type'], ['male', 'female'])) {
        $type = $_GET['type'];
        $response = [];
        
        // Function to read names from file with caching
        function getNamesFromFile($filename) {
            static $cache = [];
            
            if (isset($cache[$filename])) {
                return $cache[$filename];
            }
            
            if (file_exists($filename)) {
                $names = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $names = array_filter($names, function($name) {
                    return trim($name) !== '';
                });
                
                // Cache the result
                $cache[$filename] = $names;
                return $names;
            }
            
            return [];
        }
        
        // Get names from text files
        $firstNames = getNamesFromFile($type . '_names.txt');
        $lastNames = getNamesFromFile($type . '_last_names.txt');
        
        if (empty($firstNames) || empty($lastNames)) {
            $response['error'] = 'Name database not available';
        } else {
            // Get random names
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            
            $response = [
                'success' => true,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'fullName' => $firstName . ' ' . $lastName
            ];
        }
        
        echo json_encode($response);
        exit;
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    parse_str($input, $data);
    
    if (isset($data['action']) && $data['action'] === 'log' && isset($data['name']) && isset($data['gender'])) {
        $name = trim($data['name']);
        $gender = $data['gender'] === 'male' ? 'male' : 'female';
        
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Log the generated name
            $stmt = $pdo->prepare("INSERT INTO generated_names (name, gender) VALUES (:name, :gender)");
            $stmt->execute([':name' => $name, ':gender' => $gender]);
            
            // Update statistics
            $stmt = $pdo->prepare("UPDATE name_stats SET count = count + 1 WHERE gender = :gender");
            $stmt->execute([':gender' => $gender]);
            
            $pdo->commit();
            
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }
}

// Invalid request
echo json_encode(['error' => 'Invalid request']);
?>
