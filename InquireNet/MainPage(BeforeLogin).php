<?php
include('db.php'); 

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; 

    $stmt = $conn->prepare("SELECT user_id, username, password FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: MainPage(AfterLogin).php");
            exit();
        } else {
            $loginError = "Invalid username or password.";
        }
    } else {
        $loginError = "Invalid username or password.";
    }
}


$recentQuestionsQuery = "
SELECT Q.question_id, Q.question_text, Q.creation_date, COUNT(A.answer_id) AS answer_count
FROM Questions Q
LEFT JOIN Answers A ON Q.question_id = A.question_id
GROUP BY Q.question_id
ORDER BY Q.creation_date DESC
LIMIT 5";

$recentQuestions = mysqli_query($conn, $recentQuestionsQuery);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Main Page - Before Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
            <div class="banner">
                <a href="MainPage(BeforeLogin).html">
                    <img src="images/InquireNetIcon.png" class="logo">
                </a>
                <div class="search-container">
                    <input type="text" placeholder="Search..." id="searchBox">
                    <button type="submit">Search</button>
                </div>
            </div>
    </header>

    <div class="container">
        <h1>Welcome to InquireNet</h1>

        <section class="login-form">
        <?php if (isset($loginError)): ?>
        <p class="error-text"><?php echo $loginError; ?></p>
        <?php endif; ?>

            <h2>Login</h2>
            <form id="loginForm" method="post" class="auth-form" action="MainPage(BeforeLogin).php">
               
                <input id='username' type="text" name="username" placeholder="Username">
                <p id="error-text-username" class="error-text hidden">Username is Invalid</p>

                <input id='password' type="password" name="password" placeholder="Password">
                <p id="error-text-password" class="error-text hidden">Password is Invalid</p>

                <input type="submit" value="Login" id="loginButton">
            </form>
            <a href="SignUp.html">Don't have an account? Sign up</a>
        </section>

        <h2>Recent Questions</h2>
    <ul>
        <?php while($question = mysqli_fetch_assoc($recentQuestions)): ?>
            <li>
                <img src="images/profile.png" class="user-avatar">
                <a href="QuestionDetail.php?question_id=<?php echo $question['question_id']; ?>">
                    <?php echo htmlspecialchars($question['question_text']); ?>
                </a>
                <div class="question-info">
                    <span>Date: <?php echo $question['creation_date']; ?></span>
                    <span>Answers: <?php echo $question['answer_count']; ?></span>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
</section>

    </div>

    <footer>
        <p>&copy; 2024 InquireNet</p>
    </footer>
    <script src="js/eventHandlers.js"></script>
    <script src="js/eventRegisterLogin.js"></script>
</body>

</html>
