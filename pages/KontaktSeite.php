<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Immobilien</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../styles/stylesKontSeit.css">

</head>
<body style="background: transparent;">

    <header>
        <?php 
        
        //include("../includes/header1.php");  
        ?>
      </header>

    <main class="py-3">
        
            <header class="mb-2">
              
              <h1>Wie können wir Ihnen helfen?</h1>

            </header>

            <!-- Kontakt Form -->
            <div class="kontaktform-container">
        <form method="POST" action="../pages/Captcha.php" id="KontaktForm">
        <input type="text" name="Name" placeholder="Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="text" name="telefon" placeholder="Ihre Telefonnummer" />
        <textarea name="nachricht" class="text_field" rows="4" cols="50" placeholder="Ihre Nachricht" required></textarea>

        <!-- Captcha -->
        <?php
session_start();

// Генерация случайных чисел для CAPTCHA
$num1 = rand(1, 10);
$num2 = rand(1, 10);
$_SESSION['captcha_result'] = $num1 + $num2;

// Функция для создания изображения числа
function generateNumberImage($number) {
    $width = 130; // Ширина изображения
    $height = 130; // Высота изображения

    // Создаем изображение
    $image = imagecreatetruecolor($width, $height);

    // Генерация случайного фонового цвета
    $backgroundColors = [
        [255, 182, 193], // Светло-розовый
        [176, 224, 230], // Голубой
        [152, 251, 152], // Светло-зеленый
    ];
    $backgroundColorIndex = array_rand($backgroundColors);
    $backgroundColor = imagecolorallocate(
        $image,
        $backgroundColors[$backgroundColorIndex][0],
        $backgroundColors[$backgroundColorIndex][1],
        $backgroundColors[$backgroundColorIndex][2]
    );
    imagefill($image, 0, 0, $backgroundColor);

    // Добавление пятен на фон
    for ($i = 0; $i < 100; $i++) {
        $spotColor = imagecolorallocate(
            $image,
            rand(0, 255),
            rand(0, 255),
            rand(0, 255)
        );
        imagefilledellipse(
            $image,
            rand(0, $width),
            rand(0, $height),
            rand(5, 15),
            rand(5, 15),
            $spotColor
        );
    }

    // Генерация случайного цвета для текста
    $textColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));

    // Поворот текста
    $fontSize = 60; // Размер шрифта
    $angle = rand(85, -85); // Угол поворота
    $fontFile = __DIR__ . '/arial.ttf'; // Путь к TTF-шрифту
    $bbox = imagettfbbox($fontSize, $angle, $fontFile, $number);
    

    $x = intval(($width - ($bbox[2] - $bbox[0])) / 2);
    $y = intval(($height - ($bbox[5] - $bbox[3])) / 2 );

    // Добавление текста на изображение
    imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $fontFile, $number);

    // Сохранение изображения во временный файл
    $tempFile = tempnam(sys_get_temp_dir(), 'captcha') . '.png';
    imagepng($image, $tempFile);
    imagedestroy($image);

    return $tempFile;
}

// Создаем изображения для $num1 и $num2
$imageNum1 = generateNumberImage($num1);
$imageNum2 = generateNumberImage($num2);
?>
        
       
<div class="captcha-container">
     <label for="captcha">Wie viel wird es sein:</label>
    <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents($imageNum1)); ?>" alt="<?php echo $num1; ?>">
    <span>+</span>
    <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents($imageNum2)); ?>" alt="<?php echo $num2; ?>">
    <span>?</span>
</div>
<br>

       
       
       
      
        <input type="text" id="captcha" name="captcha" required />

        <button type="submit">Abschicken</button>
    </form>
</div>

                


            
        
    </main>
  
</body>
</html>