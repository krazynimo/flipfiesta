<?php
// index.php

$goodGifts = [
    'Surprise Gift',
    'Surprise Gift',
    'Surprise Gift',
    'Surprise Gift',
    'Surprise Gift',
    'Surprise Gift',
    'Beer Can'
];

$funnyGifts = [
    'Bath Soap',
    'Chocolate',
    'Maggi',
    'Krack Jack Biscuit',
    'Waffers',
    'Pen',
    'Funky Hat',
    'Crazy Tie',
    'Gag Gift Certificate',
    'Fake Lottery Ticket',
    'Comical Poster',
    'Bizarre Gadget',
    'Wacky Phone Case',
    'Oddball Pen',
    'Ridiculous Mug'
];

$selectedGood = $goodGifts[array_rand($goodGifts)];

$randomFunnyKeys = array_rand($funnyGifts, 2);
$selectedFunny1 = $funnyGifts[$randomFunnyKeys[0]];
$selectedFunny2 = $funnyGifts[$randomFunnyKeys[1]];


$gifts = [
    ['name' => $selectedGood, 'category' => 'good'],
    ['name' => $selectedFunny1, 'category' => 'funny'],
    ['name' => $selectedFunny2, 'category' => 'funny']
];
shuffle($gifts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ultimate Flip Fiesta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
  <script src="assets/js/particles.js"></script> 
  <style>
    html, body {
      margin: 0;
      padding: 0;
      width: 100vw;
      height: 100vh;
      overflow: hidden;
      font-family: 'Arial', sans-serif;
    }
    body {
      background: url('assets/images/background.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-evenly;
      position: relative;
    }
    #particles-js {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 0;
    }
    h1 {
      font-size: 4vw;
      color: #fff;
      text-shadow: 0 0 10px rgba(0,0,0,0.7);
      animation: shimmer 2s linear infinite;
      margin: 0;
      z-index: 1;
    }
    @keyframes shimmer {
      0% { filter: brightness(1); }
      50% { filter: brightness(1.5); }
      100% { filter: brightness(1); }
    }
    .tile-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 5vw;
      perspective: 1500px;
      width: 90%;
      z-index: 1;
    }
    .tile {
      width: 250px;
      height: 250px;
      cursor: pointer;
      user-select: none;
      position: relative;
      transform-style: preserve-3d;
      transition: transform 0.5s ease;
    }
    .tile:hover {
      transform: scale(1.1);
    }
    .tile-inner {
      position: relative;
      width: 100%;
      height: 100%;
      transition: transform 1s cubic-bezier(0.68,-0.55,0.27,1.55);
      transform-style: preserve-3d;
    }
    .tile.flipped .tile-inner {
      transform: rotateY(180deg);
    }
    .tile-face {
      position: absolute;
      width: 100%;
      height: 100%;
      backface-visibility: hidden;
      border: 4px solid #222;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2vw;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    .tile-front {
      background: rgba(255,255,255,0.85);
      color: #333;
      transition: background 0.3s;
    }
    .tile-front:hover {
      background: rgba(255,255,255,1);
    }
    .tile-back {
      background: #fffcf2;
      transform: rotateY(180deg);
      color: #222;
      font-weight: bold;
      position: relative;
    }
    .play-again {
      padding: 10px 20px;
      font-size: 1.5vw;
      color: #fff;
      background: #ff1744;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      z-index: 1;
      transition: background 0.3s;
    }
    .play-again:hover {
      background: #d500f9;
    }
    .custom-cursor {
      position: fixed;
      width: 20px;
      height: 20px;
      background-color: #fff;
      border-radius: 50%;
      pointer-events: none;
      transform: translate(-50%, -50%);
      mix-blend-mode: difference;
      z-index: 1000;
    }
  </style>
</head>
<body>
  <div id="particles-js"></div>
  <h1>Ultimate Flip Fiesta !</h1>
  <div class="tile-container">
    <?php foreach ($gifts as $gift): ?>
      <div class="tile" data-gift="<?php echo htmlspecialchars($gift['name']); ?>" data-category="<?php echo htmlspecialchars($gift['category']); ?>">
        <div class="tile-inner">
          <div class="tile-face tile-front">
            <strong>Tap to Reveal!</strong>
          </div>
          <div class="tile-face tile-back">
            <span><?php echo htmlspecialchars($gift['name']); ?></span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <button class="play-again" onclick="location.reload();">Play Again</button>
  <audio id="goodSound" src="assets/audiofiles/goodgift.mp3" preload="auto"></audio> <!-- Local audio file -->
  <audio id="funnySound" src="assets/audiofiles/funnygift.mp3" preload="auto"></audio> <!-- Local audio file -->
  <div class="custom-cursor"></div>
  <script>
    const tiles = document.querySelectorAll('.tile');
    const goodSound = document.getElementById('goodSound');
    const funnySound = document.getElementById('funnySound');
    const cursor = document.querySelector('.custom-cursor');

    function triggerConfetti() {
      confetti({ particleCount: 200, spread: 120, origin: { y: 0.6 }, colors: ['#ffea00', '#ff1744', '#00e676', '#2979ff', '#d500f9'] });
    }

    function flipTile(tile) {
      if (tile.classList.contains('flipped')) return;
      tile.classList.add('flipped');
      const category = tile.getAttribute('data-category');
      if (category === 'good') {
        goodSound.currentTime = 0;
        goodSound.play().catch(err => console.log("Sound play error:", err));
      } else {
        funnySound.currentTime = 0;
        funnySound.play().catch(err => console.log("Sound play error:", err));
      }
      triggerConfetti();
    }

    tiles.forEach(tile => {
      tile.addEventListener('click', function() { flipTile(this); });
      tile.addEventListener('touchend', function(e) { e.preventDefault(); flipTile(this); });
    });

    document.addEventListener('mousemove', (e) => {
      cursor.style.left = `${e.pageX}px`;
      cursor.style.top = `${e.pageY}px`;
    });

    particlesJS.load('particles-js', 'assets/json/particles.json', function() { // Local JSON file
      console.log('callback - particles.js config loaded');
    });
  </script>
</body>
</html>