<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MESUK - <?php echo isset($title) ? $title : 'Dashboard'; ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    
<link href="/assets/css/app.css" rel="stylesheet">
<link href="/assets/css/theme.css" rel="stylesheet">
<link href="/assets/css/sidebar.css" rel="stylesheet">
<link href="/assets/css/navbar.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include_once __DIR__ . '/../partials/sidebar.php'; ?>
        
        <!-- Main Content Wrapper -->
        <div class="main-content flex-grow-1">
            <!-- Navbar -->
            <?php include_once __DIR__ . '/../partials/navbar.php'; ?>
            
            <!-- Page Content  min-height: 100vh; -->
            <main class="container-fluid py-4" style="background-color: #f4f6f9; ">
                <?php
                $viewFile = __DIR__ . '/../' . $view . '.php';
                if (file_exists($viewFile)) {
                    require_once $viewFile;
                } else {
                    echo "View file not found: " . $view;
                }
                ?>
            </main>
        </div>
    </div>

    <!-- jQuery (ต้องมาก่อน app.js) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<!-- Main JS --> 
 <script src="/assets/js/app.js"></script>
 <script src="/assets/js/sidebar.js"></script>
 <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
