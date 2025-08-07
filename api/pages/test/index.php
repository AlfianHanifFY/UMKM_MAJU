<?php
// test_connection.php - Place this in your root directory
require_once __DIR__ . '/../../model/user.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";

// Test 1: Check if config file exists
$config_path = __DIR__ . '/../../config/config.php';
echo "<h3>Test 1: Config File</h3>";
echo "Looking for config at: $config_path<br>";

if (file_exists($config_path)) {
    echo "✓ Config file found<br>";
    $config = require $config_path;
    
    if (isset($config['DB_URL'])) {
        echo "✓ DB_URL found in config<br>";
        echo "DB_URL: " . substr($config['DB_URL'], 0, 20) . "...<br>";
    } else {
        echo "✗ DB_URL not found in config<br>";
        echo "Config contents: <pre>" . print_r($config, true) . "</pre>";
    }
} else {
    echo "✗ Config file not found<br>";
    die("Cannot proceed without config file");
}

// Test 2: Check PostgreSQL extension
echo "<h3>Test 2: PostgreSQL Extension</h3>";
if (extension_loaded('pgsql')) {
    echo "✓ PostgreSQL extension is loaded<br>";
} else {
    echo "✗ PostgreSQL extension is NOT loaded<br>";
    die("PostgreSQL extension is required");
}

// Test 3: Test database connection
echo "<h3>Test 3: Database Connection</h3>";
try {
    $conn = pg_connect($config['DB_URL']);
    if ($conn) {
        echo "✓ Database connection successful<br>";
        
        // Test 4: Check if table exists
        echo "<h3>Test 4: Table Structure</h3>";
        $table_check = pg_query($conn, "SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'tu_user' ORDER BY ordinal_position");
        
        if ($table_check && pg_num_rows($table_check) > 0) {
            echo "✓ tu_user table found<br>";
            echo "<strong>Table structure:</strong><br>";
            while ($row = pg_fetch_assoc($table_check)) {
                echo "- {$row['column_name']}: {$row['data_type']}<br>";
            }
        } else {
            echo "✗ tu_user table not found<br>";
            echo "Available tables:<br>";
            $tables = pg_query($conn, "SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
            while ($row = pg_fetch_assoc($tables)) {
                echo "- {$row['tablename']}<br>";
            }
        }
        
        // Test 5: Test insert with dummy data
        echo "<h3>Test 5: Test Insert</h3>";
        $test_email = "test_" . time() . "@example.com";
        $test_pass = password_hash("testpass", PASSWORD_DEFAULT);
        $test_name = "Test User";
        
        // $insert_sql = "INSERT INTO tu_user (email, password, display_name) VALUES ($1, $2, $3)";
        // $insert_result = pg_query_params($conn, $insert_sql, [$test_email, $test_pass, $test_name]);
        
        $insert_result = add_user($test_email,$test_pass,$test_name);
        
        if ($insert_result) {
            echo "✓ Test insert successful<br>";
            
            // Clean up test data
            $delete_sql = "DELETE FROM tu_user WHERE email = $1";
            pg_query_params($conn, $delete_sql, [$test_email]);
            echo "✓ Test data cleaned up<br>";
        } else {
            echo "✗ Test insert failed: " . pg_last_error($conn) . "<br>";
        }
        
        pg_close($conn);
    } else {
        echo "✗ Database connection failed: " . pg_last_error() . "<br>";
    }
} catch (Exception $e) {
    echo "✗ Database connection error: " . $e->getMessage() . "<br>";
}

echo "<h3>Summary</h3>";
echo "If all tests pass, your registration should work. If any test fails, that's where the problem is.<br>";
echo "After fixing issues, test the registration form again.<br>";
?>