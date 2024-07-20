<?php
session_start();


if (!isset($_SESSION['loggedin'])) {
    
    header("Location: login.php");
    exit();
}


include 'database.php';
$memberId = $_SESSION['user'];

$sql = "SELECT books.Book_id, books.Title, books.Branch_id, branches.Location
        FROM Books 
        JOIN Branches ON books.Branch_id = branches.Branch_id
        WHERE books.Issued = 'Available'";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    echo '<form action="issue_book.php" method="post" select id="customDropdown" style="text-align: center; margin: 0 auto; width: 50%;">'; // Create a new PHP script (issue_book.php) to handle the form submission

    echo '<label for="book" style="font-size: 50px; display: block;">Select a Book</label>';
    echo '<select name="book" id="book" style="font-size: 18px; border-radius: 10px; margin-bottom: 10px; width: 300px;">';
    echo '<option value="" disabled selected>Select a Book</option>';
    
    while ($row = $result->fetch_assoc()) {
       $optionText = $row['Location'] . ' - ' . $row['Title'];
       echo '<option value="' . $row['Book_id'] . '">' . $optionText . '</option>';
    }
    echo '</select><br>';
    echo '<input type="submit" name="issue" value="Issue" style="font-size: 18px; border-radius: 10px;">';
    echo '</form>';
} else {
    echo 'No available books found in the database.';
}


$conn->close();
?>
<script>
$(document).ready(function() {
    $('#customDropdown').select2();
});
</script>
