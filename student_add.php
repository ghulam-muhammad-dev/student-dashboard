<?php 
  require_once __DIR__ . "/config.php";
  require_once __DIR__ . "/db.php";
//  PHP code for adding a new student goes here
$errors = [];
if($_SERVER['REQUEST_METHOD']== "POST"){

   
    $First_name = trim($_POST['first_name']);
    $Last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $roll_number = trim($_POST['roll_number']);
    $class = trim($_POST['class']);
    $gender = trim($_POST['gender']);
    $dob = trim($_POST['dob']);
    $status = trim($_POST['status']);

    // Validate input
    if(empty($First_name) || empty($Last_name) || empty($email) || empty($phone) || empty($roll_number) || empty($class) || empty($gender) || empty($dob) || empty($status)){
        $errors[] = "All fields are required.";
        
    }
    //error array is not empty then redirect back to form
    if(!empty($errors)){
        $_SESSION['errors'] = $errors;
        header("Location: student-create.php");
        exit();
    }

    // Insert into database
   $stmt= mysqli_prepare($conn, "INSERT INTO students (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $First_name, $Last_name, $email, $phone);
    mysqli_stmt_execute($stmt);

    
     $student_id = mysqli_insert_id($conn);

    $stmt2 = mysqli_prepare($conn, "INSERT INTO academic (student_id, roll_number, class, gender, dob, status) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt2, 'isssss', $student_id, $roll_number, $class, $gender, $dob, $status);
    mysqli_stmt_execute($stmt2);

    $_SESSION['success'] = "Student record created successfully.";
    header("Location: student-create.php");
    exit;
}
?>