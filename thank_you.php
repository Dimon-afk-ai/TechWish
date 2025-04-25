<?php
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "warhammer_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['user']);
    $nickname = $conn->real_escape_string($_POST['nickname']);
    $email = $conn->real_escape_string($_POST['Poshta']);
    $choice = $conn->real_escape_string($_POST['Politika']);

    $sql = "INSERT INTO warhammer_db (name, nickname, email, choice) 
            VALUES ('$name', '$nickname', '$email', '$choice')";

    if ($conn->query($sql) === TRUE) {
        echo "<!DOCTYPE html>
        <html lang='uk'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Дякуємо!</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f9f9f9;
                    color: #333;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .container {
                    text-align: center;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    padding: 30px;
                    width: 80%;
                    max-width: 400px;
                }
                .message-box {
                    background-color: #4CAF50;
                    color: white;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    margin-bottom: 30px;
                }
                .message-box h2 {
                    font-size: 24px;
                    margin-bottom: 10px;
                }
                .message-box p {
                    font-size: 16px;
                    margin-bottom: 20px;
                }
                a {
                    text-decoration: none;
                    background-color: #007bff;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 5px;
                    font-size: 16px;
                }
                a:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='message-box'>
                    <h2>Дякуємо за відповідь!</h2>
                    <p>Чекайте відповідь на електронну адресу.</p>
                    <a href='index.php'>На головну</a>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 3000);
            </script>
        </body>
        </html>";
    } else {
        echo "<p>Помилка: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$conn->close();
?>
