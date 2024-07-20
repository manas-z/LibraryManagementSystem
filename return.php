<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    
    echo '<script>window.location.href = "login.php";</script>';
    exit();
}


include 'database.php';

$memberId = $_SESSION['user'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_book'])) {
 
    $issuedBookId = isset($_POST['issued_book']) ? $_POST['issued_book'] : '';


    $returnDate = date("Y-m-d");
    $returnQuery = "UPDATE Book_Issued SET Return_Date = ? WHERE Book_id = ? AND Member_id = ?";
    $returnStmt = $conn->prepare($returnQuery);
    $returnStmt->bind_param("sii", $returnDate, $issuedBookId, $memberId);
    $returnResult = $returnStmt->execute();
    $returnStmt->close();

    
    if ($returnResult) {
 
        $returnBookQuery = "UPDATE Books SET Issued = 'Available' WHERE Book_id = $issuedBookId";
        $returnBookResult = $conn->query($returnBookQuery);

        
        if ($returnBookResult) {
            echo '<div style="text-align: center;">';
            echo "Book returned successfully!<br>";
            echo "Book ID: $issuedBookId<br>";
            echo "Return Date: $returnDate<br>";
            echo '</div>';  

        } else {
            echo "Error updating the Books table: " . $conn->error;
        }
    } else {
        echo "Error updating the Book_Issued table: " . $conn->error;
    }
}

                
$issuedBooksQuery = "SELECT Book_Issued.Book_id, Title, Issue_Date, Branches.Location FROM Book_Issued JOIN Books ON Book_Issued.Book_id = Books.Book_id JOIN Branches ON Books.Branch_id = Branches.Branch_id WHERE Member_id = $memberId AND Return_Date IS NULL";
$issuedBooksResult = $conn->query($issuedBooksQuery);


if ($issuedBooksResult && $issuedBooksResult->num_rows > 0) {
    echo '<form action="" method="post" style="text-align: center; margin: 0 auto; width: 50%;">';
    echo '<label for="issued_book" style="font-size: 30px; display: block;">Select an Issued Book to Return</label>';
    echo '<select name="issued_book" id="issued_book" style="font-size: 18px; border-radius: 10px; margin-bottom: 10px; width: 400px;">';
    echo '<option value="" disabled selected>Select Book to Return</option>';

    while ($issuedBook = $issuedBooksResult->fetch_assoc()) {
        $issuedBookId = $issuedBook['Book_id'];
        $issueDate = $issuedBook['Issue_Date'];
        $issuedBookTitle = $issuedBook['Title'];
        $issuedBranchLocation = $issuedBook['Location'];
        echo '<option value="' . $issuedBookId . '">' . "Book: $issuedBookTitle ($issuedBookId) - Location: $issuedBranchLocation Issued Date: $issueDate" . '</option>';
    }

    echo '</select><br>';
    echo '<input type="submit" name="return_book" value="Return Book" style="font-size: 18px; border-radius: 10px;">';
    echo '</form>';
} else {
    echo '<div style="text-align: center;">';
    echo 'No books are currently issued by the user.';
    echo '<form action="index.php">';
    echo '<button type="submit">Go to Dashboard</button>';
    echo '</form>';
    echo '</div>';  
}


$conn->close();
?>
