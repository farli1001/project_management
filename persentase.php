<?php
include 'template.php';
?>
<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html>

<head>
    <style>
        .circle {
            position: relative;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: #ddd;
        }

        .bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #4CAF50;
            clip: rect(0, 100px, 200px, 0);
        }

        .text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>
    <?php
    // Kode PHP untuk menghitung persentase rata-rata dari semua project
    $totalPercentage = 0;
    $totalProjects = 0;
    $query = $conn->query('SELECT * FROM projects WHERE');
    $projects = $query->fetchAll(PDO::FETCH_ASSOC);
    // Pastikan variabel $projects sudah terdefinisi
    if ($projects !== null && is_array($projects)) {
        // Hitung total persentase dari semua proyek
        foreach ($projects as $project) {
            $totalPercentage += $project['percentage'];
            $totalProjects++;
        }

        // Hitung persentase rata-rata
        $averagePercentage = ($totalProjects > 0) ? $totalPercentage / $totalProjects : 0;

        // Bulatkan persentase rata-rata ke bilangan bulat terdekat
        $roundedPercentage = round($averagePercentage);

        // Hitung sudut progres (dalam derajat)
        $progressAngle = $averagePercentage * 3.6;

        // Cetak lingkaran progres
        echo '
        <div class="circle">
            <div class="bar" style="transform: rotate(' . $progressAngle . 'deg);"></div>
            <div class="text">' . $roundedPercentage . '%</div>
        </div>';
    } else {
        // Tindakan yang sesuai jika $projects bernilai null atau bukan array
        echo 'Variabel projects kosong atau bukan array';
    }
    ?>

</body>

</html>
