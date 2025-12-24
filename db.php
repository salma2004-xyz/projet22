<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'projet2');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Function to check if user is logged in
function requiresLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
}

// Function to check if user is admin
function isAdmin() {
    if (!isset($_SESSION['user_role'])) {
        return false;
    }
    return $_SESSION['user_role'] === 'admin';
}

// Function to check if user is student
function isStudent() {
    if (!isset($_SESSION['user_role'])) {
        return false;
    }
    return $_SESSION['user_role'] === 'student';
}

// Function to check if user is teacher
function isTeacher() {
    if (!isset($_SESSION['user_role'])) {
        return false;
    }
    return $_SESSION['user_role'] === 'teacher';
}

// Function to authenticate user and start session
function loginUser($username, $password) {
    global $conn;
    
    $username = mysqli_real_escape_string($conn, $username);
    $query = "SELECT id, username, password, role FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if (!$result || mysqli_num_rows($result) === 0) {
        return false;
    }
    
    $user = mysqli_fetch_assoc($result);
    
    // Verify password (assuming passwords are hashed with password_hash)
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        return true;
    }
    
    return false;
}

// Function to logout user
function logoutUser() {
    session_destroy();
    $_SESSION = [];
}

// Function to register a new user
function registerUser($username, $password, $role, $niveau = null, $groupe = null) {
    global $conn;
    
    // Validate inputs
    if (empty($username) || empty($password)) {
        return false;
    }
    
    // Check if username already exists
    $username = mysqli_real_escape_string($conn, $username);
    $query = "SELECT id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return false; // Username already exists
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedPassword = mysqli_real_escape_string($conn, $hashedPassword);
    $role = mysqli_real_escape_string($conn, $role);
    $niveau = $niveau ? mysqli_real_escape_string($conn, $niveau) : null;
    $groupe = $groupe ? mysqli_real_escape_string($conn, $groupe) : null;
    
    // Insert new user
    $query = "INSERT INTO users (username, password, role, niveau, groupe) 
              VALUES ('$username', '$hashedPassword', '$role', " . 
              ($niveau ? "'$niveau'" : "NULL") . ", " . 
              ($groupe ? "'$groupe'" : "NULL") . ")";
    
    return mysqli_query($conn, $query);
}
?>
