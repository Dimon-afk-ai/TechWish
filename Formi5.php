<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warhammer 40,000 Theme with Flexbox</title>
    <style>
        body {
            background-color: #0b0c10;
            color: #c5c6c7;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
           /* display: flex;*/
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        .resize-button {
            padding: 10px 20px;
            font-size: 64px;
            align-items: center;
            background-color: #4CAF50;
            color: white;
            border: none;
            margin: 0 auto;
            display: block;
            cursor: pointer;
            transition: transform 0.3s ease; /* Плавная анимация изменения размера */
        }

        .resize-button:hover {
            transform: scale(1.2); /* Увеличение размера кнопки при наведении */
        
        }

        header {
            background-color: #1f2833;
            width: 100%;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            color: #66fcf1;
        }
        .content {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }
        .card {
            background-color: #45a29e;
            border: 1px solid #66fcf1;
            border-radius: 10px;
            margin: 10px;
            padding: 20px;
            width: 300px;
            text-align: center;
        }
        .card img {
            width: 100%;
            border-bottom: 1px solid #66fcf1;
            margin-bottom: 15px;
            border-radius: 10px 10px 0 0;
        }
        .card h2 {
            color: #1f2833;
            margin: 0;
        }
        .card p {
            color: #0b0c10;
        }
        footer {
            background-color: #1f2833;
            width: 100%;
            padding: 10px;
            text-align: center;
            position: absolute;
            bottom: 0;
        }
        footer p {
            margin: 0;
            color: #66fcf1;
        }
  .image-container {
    position: relative;
    overflow: hidden;
  }
  
  .image-container img {
    transition: transform 0.3s;
  }
  
  .image-container:hover img {
    transform: scale(1.2);
  }
  .card  {
    width: 350px;
    height: 350px;
    background-color: lightblue;
    transition: width 0.3s ease, height 0.3s ease; /* Плавная анимация изменения размеров */
  }
  .card:hover {
    width: 400px; /* Новая ширина при наведении */
    height: 375px; /* Новая высота при наведении */

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
</style>
    </style>
</head>
<body>
    <header>
        <h1>Warhammer 40,000</h1>
    </header>
    <nav>
        <ul>
            <li style="position: relative;text-align: center;"> </header>
            <a class="button" href="index.php">Хаб</a></li>
            <footer>
        </ul>
    </nav>
    <div class="content">
        <div class="card">
            <img src="https://warhammergames.ru/_pu/5/s68893767.jpg" alt="Space Marine" width="367px" height="200px" class="zoom-image">
            <h2>Space Marine</h2>
            <p>Елітні воїни Імперіуму, одягнені в силову броню.</p>
        </div>
        <div class="card">
            <img  src="https://31.media.tumblr.com/0316998e1149d899462a950aedfa2331/tumblr_mwqsxoo3mk1so4uslo1_1280.jpg" alt="Chaos Warrior" width="367px" height="200px" class="zoom-image">
            <h2>Chaos Warrior</h2>
            <p>Загиблі воїни, що служать темним богам Хаосу.</p>
        </div>
        <div class="card">
            <img src="https://i.pinimg.com/736x/44/4d/96/444d96c6d2c8c4ec794c54f1a291a5c3.jpg" alt="Eldar" width="367px" height="200px" class="zoom-image">
            <h2>Eldar</h2>
            <p>Стародавня та могутня раса з передовими технологіями.</p>
        </div>
    </div>
    <footer>
        <p>For the Emperor!</p>
    </footer>
</body><body>
    <button class="resize-button" onclick="window.location.href = 'https://warhammer40000.com/';" >Грати Зараз</button>
</body>
</html>
