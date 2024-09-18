

<?php
if (isset($_POST['search'])) {
    $search = $_POST['search'];

    // Database connection details
    $host = 'localhost'; // Your host
    $db = 'ekattor'; // Your database name
    $user = 'root'; // Your username
    $pass = ''; // Your password

    // Connect to MySQL
    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to search for student names
    $stmt = $conn->prepare("SELECT name FROM users WHERE name LIKE ?");
    $searchParam = '%' . $search . '%';
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    print_r($result);
    die();
    // Collect search results
    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = ['name' => $row['name']];
    }

    // Return results as JSON
    echo json_encode($suggestions);

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>
