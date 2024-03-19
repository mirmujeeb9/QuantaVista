<?php
// Database configuration settings
$host = 'localhost'; // e.g., 'localhost'
$dbname = 'quanfwxa_quantavista';
$username = 'quanfwxa_mujeeb';
$password = 'Mujeeb10';

// Attempt to connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Function to redirect with delay
function redirect_with_delay($url, $delay) {
    echo "<meta http-equiv='refresh' content='{$delay};URL={$url}'>";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    $number = htmlspecialchars(strip_tags(trim($_POST['number'])));
    $subject = htmlspecialchars(strip_tags(trim($_POST['subject'])));
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    // Basic validation
    if(empty($name) || empty($email) || empty($number) || empty($subject) || empty($message)) {
        // Redirect back to the form page with an error message
        echo "<script>alert('Please fill all required fields.'); window.location.href = 'index.html';</script>";
        exit;
    } else {
        try {
            // Prepare an insert statement
            $sql = "INSERT INTO msgform (name, email, number, subject, message) VALUES (:name, :email, :number, :subject, :message)";
            
            $stmt = $pdo->prepare($sql);
            
            // Bind parameters to statement
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':number', $number);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':message', $message);
            
            // Execute the prepared statement
            $stmt->execute();

            // Alert the user and redirect
            echo "<script>alert('Our team will contact you soon.');</script>";
            redirect_with_delay('index.html', 3);
        } catch(PDOException $e) {
            // If an error occurs, display it and redirect back to the form page
            echo "<script>alert('Error: " . str_replace("'", "\'", $e->getMessage()) . "'); window.location.href = 'index.html';</script>";
            exit;
        }
    }
} else {
    // Redirect back to the form page if the script is accessed without posting form data
    header('Location: index.html');
    exit;
}
?>
