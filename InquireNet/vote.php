<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $answerId = isset($_POST['answerId']) ? (int)$_POST['answerId'] : 0;
    $voteType = $_POST['voteType'] === 'true' ? 1 : 0;
    $userId = (int)$_SESSION['user_id']; 

    $stmt = $conn->prepare("SELECT vote_id FROM Votes WHERE user_id = ? AND answer_id = ?");
    $stmt->bind_param("ii", $userId, $answerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $stmt = $conn->prepare("UPDATE Votes SET vote_type = ? WHERE user_id = ? AND answer_id = ?");
        $stmt->bind_param("iii", $voteType, $userId, $answerId);
    } else {

        $stmt = $conn->prepare("INSERT INTO Votes (answer_id, user_id, vote_type) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $answerId, $userId, $voteType);
    }
    $stmt->execute();
    $stmt->close();


    echo "Vote recorded.";
}
?>
