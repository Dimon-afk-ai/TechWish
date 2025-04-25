<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warhammer 40000</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #000;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            color: #ffcc00;
        }
        nav {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: #e0e0e0;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        nav a:hover {
            color: #ffcc00;
        }
        .container {
            padding: 20px;
        }
        .faction {
            margin-bottom: 40px;
            text-align: center;
        }
        .faction h2 {
            color: #ffcc00;
            margin-bottom: 20px;
        }
        .faction img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 20px;
            border-radius: 10px;
        }
        .faction p {
            text-align: justify;
        }
        .button-container {
            text-align: center;
            margin: 20px 0;
        }
        .button-container a {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #ffcc00;
            color: #1a1a1a;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .button-container a:hover {
            background-color: #e0b800;
        }
        .faction .select-button {
            background-color: #ffcc00;
            color: #1a1a1a;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .faction .select-button:hover {
            background-color: #e0b800;
        }
        footer {
            background-color: #000;
            color: #e0e0e0;
            text-align: center;
            padding: 10px 0;
            position: relative;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Warhammer 40000</h1>
    </header>
    <nav>
        <a href="#imperium">Імперіум Людства</a>
        <a href="#chaos">Хаос</a>
        <a href="#orks">Орки</a>
        <a href="#eldar">Ельдар</a>
    </nav>
    <div class="container">
        <section id="imperium" class="faction">
            <h2>Імперіум Людства</h2>
            <img src="https://warhammergames.ru/_pu/1/37879304.jpg" alt="Імперіум Людства">
            <p>Імперіум Людства є найбільшою фракцією в галактиці Warhammer 40000, керованою богоподібним Імператором. Їх армії складаються з Космічних Десантників, Імперської Гвардії та інших військових підрозділів.</p>
            <a href="Formi3.php" class="select-button">Вибрати</a>
        </section>
        <section id="chaos" class="faction">
            <h2>Хаос</h2>
            <img src="https://img1.reactor.cc/pics/post/Warhammer-40000-Chaos-Space-Marine-Chaos-%28Wh-40000%29-Khorne-3450075.jpeg" alt="Хаос">
            <p>Хаос - це сили, що поклоняються богам Хаосу, прагнуть руйнування та поневолення галактики. Вони складаються з зрадників Космічних Десантників, демонів та інших темних істот.</p>
            <a href="Formi3.php" class="select-button">Вибрати</a>
        </section>
        <section id="orks" class="faction">
            <h2>Орки</h2>
            <img src="https://img10.reactor.cc/pics/post/full/%D0%BD%D0%B5%D0%B9%D1%80%D0%BE%D0%BC%D0%B0%D0%B7%D0%BD%D1%8F-%D0%BD%D0%B5%D0%B9%D1%80%D0%BE%D0%BD%D0%BD%D1%8B%D0%B5-%D1%81%D0%B5%D1%82%D0%B8-MidJourney-Orks-8113014.png" alt="Орки">
            <p>Орки - це війскові дикі племена, які живуть лише заради війни. Вони відомі своїм величезним розміром, силою та жорстокістю, а також їх унікальною технікою та зброєю.</p>
            <a href="Formi3.php" class="select-button">Вибрати</a>
        </section>
        <section id="eldar" class="faction">
            <h2>Ельдар</h2>
            <img src="https://cs14.pikabu.ru/post_img/2022/06/10/1/1654818234156388469.jpg" alt="Ельдар">
            <p>Ельдар - це давня і загадкова раса з надзвичайно розвиненими психічними здібностями. Вони використовують свої технології та магію для захисту своїх світів від різних загроз галактики.</p>
            <a href="Formi3.php" class="select-button">Вибрати</a>
        </section>
    </div>
    <footer>
        <a href="index.php">Хаб</a>
    </footer>
</body>
</html>
