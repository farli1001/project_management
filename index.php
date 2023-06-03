<?php
include 'template.php';
?>
<?php
require_once 'config.php';

// Mengambil daftar tugas dari database
$query = $conn->prepare('SELECT * FROM tasks WHERE project_id = :project_id');
$query->bindParam(':project_id', $projectId);
$query->execute();
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

// Hitung total tugas
$totalTasks = count($tasks);
// Mengambil daftar proyek dari database

$query = $conn->query('SELECT * FROM projects WHERE archived = 0 ORDER BY sort_order ASC');
$projects = $query->fetchAll(PDO::FETCH_ASSOC);

// Update sort_order untuk setiap proyek
$updateQuery = $conn->prepare('UPDATE projects SET sort_order = :sort_order WHERE id = :id');
$order = 1;
foreach ($projects as $project) {
    $updateQuery->bindValue(':sort_order', $order);
    $updateQuery->bindValue(':id', $project['id']);
    $updateQuery->execute();
    $order++;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Project Management</title>
    <!-- Tambahkan CSS Bootstrap -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.13.0/Sortable.min.js"></script>
    <style>
        .empty-image {
            border: 1px dashed #ccc;
            background-color: white;
            height: 143px;
            width: 100%;
            box-shadow: 4px 0px 4px rgba(0, 0, 0, 0.1);
        }

        .project-image {
            height: 143px;
            width: 100%;
            padding: 5px;
            text-align: center;
            box-shadow: 2px 0px 1px rgba(0, 0, 0, 0.2);
        }

        .green-button {
            background-color: #81C784;
            color: white;
            transition: background-color 0.3s;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .green-button.active,
        .green-button:hover {
            background-color: #A5D6A7;
        }

        .red-button {
            background-color: red;
            color: white;
        }

        .yellow-button {
            background-color: yellow;
            color: black;
        }

        .blue-button {
            background-color: blue;
            color: white;
        }


        .green-button:hover {
            background-color: #A5D6A7;
        }

        body {
            background-color: #f0f7fa;
        }

        .card-text {
            padding: 1rem;
            background-color: white;
            height: 120px;
            /* Tinggi tetap untuk kotak deskripsi */
            border-color: blue;
        }

        .card {
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .card-body {
            background-color: white;
            border: none;
            padding: 1.25rem;
            box-shadow: 2px 0px 3px rgba(0, 0, 0, 0.2);
        }

        .card-footer .progress-bar {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .card-footer {
            border-right: none;
            border-left: none;
            background-color: #f8f9fa;
            padding: 0.75rem;
            box-shadow: 2px 0px 4px rgba(0, 0, 0, 0.2);
        }

        .card-footer small.text-muted {
            color: #6c757d;
        }

        .nav-primary .nav-item .active {

            background-color: black;
            color: white;
            transition: background-color 0.3s;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;

        }
    </style>
</head>

<body>
    <div class="container">

        <h1>Daftar Project</h1>
        <!-- Form untuk menambahkan proyek baru -->
        <button type="button" class="btn green-button mt-3" data-toggle="modal" data-target="#addProjectModal">
            Add Project
        </button>
        <!-- Tampilkan daftar proyek -->
        <div class="card-deck row" id="project-list" draggable="true">

            <?php foreach ($projects as $project) : ?>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">

                    <a href="project.php?id=<?= $project['id']; ?>" style="text-decoration: none;" data-project-id="<?= $project['id']; ?>">

                        <?php if (!empty($project['image'])) : ?>
                            <img src="assets/<?= $project['image']; ?>" alt="<?= $project['name']; ?>" class="project-image mt-5" style="max-width: 100%;">
                        <?php else : ?>
                            <div class="empty-image mt-5"></div>
                        <?php endif; ?>
                        <!-- <img src="assets/img/logo_labict2.png" class="card-img-top" alt="Project Image"> -->
                        <div class="card-body">
                            <h5 class="card-title"><?= $project['name']; ?></h5>
                            <p class="card-text"><?= $project['description']; ?></p></a>
                        </div>
                        <div class="card-footer">
                            <div class="progress mt-2">
                                <?php
                                // Mengambil daftar tugas dari database untuk proyek ini
                                $tasksQuery = $conn->prepare('SELECT * FROM tasks WHERE project_id = :project_id');
                                $tasksQuery->bindValue(':project_id', $project['id']);
                                $tasksQuery->execute();
                                $tasks = $tasksQuery->fetchAll(PDO::FETCH_ASSOC);

                                $totalPercentage = 0;
                                $totalTasks = count($tasks);

                                foreach ($tasks as $task) {
                                    $totalPercentage += $task['percentage'];
                                }

                                $projectPercentage = ($totalTasks > 0) ? $totalPercentage / $totalTasks : 0;
                                $roundedPercentage = ceil($projectPercentage);

                                // Tentukan kelas CSS berdasarkan persentase
                                $progressBarClass = 'progress-bar';

                                if ($roundedPercentage >= 0 && $roundedPercentage < 30) {
                                    $progressBarClass .= ' progress-bar-red';
                                } elseif ($roundedPercentage >= 30 && $roundedPercentage < 60) {
                                    $progressBarClass .= ' progress-bar-orange';
                                } elseif ($roundedPercentage >= 60 && $roundedPercentage < 80) {
                                    $progressBarClass .= ' progress-bar-yellow';
                                } elseif ($roundedPercentage >= 80 && $roundedPercentage < 100) {
                                    $progressBarClass .= ' progress-bar-lightgreen';
                                } elseif ($roundedPercentage == 100) {
                                    $progressBarClass .= ' progress-bar-mediumseagreen';
                                }
                                ?>

                                <div class="<?= $progressBarClass; ?>" role="progressbar" style="width: <?= $roundedPercentage; ?>%;">
                                    <?= $roundedPercentage; ?>%
                                </div>
                            </div>
                            <small class="text-muted">Deadline: <?= $project['end_date'] ? date('d-m-Y', strtotime($project['end_date'])) : 'No deadline'; ?></small>
                        </div>
                </div>
            <?php endforeach; ?>
        </div>


    </div>

    <!-- Modal untuk menambahkan proyek baru -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">Add Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="add_project.php" method="POST">
                        <div class="form-group">
                            <label for="projectName">Project Name</label>
                            <input type="text" class="form-control" id="projectName" name="name" required>
                        </div>
                        <button type="submit" class="btn green-button">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--  JavaScript SortableJS -->

    <script>
        // Buat instance Sortable untuk mengatur drag and drop
        // Inisialisasi SortableJS pada elemen yang berisi daftar kartu
        var sortable = new Sortable(document.getElementById('project-list'), {
            handle: '.card-text', // Tentukan elemen yang akan digunakan sebagai pegangan kartu
            onEnd: function(evt) {
                // Dijalankan setelah pengurutan selesai
                // Dapatkan urutan baru dari kartu
                var projectIds = [];
                var projectElements = document.querySelectorAll('#project-list a[data-project-id]');

                projectElements.forEach(function(element) {
                    var projectId = element.getAttribute('data-project-id');
                    projectIds.push(projectId);
                });

                // Kirim data urutan baru ke server menggunakan AJAX atau metode lainnya
                // Contoh menggunakan AJAX dan jQuery
                $.ajax({
                    url: 'update_project_order.php',
                    method: 'POST',
                    data: {
                        projectIds: projectIds
                    },
                    success: function(response) {
                        // Perbarui tampilan daftar kartu dengan urutan yang diperbarui
                        // Misalnya, jika respons mengembalikan HTML yang diperbarui
                        $('#project-list').html(response);
                        console.log('Project order updated');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating project order:', error);
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Fokus pada input nama tugas saat modal muncul
            $('#addProjectModal').on('shown.bs.modal', function() {
                $('#projectName').focus();
            });
        });
    </script>



    <!-- Tambahkan JavaScript Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <!-- Tambahkan JavaScript Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script>
        var today = new Date();
  var options = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  };
  var date = today.toLocaleDateString('id-ID', options);
  document.getElementById('todayDate').innerHTML = date;
    </script>

    <!-- jQuery UI -->
    <script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>
    <!-- Atlantis JS -->
    <script src="assets/js/atlantis.min.js"></script>
    <!-- Atlantis DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
</body>

</html>