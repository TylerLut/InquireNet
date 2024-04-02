<?php
include('db.php'); 


$recentQuestionsQuery = "
SELECT Q.question_id, Q.question_text, Q.creation_date, COUNT(A.answer_id) AS answer_count
FROM Questions Q
LEFT JOIN Answers A ON Q.question_id = A.question_id
GROUP BY Q.question_id
ORDER BY Q.creation_date DESC
LIMIT 20";

$recentQuestionsResult = mysqli_query($conn, $recentQuestionsQuery);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Main Page - After Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="banner">
            <a href="MainPage(AfterLogin).html">
                <img src="images/InquireNetIcon.png" class="logo" >
            </a>
            <div class="search-container">
                <input type="text" placeholder="Search..." id="searchBox">
                <button type="submit">Search</button>
            </div>
            <div class="user-header-info">
                <img src="images/user-avatar.jpg" class="user-avatar">
                <span>Welcome, [Username]</span>
                <button onclick="location.href='MainPage(BeforeLogin).html'">Logout</button>
            </div>
            <style> 
                nav { margin-bottom: 20px}
            </style>
        </div>
    </header>

    <nav>
        <button onclick="location.href='QuestionManagement.html'">Question Management</button>
    </nav>

    <nav>
         <button onclick="location.href='QuestionCreation.html'">Ask a Question</button>
    </nav>

    <section id="recent-questions">
    <h2>20 Most Recent Questions</h2>
    <ul>
        <?php while($question = mysqli_fetch_assoc($recentQuestionsResult)): ?>
            <li>
                <img src="images/profile.png" class="user-avatar">
                <a href="QuestionDetail.php?question_id=<?php echo $question['question_id']; ?>">
                    <?php echo htmlspecialchars($question['question_text']); ?>
                </a>
                <div class="question-info">
                    <span>Date: <?
    /ul
    </section>

    <footer>
        <p>&copy; 2024 InquireNet</p>
    </footer>
</body>
</html>
