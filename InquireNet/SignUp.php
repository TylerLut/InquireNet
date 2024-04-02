<?php

session_start();

include('db.php');

function redirectToMainPage() {
    header('Location: MainPage(AfterLogin).html');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['screenName'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $dob = $_POST['dob'];


    if ($password !== $confirmPassword) {
        $_SESSION['message'] = 'Passwords do not match.';
        redirectToMainPage();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $avatarUrl = null; 
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $targetDir = "uploads/"; 
        $targetFile = $targetDir . basename($_FILES["avatar"]["name"]);
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
            $avatarUrl = $targetFile; 
        } else {
            $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            header("Location: SignUp.php"); 
            exit();
        }
    }


    $stmt = $conn->prepare("INSERT INTO Users (email, username, password, avatar_url, birthday) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $email, $username, $hashedPassword, $avatarUrl, $birthday);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Registered successfully. Please log in.';
        header("Location: MainPage(AfterLogin).html"); 
    } else {
        $_SESSION['message'] = 'Error: ' . $stmt->error;
        header("Location: SignUp.php"); 
    }

    $stmt->close();
    mysqli_close($conn);
} else {

    $_SESSION['message'] = 'Invalid request method.';
    header("Location: SignUp.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Sign-up Page</title>
</head>
<body>
    <header>
        <div class="banner">
            <img src="images/InquireNetIcon.png" class="logo">
            </div>
        </div>
    </header>

    <section class="container">
        <h2>Sign Up</h2>

        <?php if(isset($_SESSION['message'])): ?>
            <p class="error-text"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php endif; ?>

        <form id="signupForm" action="SignUp.php" method="post" enctype="multipart/form-data">
        <input type="email" name="email" required placeholder="Email">
        <input type="text" name="username" required placeholder="Username">
        <input type="password" name="password" required placeholder="Password">
        <input type="password" name="confirmPassword" required placeholder="Confirm Password">
        <input type="date" name="birthday" required placeholder="Birthday">
        <input type="file" name="avatar" placeholder="Avatar Image">
        <button type="submit">Sign Up</button>
        
        </form>

    </section>

    <footer>
        <p>&copy; 2024 InquireNet</p>
    </footer>
    <script src="js/eventHandlers.js"></script>
    <script src="js/eventRegisterSignUp.js"></script>
</body>
</html>