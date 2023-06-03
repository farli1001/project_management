<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Project Management</title>
    <link rel="icon" href="" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['assets/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/atlantis.min.css">
    <style>
        /* Logo Header */
        .logo-header,
        .navbar-header {
            color: #A5D6A7;
            background-color: #A5D6A7;
        }

        /* Tombol Dashboard */
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

        .display-4 {
            margin-top: 20px;
        }
    </style>
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css">
</head>

<body>
    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header">
                <a href="dashboard.php" class="logo">
                    <!-- <img src="../assets/img/mts.png" alt="navbar brand" class="navbar-brand" width="40"> -->
                    <b class="text-white">Project Management</b>
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="icon-menu"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg">
                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="dropdown">
                                <i class="fa fa-calendar"></i>
                                <span id="todayDate"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- End Navbar -->
        </div>
        <!-- nav bar -->
        <!-- side bar -->
        <div class="sidebar sidebar-style-2">
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav mb-1 pb-1">
                        <li class="green-button ml-auto nav-item active">

                            <a href="dashboard.php" class=" collapse">
                                <i class="fas fa-home white-icon"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Menu Utama</h4>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#projek">
                            <i class="fas fa-folder"></i>
                                <p>Project</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="projek">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="index.php">
                                            <span class="sub-item">Daftar Project </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="list_task.php">
                                            <span class="sub-item">Daftar Task </span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                        <a data-toggle="collapse" href="#arsip">
                        <i class="fas fa-archive"></i>
                                <p>Arsip</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="arsip">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="archive_project2.php">
                                            <span class="sub-item">Arsip Penyimpanan</span>
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a href="">
                                            <span class="sub-item">Arsip Terkunci</span>
                                        </a>
                                    </li> -->
                                </ul>
                            </div>
                        </li>
                        <!-- <li class="nav-item">
                        <a data-toggle="collapse" href="#statistik">
                        <i class="fas fa-chart-line"></i>
                                <p>Statistik</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="statistik">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="statistik.php">
                                            <span class="sub-item">Produktivitas</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="persentase.php">
                                            <span class="sub-item">Total Persentase</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li> -->
                        <!-- <li class="nav-item active mt-3">
                            <a href="admin/index.php" class="collapsed">
                                <i class="fas fa-arrow-alt-circle-right"></i>
                                <p>Login</p>
                            </a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
        <div class="main-panel">
            <div class="content">
                <div class="page-inner mt-1">