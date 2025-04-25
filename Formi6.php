<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warhammer 40,000 Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url();
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Fixed background image */
            background-image: url(https://www.kallisti.com.mx/wp-content/uploads/2017/06/WarComm-Cover-DarkImperiumLogov2-1600x800.jpg);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Added to align content vertically */
            height: 100vh;
        }
        .calculator {
            width: 400px;
            padding: 20px;
            border: 2px solid #ccc;
            border-radius: 5px;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); /* Box shadow */
            color: white;
            text-align: center;
            margin-bottom: 20px; /* Added margin for spacing */
        }
        input[type="text"] {
            width: calc(100% - 22px); /* Adjusting for the padding */
            margin-bottom: 10px;
            padding: 10px;
            box-sizing: border-box;
            font-size: 18px;
            background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent background */
            border: none;
            outline: none;
            color: white;
        }
        input[type="button"] {
            width: 48%;
            padding: 10px;
            margin-bottom: 5px;
            box-sizing: border-box;
            cursor: pointer;
            border: none;
            outline: none;
            font-size: 18px;
        }
        input[type="button"]:hover {
            opacity: 0.8;
        }
        a.button {
            background-color: green;
            color: black;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            border: 2px solid green;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        a.button:hover {
            background-color: #ffd700;
        }
        .footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
        }
        .footer a {
            color: white;
            text-decoration: none;
            font-size: 20px;
        }
    </style>
</head>
<body>

<h1 style="text-align: center; color: white; margin-top: 50px;">Как увеличить свой доход за 3 дня</h1>

<div class="calculator">
    <input type="text" id="display" disabled>
    <input type="button" value="7" onclick="addToDisplay('7')" style="color: blue;">
    <input type="button" value="8" onclick="addToDisplay('8')" style="color: gold;">
    <input type="button" value="9" onclick="addToDisplay('9')" style="color: blue;">
    <input type="button" value="/" onclick="addToDisplay('/')" style="color: gold;">
    <br>
    <input type="button" value="4" onclick="addToDisplay('4')" style="color: blue;">
    <input type="button" value="5" onclick="addToDisplay('5')" style="color: gold;">
    <input type="button" value="6" onclick="addToDisplay('6')" style="color: blue;">
    <input type="button" value="*" onclick="addToDisplay('*')" style="color: orange;">
    <br>
    <input type="button" value="1" onclick="addToDisplay('1')" style="color: blue;">
    <input type="button" value="2" onclick="addToDisplay('2')" style="color: green;">
    <input type="button" value="3" onclick="addToDisplay('3')" style="color: blue;">
    <input type="button" value="-" onclick="addToDisplay('-')" style="color: red;">
    <br>
    <input type="button" value="0" onclick="addToDisplay('0')" style="color: blue;">
    <input type="button" value="." onclick="addToDisplay('.')" style="color: red;">
    <input type="button" value="=" onclick="calculate()" style="color: green;">
    <input type="button" value="+" onclick="addToDisplay('+')" style="color: gold;">
    <br>
    <input type="button" value="C" onclick="clearDisplay()" style="color: red;">
</div>

<script>
    function addToDisplay(value) {
        document.getElementById('display').value += value;
    }

    function calculate() {
        let result = eval(document.getElementById('display').value);
        document.getElementById('display').value = result;
    }

    function clearDisplay() {
        document.getElementById('display').value = '';
    }
</script>

</header>
    <nav>
    <a class="button" href="index.php">Хаб</a></li>
    </nav>
    <footer>

</body>
</html>
