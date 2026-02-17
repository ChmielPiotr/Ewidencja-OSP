<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Ewidencji OSP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Prosty styl dla paska bocznego */
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
            color: white;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #dc3545; /* Strażacki czerwony */
            color: white;
        }
    </style>
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="sidebar p-3" style="width: 250px;">
        <h4 class="text-center mb-4 text-white"><i class="bi bi-fire text-danger"></i> OSP Panel</h4>
        <hr class="text-secondary">
        
        <a href="index.php?action=dashboard"><i class="bi bi-house-door me-2"></i> Mój Profil</a>
        <a href="index.php?action=board"><i class="bi bi-diagram-3 me-2"></i> Zarząd OSP</a>
        
        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin')): ?>
            <p class="text-white-50 small mt-4 mb-2 text-uppercase">Administracja</p>
            <a href="index.php?action=index"><i class="bi bi-people me-2"></i> Lista Druhów</a>
            <a href="index.php?action=vehicles"><i class="bi bi-truck me-2"></i> Wozy i Sprzęt</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin'): ?>
            <p class="text-white-50 small mt-4 mb-2 text-uppercase">Logi Systemowe</p>
            <a href="index.php?action=logs" class="text-warning"><i class="bi bi-shield-check me-2"></i> Dziennik Zdarzeń</a>
        <?php endif; ?>
    </div>

    <div class="flex-grow-1">
        
        <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm mb-4 px-4 py-3">
            <div class="container-fluid justify-content-end">
                <span class="navbar-text me-3 fw-bold text-dark">
                    Zalogowany jako: <?= htmlspecialchars($_SESSION['first_name'] ?? 'Użytkownik') ?> 
                    (<?php 
                        if (isset($_SESSION['role'])) {
                            if ($_SESSION['role'] === 'superadmin') echo 'Super Admin';
                            elseif ($_SESSION['role'] === 'admin') echo 'Administrator';
                            else echo 'Strażak';
                        }
                    ?>)
                </span>
                <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Wyloguj</a>
            </div>
        </nav>

        <div class="container-fluid px-4">