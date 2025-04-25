<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Games</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-image: url(https://scontent.fods5-1.fna.fbcdn.net/v/t39.30808-6/322690495_1618100548633899_4158737306095653989_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=5f2048&_nc_ohc=b8pzGD2xdtwQ7kNvgEtr_JR&_nc_ht=scontent.fods5-1.fna&oh=00_AYAMzUiW1hPOc-4nw_5abfOVLR84PC52cqQypAWIpTNqyQ&oe=666D3986);
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    flex-direction: column; /* Чтобы элементы выравнивались вертикально */
  }
  .container {
    display: grid;
    grid-template-columns: repeat(3, 100px);
    grid-template-rows: repeat(3, 100px);
    gap: 2px;
    width: 306px;
    margin-bottom: 20px; /* Добавим отступ внизу для разделения калькулятора и ссылки */
  }
  .cell {
    border: 3px solid black; /* Сделаем границы толще */
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 2em;
    font-weight: bold; /* Добавим жирность тексту */
    cursor: pointer;
    background-color: rgba(255, 0, 0, 0.7); /* Изменим цвет фона на полупрозрачный красный */
    transition: background-color 0.3s, transform 0.3s;
  }
  .cell:hover {
    background-color: rgba(0, 128, 255, 0.7);
    transform: scale(1.1);
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
<div class="container" id="board">
</div>
<div class="footer">
  <a href="index.php">Хаб</a>
</div>
<script>
  let currentPlayer = 'X';
  const board = document.getElementById('board');

  const cells = Array.from({ length: 9 }, (_, index) => index);

  cells.forEach(index => {
    const cell = document.createElement('div');
    cell.classList.add('cell');
    cell.setAttribute('data-index', index);
    cell.addEventListener('click', () => {
      if (!cell.textContent) {
        cell.textContent = currentPlayer;
        if (checkWin()) {
          alert(currentPlayer + ' переміг!');
          resetBoard();
          return;
        }
        if (checkDraw()) {
          alert('Нічия!');
          resetBoard();
          return;
        }
        currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
      }
    });
    board.appendChild(cell);
  });

  function checkWin() {
    const winningCombinations = [
      [0, 1, 2], [3, 4, 5], [6, 7, 8],
      [0, 3, 6], [1, 4, 7], [2, 5, 8],
      [0, 4, 8], [2, 4, 6]
    ];

    return winningCombinations.some(combination => {
      return combination.every(index => {
        const cell = document.querySelector(`[data-index='${index}']`);
        return cell.textContent === currentPlayer;
      });
    });
  }

  function checkDraw() {
    return cells.every(index => {
      const cell = document.querySelector(`[data-index='${index}']`);
      return cell.textContent !== '';
    });
  }

  function resetBoard() {
    cells.forEach(index => {
      const cell = document.querySelector(`[data-index='${index}']`);
      cell.textContent = '';
    });
    currentPlayer = 'X';
  }
</script>
</body>
</html>
