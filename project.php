<?php
include 'template.php';
?>
<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$projectId = $_GET['id'];

// Mengambil data proyek dari database
$query = $conn->prepare('SELECT * FROM projects WHERE id = :id');
$query->bindParam(':id', $projectId);
$query->execute();
$project = $query->fetch(PDO::FETCH_ASSOC);

// Mengambil daftar tugas dari database
$query = $conn->prepare('SELECT * FROM tasks WHERE project_id = :project_id');
$query->bindParam(':project_id', $projectId);
$query->execute();
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

// Penanganan pembaruan tugas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_task') {
    $taskId = $_POST['task_id'];
    $taskName = $_POST['task_name'];
    $percentage = $_POST['percentage'];
    $deadline = $_POST['deadline'];
    $description = $_POST['description'];

    // Perbarui nilai tugas dalam database
    $query = $conn->prepare('UPDATE tasks SET name = :name, percentage = :percentage, deadline = :deadline, description = :description WHERE id = :task_id');
    $query->bindParam(':name', $taskName);
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
        <style>
            /* Ganti warna thumb slider */
            input[type="range"] {
                -webkit-appearance: none;
                width: 100%;
                height: 10px;
                background-color: #ddd;
                outline: none;
                opacity: 0.7;
                transition: opacity 0.2s;
            }

            /* Warna latar belakang saat range slider digeser */
            input[type="range"]::-webkit-slider-thumb {
                -webkit-appearance: none;
                appearance: none;
                width: 15px;
                height: 15px;
                background-color: #4CAF50;
                cursor: pointer;
            }

            /* Warna latar belakang ketika range slider tidak digeser */
            input[type="range"]::-webkit-slider-runnable-track {
                width: 100%;
                height: 10px;
                background-color: #ccc;
                cursor: pointer;
            }

            .project-image {
                width: 100%;
                height: 200px;
                margin-bottom: 20px;
            }

            .progress-bar {
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            }

            .badge-pill,
            .badge-primary {
                text-shadow: 2px 0px 1px rgba(0, 0, 0, 0.3);
            }

            .empty-image {
                border: none;
                background-color: #f0f7fa;
                height: 180px;
                width: 250px;
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
            <div class="dropdown " onclick="showImageOptions()" onmouseleave="hideImageOptions()" style="display: inline-block;">
                <?php if (!empty($project['image'])) : ?>
                    <img src="assets/<?= $project['image']; ?>" alt="<?= $project['name']; ?>" class="project-image">
                <?php else : ?>
                    <div class="empty-image"></div>
                <?php endif; ?>
                <div id="imageOptions" class="dropdown-menu">
                    <?php if (!empty($project['image'])) : ?>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editImageModal">Ubah Gambar</a>
                        <a class="dropdown-item" href="delete_image.php?id=<?= $projectId; ?>&image=<?= $project['image']; ?>">Hapus Gambar</a>

                    <?php elseif (empty($project['image'])) : ?>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editImageModal">Tambah Gambar</a>
                    <?php endif; ?>
                </div>
            </div>
            <h1><?= $project['name']; ?></h1>
            <!-- Modal untuk mengubah gambar -->
            <div class="modal fade" id="editImageModal" tabindex="-1" role="dialog" aria-labelledby="editImageModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editImageModalLabel">Ubah Gambar</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Form untuk mengubah gambar -->
                            <form action="upload_image.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="project_id" value="<?= $project['id']; ?>">
                                <input type="file" name="image" accept="image/*" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn red-button" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn green-button">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Tampilkan progres projek -->
            <div class="progress mt-4">
                <?php
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
                <div class="<?= $progressBarClass; ?>" role="progressbar" style="width: <?= $roundedPercentage; ?>%;" aria-valuemin="0" aria-valuemax="100">
                    <?= $roundedPercentage; ?>%
                </div>
            </div>
            <!-- Tampilkan tanggal awal dan akhir progres -->
            <p>Start Date: <?= $project['start_date'] ? date('d-m-Y', strtotime($project['start_date'])) : 'No deadline'; ?></p>
            <p>Deadline: <?= ($project['end_date']) ? date('d-m-Y', strtotime($project['end_date'])) : 'No deadline'; ?></p>
            <p>Description: <?= $project['description']; ?></p>

            <!-- Dropdown untuk mengedit, menghapus, mengcopy, dan mengarsipkan projek -->
            <div class="dropdown mt-5 ml-5" style="position: absolute; top: 60px; right: 70px;">
                <button class="green-button  btn-sm " type="button" id="projectOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="projectOptions">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editProjectModal">Edit Project</a>
                    <a class="dropdown-item" href="copy_project.php?id=<?= $projectId; ?>">Copy Project</a>
                    <a class="dropdown-item" href="delete_project.php?id=<?= $projectId; ?>">Delete Project</a>
                    <!-- <a class="dropdown-item" href="lock_archive.php?id=<?//= $projectId; ?>">Arsip Project secara terkunci</a> -->
                    <a class="dropdown-item" href="archive_project.php?id=<?= $projectId; ?>">Arsip Project</a>
                </div>
            </div>
            <!-- Modal untuk mengedit proyek -->
            <div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="editProjectModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="edit_project.php?id=<?= $projectId; ?>" method="POST" action="" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="projectName">Project Name</label>
                                    <input type="text" class="form-control" id="projectName" name="name" value="<?= $project['name']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="projectDescription">Project Description</label>
                                    <textarea class="form-control" id="projectDescription" name="description"><?= $project['description']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="projectEndDate">End Date</label>
                                    <input type="date" class="form-control" id="projectEndDate" name="end_date" value="<?= $project['end_date']; ?>">
                                </div>
                                <button type="submit" class=" green-button">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tampilkan daftar tugas -->
            <!-- ;otrem -->
            <?php if (count($tasks) > 0) : ?>

                <div class="dropdown">
                    <button class=" green-button  mb-3 " type="button" id="sortOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sort by
                    </button>
                    <div class="dropdown-menu" aria-labelledby="sortOptions">
                        <a class="dropdown-item" href="#" onclick="sortTasks('deadlineAsc')">Deadline Terdekat</a>
                        <a class="dropdown-item" href="#" onclick="sortTasks('deadlineDesc')">Deadline Terlama</a>
                        <a class="dropdown-item" href="#" onclick="sortTasks('percentageAsc')">Progress Terkecil </a>
                        <a class="dropdown-item" href="#" onclick="sortTasks('percentageDesc')">Progress Terbesar</a>
                    </div>
                </div>
                <ul class="list-group" id="taskList">
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
                            <div class="dropdown ">
                                <button class=" green-button  btn-sm " type="button" id="taskOptions<?= $task['id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="taskOptions<?= $task['id']; ?>">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editTaskModal<?= $task['id']; ?>">Edit Task</a>
                                    <a class="dropdown-item" onclick="copyTask(<?= $task['id']; ?>)">Copy Task</a>
                                    <a class="dropdown-item" onclick="deleteTask(<?= $task['id']; ?>)">Remove Task</a>
                                </div>
                            </div>
                        </li>
                        <!-- Modal untuk mengedit tugas -->
                        <div class="modal fade" id="editTaskModal<?= $task['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel<?= $task['id']; ?>" aria-hidden="true">
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
                                                <label for="taskName<?= $task['id']; ?>">Name</label>
                                                <input type="text" class="form-control" id="taskName<?= $task['id']; ?>" name="task_name" value="<?= $task['name']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="taskDeadline<?= $task['id']; ?>">Deadline</label>
                                                <input type="date" class="form-control" id="taskDeadline<?= $task['id']; ?>" name="deadline" value="<?= $task['deadline']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="taskPercentage<?= $task['id']; ?>">Percentage</label>
                                                <input type="range" class="form-control-range" id="taskPercentage<?= $task['id']; ?>" name="percentage" min="0" max="100" value="<?= $task['percentage']; ?>" step="1" oninput="updatePercentageValue(this)">
                                                <span id="percentageValue<?= $task['id']; ?>"><?= $task['percentage']; ?>%</span>
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
                        </div>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <div class="alert alert-warning">Segera buat tugas!! Projek tidak akan jalan tanpa membuat task-task kecil.</div>
            <?php endif; ?>
            <!-- Form untuk menambahkan tugas baru -->
            <button type="button" class="green-button mt-3 mb-5 " data-toggle="modal" data-target="#addTaskModal">
                Add Task
            </button>
        </div>

        <!-- Modal untuk menambahkan tugas baru -->
        <div class="modal fade " id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="add_task.php?project_id=<?= $projectId; ?>" method="POST">
                            <div class="form-group">
                                <label for="taskName">Task Name</label>
                                <input type="text" class="form-control" id="taskName" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="taskPercentage">Percentage</label>
                                <input type="range" class="form-control-range" id="taskPercentage" name="percentage" min="0" max="100" value="0" step="1">
                            </div>
                            <div class="form-group">
                                <label for="taskDeadline">Deadline</label>
                                <input type="date" class="form-control" id="taskDeadline" name="deadline">
                            </div>
                            <div class="form-group">
                                <label for="taskDescription">Description</label>
                                <textarea class="form-control" id="taskDescription" name="description"></textarea>
                            </div>
                            <button type="submit" class="green-button">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

        </div>
        <footer class="page-footer font-small blue">
            <div class="container">
                <div class="footer-copyright text-center py-3">
                    © 2023 by Mohamad Rafli Maulana
                </div>
            </div>
        </footer>
        </div>
        </div>
        <!--  JavaScript Bootstrap -->
        <!--  JavaScript jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <!--  JavaScript Popper.js -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <!-- -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="assets/js/core/jquery.3.2.1.min.js"></script>
        <script src="assets/js/core/popper.min.js"></script>
        <script src="assets/js/core/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#basic-datatables').DataTable();
            });
        </script>
        <script>
            history.pushState(null, null, location.href);
            window.onpopstate = function() {
                history.go(1);
            };
        </script>
        <script>
            function updatePercentageValue(input) {
                var percentageValueElement = document.getElementById('percentageValue<?= $task['id']; ?>');
                percentageValueElement.textContent = input.value + '%';
            }
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
        <script src="assets/js/setting-demo.js"></script>
        <script>
            $(document).ready(function() {

                // Fokus pada input nama tugas saat modal muncul
                $('#addTaskModal').on('shown.bs.modal', function() {
                    $('#taskName').focus();
                });
                $('#editProjectModal').on('shown.bs.modal', function() {
                    $('#projectName').focus();
                });

                // Fokus pada input deskripsi tugas saat modal muncul
                $('[id^=editTaskModal]').on('shown.bs.modal', function() {
                    var taskId = $(this).attr('id').replace('editTaskModal', '');
                    $('#taskPercentage' + taskId).focus();
                });

                // Mengatur hover pada tiap task untuk menampilkan modal
                $('.task-item').hover(
                    function() {
                        $(this).css('cursor', 'pointer');
                    },
                    function() {
                        $(this).css('cursor', 'default');
                    }
                );

                // Menampilkan modal edit saat task di-klik
                $('.task-item').click(function() {
                    var targetModal = $(this).data('target');
                    $(targetModal).modal('show');
                });
            });

            function copyTask(taskId) {
                // Kirim permintaan AJAX untuk menyalin tugas
                $.ajax({
                    url: 'copy_task.php',
                    type: 'POST',
                    data: {
                        taskId: taskId
                    },
                    success: function(response) {
                        // Refresh halaman projek
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

            function deleteTask(taskId) {
                // Kirim permintaan AJAX untuk menghapus tugas
                $.ajax({
                    url: 'delete_task.php',
                    type: 'POST',
                    data: {
                        taskId: taskId
                    },
                    success: function(response) {
                        // Refresh halaman projek
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
            // Fungsi untuk mengurutkan tugas berdasarkan kriteria tertentu
            function sortTasks(criteria) {
                var taskList = document.getElementById('taskList');
                var tasks = Array.from(taskList.getElementsByClassName('task-item'));
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
                // Menghapus tugas dari daftar
                while (taskList.firstChild) {
                    taskList.removeChild(taskList.firstChild);
                }

                // Menambahkan tugas yang sudah diurutkan ke daftar
                tasks.forEach(function(task) {
                    taskList.appendChild(task);
                });
            }

            function showImageOptions() {
                document.getElementById('imageOptions').classList.toggle('show');
            }

            function hideImageOptions() {
                document.getElementById('imageOptions').classList.remove('show');
            }

            function copyProject(projectId) {
                // Lakukan permintaan AJAX untuk mengkopi proyek
                $.ajax({
                    url: 'copy_project.php',
                    method: 'POST',
                    data: {
                        project_id: projectId
                    },
                    success: function(response) {
                        // Manipulasi tanggapan jika diperlukan
                        alert('Project copied successfully!');
                    },
                    error: function(xhr, status, error) {
                        // Tangani kesalahan jika terjadi
                        alert('An error occurred while copying the project.');
                        console.log(xhr.responseText);
                    }
                });
            }
        </script>

    </body>

    </html>