<!DOCTYPE html>
<html>
<head>
  <title>ok</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-image: url('assets/img/1.jpeg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .loading-bar {
      width: 70%;
      height: 10px;
      background-color: #ccc;
      border-radius: 5px;
      position: absolute;
      bottom: 20px;
    }

    .progress-bar {
      width: 0%;
      height: 100%;
      background-color: #4CAF50;
      border-radius: 5px;
      transition: width 0.1s ease-in-out;
    }
  </style>
</head>
<body>
  <div class="loading-bar">
    <div class="progress-bar"></div>
  </div>

  <script>
    function redirect() {
      window.location.href = "index.php";
    }

    function simulateProgress() {
      var progressBar = document.querySelector('.progress-bar');
      var width = 0;
      var interval = setInterval(increaseWidth, 50);

      function increaseWidth() {
        width += 3;
        progressBar.style.width = width + '%';

        if (width >= 50) {
          clearInterval(interval);
          progressBar.style.backgroundColor = "#FFC107"; // Ubah warna progress bar menjadi #FFC107 (kuning)
          document.body.style.backgroundImage = "url('assets/img/2.jpeg')"; // Ganti gambar background
          interval = setInterval(increaseWidth, 50); // Mulai kembali peningkatan lebar progress bar
        }

        if (width >= 100) {
          clearInterval(interval);
          redirect();
        }
      }
    }

    simulateProgress();
  </script>
</body>
</html>
