<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db.php";

$errors = [];
if($_SERVER['REQUEST_METHOD']== 'POST'){
    $email = trim($_POST['email']) ?? ''; 
    $password = $_POST['password'];

    // check if email and password are empty
    if(empty($email) || empty($password)){
        $errors[] = "Email and Password are required";
    }

    // if there are errors then return back to form
    if(!empty($errors)){
        $_SESSION['errors'] = $errors;
        header("Location: login.php");
        exit;
    }

    // check if email and password match
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) == 0){
        $errors[] = "Invalid email or password";
        $_SESSION['errors'] = $errors;
        header("Location: login.php");
        exit;
    }

    $user = mysqli_fetch_assoc($result);
    if(!password_verify($password, $user['password'])){
        $errors[] = "Invalid email or password";
        $_SESSION['errors'] = $errors;
        header("Location: login.php");
        exit;
    }

    // if login is successful, set session variables
    $_SESSION['user_id'] = $user['user_id'];
    // $_SESSION['user_name'] = $user['name'];
    // $_SESSION['user_email'] = $user['email'];

    header("Location: dashboard.php");
    exit;
}
?>