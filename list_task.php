<?php
include 'template.php';
?>
<?php
require_once 'config.php';

// Mengambil daftar tugas dari database (kecuali proyek dengan archive = 0)
$query = $conn->prepare('SELECT * FROM tasks JOIN projects ON tasks.project_id = projects.id WHERE projects.archived = 0');
$query->execute();
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);
// Penanganan pembaruan tugas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_task') {
    $taskId = $_POST['task_id'];
    $percentage = $_POST['percentage'];
    $deadline = $_POST['deadline'];
    $description = $_POST['description'];

    // Perbarui nilai tugas dalam database
    $query = $conn->prepare('UPDATE tasks SET percentage = :percentage, deadline = :deadline, description = :description WHERE id = :task_id');
    $query->bindParam(':percentage', $percentage);

    // Cek jika nilai end date kosong atau tidak diisi
    if (empty($deadline)) {
        $query->bindValue(':deadline', null, PDO::PARAM_NULL);
    } else {
        $query->bindParam(':deadline', $deadline);
    }

    $query->bindParam(':description', $description);
    $query->bindParam(':task_id', $taskId);
    $query->execute();

    // Refresh halaman projek
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Project Management - <?= $project['name']; ?></title>
    <!-- Tambahkan CSS Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="stylesheet" href="style1.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <style>
        .progress-bar {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .badge-pill,
        .badge-primary {
            text-shadow: 2px 0px 1px rgba(0, 0, 0, 0.3);
        }

        .green-button {
            background-color: #81c784;
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
            background-color: #a5d6a7;
        }
    </style>
</head>

<body>
    <div class="container ">
        <h1 class="mb-5">Daftar Seluruh Task</h1>
        <?php if (count($tasks) > 0) : ?>

            <!-- <div class="dropdown">
                <button class=" green-button  mb-3 " type="button" id="sortOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sort by
                </button>
                <div class="dropdown-menu" aria-labelledby="sortOptions">
                <a class="dropdown-item" href="#" onclick="okdah('deadlineAsc'); updatetaskli(tasks);">Deadline Terdekat</a>
                    <a class="dropdown-item" href="#" onclick="okdah('deadlineDesc'); updatetaskli(tasks);">Deadline Terlama</a>
                    <a class="dropdown-item" href="#" onclick="okdah('percentageAsc'); updatetaskli(tasks);">Progress Terkecil </a>
                    <a class="dropdown-item" href="#" onclick="okdah('percentageDesc'); updatetaskli(tasks);">Persentase Terbesar</a>
                </div>
            </div> -->

            <ul class="list-group" id="taskli">
                <?php foreach ($tasks as $task) : ?>
                    <li class="list-group-item task-item mb-2">
                        <div class=" task-name">
                            <span><?= $task['name']; ?></span>
                            <span class="badge badge-primary badge-pill percentage <?php
                                                                                    if ($task['percentage'] >= 0 && $task['percentage'] < 30) {
                                                                                        echo  'progress-bar-red';
                                                                                    } elseif ($task['percentage'] >= 30 && $task['percentage'] < 60) {
                                                                                        echo 'progress-bar-orange';
                                                                                    } elseif ($task['percentage'] >= 60 && $task['percentage'] < 80) {
                                                                                        echo  'progress-bar-yellow';
                                                                                    } elseif ($task['percentage'] >= 80 && $task['percentage'] < 100) {
                                                                                        echo  'progress-bar-lightgreen';
                                                                                    } elseif ($task['percentage'] == 100) {
                                                                                        echo  'progress-bar-mediumseagreen';
                                                                                    }
                                                                                    ?>">
                                <?= $task['percentage']; ?>%
                            </span>

                        </div>
                        <?php
                        $today = date('Y-m-d');
                        $deadline = $task['deadline'];

                        // alert deadline
                        if ($task['percentage'] === 100) {
                            // Jika sudah 100% maka tugas sukses (tanpa keterangan deadline)
                            echo '<div class="alert alert-success">Task Sukses</div>';
                            echo '<style>.task-item[data-deadline="' . $deadline . '"] { background-color: lightgreen !important; }</style>';
                        } elseif ($deadline === $today) {
                            // Jika deadline hari ini dan tugas belum selesai
                            echo '<div class="alert alert-danger">Deadline hari ini</div>';
                            echo '<style>.task-item[data-deadline="' . $deadline . '"] { background-color: lightcoral !important; }</style>';
                        } elseif ($deadline === date('Y-m-d', strtotime('+1 day'))) {
                            // Jika deadline dalam 1 hari lagi dan tugas belum selesai
                            echo '<div class="alert alert-warning">Deadline dalam 1 hari</div>';
                            echo '<style>.task-item[data-deadline="' . $deadline . '"] { background-color: lightsalmon !important; }</style>';
                        } elseif ($deadline < $today && $task['percentage'] < 100) {
                            // Jika sudah lewat dari deadline dan tugas belum selesai
                            echo '<div class="alert alert-danger">Sudah lewat deadline</div>';
                            echo '<style>.task-item[data-deadline="' . $deadline . '"] { background-color: red !important; }</style>';
                        } elseif ($deadline > date('Y-m-d', strtotime('+1 day')) && $task['percentage'] < 100) {
                            // Jika blm akan deadline dan tugas belum selesai
                            echo '<div class="alert alert-primary">deadline:' . $deadline . '</div>';
                            echo '<style>.task-item[data-deadline="' . $deadline . '"] { background-color: blue !important; }</style>';
                        }
                        ?>

                        <!-- <div class="dropdown ">
                            <button class=" green-button  btn-sm " type="button" id="taskOptions<?= $task['id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="taskOptions<?= $task['id']; ?>">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editTaskModal<?= $task['id']; ?>">Edit Task</a>
                                <a class="dropdown-item" onclick="copyTask(<?= $task['id']; ?>)">Copy Task</a>
                                <a class="dropdown-item" onclick="deleteTask(<?= $task['id']; ?>)">Remove Task</a>
                            </div>
                        </div>
                    </li> -->
                    <!-- Modal untuk mengedit tugas -->
                    <!-- <div class="modal fade" id="editTaskModal<?= $task['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel<?= $task['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editTaskModalLabel<?= $task['id']; ?>">Edit Task</h5>
                                    <button type="button" class="close green-button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="project.php?id=<?= $projectId; ?>" method="POST">
                                        <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                                        <input type="hidden" name="action" value="update_task">

                                        <div class="form-group">
                                            <label for="taskPercentage<?= $task['id']; ?>">Percentage</label>
                                            <input type="range" class="form-control-range" id="taskPercentage<?= $task['id']; ?>" name="percentage" min="0" max="100" value="<?= $task['percentage']; ?>" step="1">
                                        </div>
                                        <div class="form-group">
                                            <label for="taskDeadline<?= $task['id']; ?>">Deadline</label>
                                            <input type="date" class="form-control" id="taskDeadline<?= $task['id']; ?>" name="deadline" value="<?= $task['deadline']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="taskDescription<?= $task['id']; ?>">Description</label>
                                            <textarea class="form-control" id="taskDescription<?= $task['id']; ?>" name="description"><?= $task['description']; ?></textarea>
                                        </div>
                                        <button type="submit" class=" green-button">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> -->
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <div class="alert alert-warning">Tugas kosong</div>
        <?php endif; ?>
    </div>
    </div>
    <script>
        function okdah(criteria) {
            var taskli = document.getElementById('taskli');
            var tasks = Array.from(taskli.getElementsByClassName('task-item'));

            tasks.sort(function(a, b) {
                var taskA, taskB;

                switch (criteria) {
                    case 'deadlineAsc':
                        taskA = a.getAttribute('data-deadline');
                        taskB = b.getAttribute('data-deadline');
                        return (taskA > taskB) ? 1 : -1;
                    case 'deadlineDesc':
                        taskA = a.getAttribute('data-deadline');
                        taskB = b.getAttribute('data-deadline');
                        return (taskA < taskB) ? 1 : -1;
                    case 'percentageDesc':
                        taskA = parseInt(a.getElementsByClassName('percentage')[0].textContent);
                        taskB = parseInt(b.getElementsByClassName('percentage')[0].textContent);
                        return (taskA < taskB) ? 1 : -1;
                    case 'percentageAsc':
                        taskA = parseInt(a.getElementsByClassName('percentage')[0].textContent);
                        taskB = parseInt(b.getElementsByClassName('percentage')[0].textContent);
                        return (taskA > taskB) ? 1 : -1;
                    default:
                        return 0;
                }
            });
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!--  JavaScript Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <!-- -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <script src="assets/js/core/jquery.3.2.1.min.js"></script> -->
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    
    <script>
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };
    </script>
    <script>
        var today = new Date();
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
<script>function updatetaskli(sortedTasks) {
    var taskli = document.getElementById('taskli');

    // Hapus semua elemen <li> dalam taskli
    while (taskli.firstChild) {
        taskli.removeChild(taskli.firstChild);
    }

    // Masukkan kembali tugas-tugas dalam urutan yang benar
    sortedTasks.forEach(function(task) {
        taskli.appendChild(task);
    });
}
</script>
    <!-- jQuery UI -->
    <script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Atlantis JS -->
    <script src="assets/js/atlantis.min.js"></script>

    <!-- Atlantis DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    </body>

</html>