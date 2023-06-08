<!DOCTYPE html>
<html>
<head>
    <title>Book Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }

        input[type="file"] {
            margin-top: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        h2 {
            margin-top: 40px;
            color: #333;
        }

        p {
            margin-bottom: 10px;
        }

        hr {
            margin-top: 20px;
            border: none;
            border-top: 1px solid #ccc;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Book Upload</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required><br><br>
        
        <label>Author:</label>
        <input type="text" name="author" required><br><br>
        
        <label>Description:</label>
        <textarea name="description" required></textarea><br><br>
        
        <label>Book File:</label>
        <input type="file" name="bookFile" required><br><br>
        
        <input type="submit" name="submit" value="Upload">
    </form>

    

<?php
$servername = "localhost";
$username = "root";
$password = ""; // Leave blank if you haven't set a password for your MySQL installation
$dbname = "book_database";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    
    $file = $_FILES['bookFile'];
    $filename = $file['name'];
    $tmpFilePath = $file['tmp_name'];

    // Specify the directory where you want to store the uploaded books
    $uploadDirectory = "C:/xampp/htdocs/uploads/";


    // Create a unique file name to avoid conflicts
    $uniqueFilename = uniqid() . '_' . $filename;
    $targetFilePath = $uploadDirectory . $uniqueFilename;

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($tmpFilePath, $targetFilePath)) {
        // File uploaded successfully, insert book details into the database
        $sql = "INSERT INTO books (title, author, description, filename, filepath) VALUES ('$title', '$author', '$description', '$filename', '$targetFilePath')";

        if ($conn->query($sql) === TRUE) {
            echo "Book details uploaded successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Retrieve the uploaded books from the database
$retrieveQuery = "SELECT * FROM books";
$result = $conn->query($retrieveQuery);

if ($result->num_rows > 0) {
    echo "<h2>Uploaded Books:</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<p>Title: " . $row['title'] . "</p>";
        echo "<p>Author: " . $row['author'] . "</p>";
        echo "<p>Description: " . $row['description'] . "</p>";
        echo "<p>Download: <a href='" . $row['filepath'] . "' target='_blank'>" . $row['filename'] . "</a></p>";
        echo "<hr>";
    }
} else {
    echo "<p>No books uploaded yet.</p>";
}

$conn->close();
?>


</body>
</html>
