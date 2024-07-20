<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avid Readers - Search Books</title>
</head>
<body>
    <div style="text-align: center; margin-top: 50px;">
        <h2>Search books from our wide database</h2>

        <form action="#" method="post">
            <label for="book">Search a Book:</label>
            <select name="book" id="book">
                <option value="" disabled selected>Search a Book</option>

                <?php
                
                include 'database.php';

               
                $sql = "SELECT Book_id, Title FROM Books";
                $result = $conn->query($sql);

                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['Book_id'] . '">' . $row['Title'] . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No books available</option>';
                }

                
                $conn->close();
                ?>
            </select>

            <br>

        
        </form>
    </div>
</body>
</html>
