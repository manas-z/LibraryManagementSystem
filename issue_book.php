<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
   
    echo '<script>window.location.href = "login.php";</script>';
    exit();
}


include 'database.php';

$memberId = $_SESSION['user'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $bookId = isset($_POST['book']) ? $_POST['book'] : '';


    $query = "SELECT books.Book_id, books.Title, books.Branch_id, branches.Location
              FROM Books 
              JOIN Branches  ON books.Branch_id = branches.Branch_id
              WHERE books.Book_id = $bookId";
    $result = $conn->query($query);

   
    if ($result && $result->num_rows > 0) {
        $bookInfo = $result->fetch_assoc();
        $bookTitle = $bookInfo['Title'];
        $branchLocation = $bookInfo['Location'];
        $branchId = $bookInfo['Branch_id'];

        
        $updateQuery = "UPDATE Books SET Issued = 'Issued' WHERE Book_id = $bookId";
        $updateResult = $conn->query($updateQuery);
        $member_id = $_SESSION['user'];
       
        $issueDate = date("Y-m-d");
        $insertQuery = "INSERT INTO Book_Issued (Issue_Date, Book_id, Member_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sii", $issueDate, $bookId, $memberId); 
        $insertResult = $stmt->execute();
        $stmt->close();

       
        if ($updateResult && $insertResult) {
            echo '<div style="text-align: center;">';
            echo "Book issued successfully!<br>";
            echo "Book Title: $bookTitle<br>";
            echo "Branch ID: $branchId<br>";
            echo "Branch Location: $branchLocation<br>";
            echo "Issue Date: $issueDate";
            echo '<form action="index.php">';
            echo '<button type="submit">Go back to Dashbord</button>';
            echo '</form>';
            echo '</div>';  
        } else {
            echo "Error updating the database: " . $conn->error;
        }
    } else {
        echo "Error fetching book information: " . $conn->error;
    }
}


$conn->close();
?>
