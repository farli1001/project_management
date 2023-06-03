<?php
include 'template.php';
?>
<?php
require_once 'config.php';

// Mengambil daftar projek yang diarsipkan dari database
$query = $conn->prepare('SELECT * FROM projects WHERE archived = 1');

$query->execute();
$archivedProjects = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Archived Projects</title>
    <!-- Tambahkan CSS Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Archived Projects</h1>

        <?php if (count($archivedProjects) > 0) : ?>
            <ul class="list-group" id="archiveList">
                <?php foreach ($archivedProjects as $project) : ?>
                    <li class="list-group-item task-item mb-2">
                        <div class=" task-name">
                            <span>v<?= $project['name']; ?></span>
                        </div>
                        <div class="dropdown">
                            <button class="green-button btn-sm " type="button" id="projectOptions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="projectOptions">
                                <a class="dropdown-item" href="unarchive.php?id=<?= $project['id']; ?>">Unarchived</a>
                                <a class="dropdown-item" href="delete_project.php?id=<?= $project['id']; ?>">Delete Project</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>No archived projects found.</p>
        <?php endif; ?>
    </div>
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
<?php
include 'footer.php';
?>
