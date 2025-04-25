<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warhammer 40,000</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('https://cs13.pikabu.ru/post_img/big/2020/03/16/11/1584388241122032132.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: white;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: auto;
            gap: 10px;
            padding: 20px;
            box-sizing: border-box;
        }

        .grid-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            background-color: rgba(0, 0, 0, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .grid-item:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
        }

        .grid-item img {
            max-width: 100%;
            max-height: 150px;
            margin-bottom: 1rem;
            border-radius: 10px;
        }

        .grid-item:nth-child(5) {
            grid-row: span 2;
        }

        .grid-item:nth-child(6) {
            grid-column: span 2;
        }

        .grid-item:nth-child(7) {
            grid-column: 4;
            grid-row: span 2;
        }

        .red-bg {
            background-color: rgba(204, 0, 0, 0.8);
        }

        .blue-bg {
            background-color: rgba(0, 102, 204, 0.8);
        }

        .yellow-bg {
            background-color: rgba(204, 204, 0, 0.8);
        }

        .text-content {
            text-align: center;
        }

        .title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .text {
            font-size: 1rem;
            font-weight: normal;
            line-height: 1.5rem;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer a {
            color: white;
            text-decoration: none;
            font-size: 24px;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 5px;
            transition: opacity 0.3s ease;
        }

        .footer a:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="grid-container">
        <div class="grid-item red-bg">
            <div class="text-content">
                <p class="title">Імператор Людства</p>
                <p class="text">Ось уже понад сто століть Імператор нерухомо сидить на Золотому Троні Терри</p>
            </div>
        </div>
        <div class="grid-item blue-bg">
            <div class="text-content">
                <p class="title">Волею богів</p>
                <p class="text">Волею богів він є Повелителем Людства і править мільйоном світів завдяки могутності своїх незліченних армій.</p>
            </div>
        </div>
        <div class="grid-item yellow-bg">
            <div class="text-content">
                <p class="title">Гниючий напівтруп</p>
                <p class="text">Він - гниючий напівтруп, чиї незримі муки продовжуються загадковими пристроями Темної Ери Технологій.</p>
            </div>
        </div>
        <div class="grid-item red-bg">
            <div class="text-content">
                <p class="title">Володар Імперіума</p>
                <p class="text">Він — володар Імперіума, що розкладається, якому щодня приносять у жертву тисячу душ, чию кров він п'є і поїдає плоть.</p>
            </div>
        </div>
        <div class="grid-item blue-bg">
            <div class="text-content">
                <p class="title">На крові та людському тілі</p>
                <p class="text">На крові та людському тілі стоїть сам Імперіум.</p>
            </div>
        </div>
        <div class="grid-item yellow-bg">
            <img src="https://pm1.aminoapps.com/6824/6b9b64efc57d867560bd6abd9591bc52d32aa81dv2_hq.jpg" alt="Image 6">
            <div class="text-content">
                <p class="title">Імператор Людства</p>
                <p class="text">Імператор Людства, відомий також як Король Людства, Бог-Імператор або просто Імператор - засновник і правитель Імперіуму. Офіційним гербом Імператора та Імперіуму є аквіла.</p>
            </div>
        </div>
        <div class="grid-item red-bg">
            <div class="text-content">
                <p class="title">Бог Імперіума</p>
                <p class="text">Для переважної більшості людей Імператор є богом. Він - божество, що сидить на Золотому Троні Терри.</p>
            </div>
        </div>
        <div class="grid-item blue-bg">
            <div class="text-content">
                <p class="title">Провідник і правитель</p>
                <p class="text">Він — провідник, правитель, вища сила, якою вони підносять свої молитви про допомогу та визволення. Квадрильйони людей по всій Галактиці носять Його зображення і поклоняються Йому у незліченних формах.</p>
            </div>
        </div>
        <div class="grid-item yellow-bg">
            <div class="text-content">
                <p class="title">Культ Механікус</p>
                <p class="text">Культ Механікус шанує Його як «Омнісію», фізичне втілення Бога-Машини. Більшість збожеволіла б від розпачу, якби дізналася правду.</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <a href="index.php">Хаб</a>
    </div>
</body>

</html>
