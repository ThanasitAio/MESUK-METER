<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Dashboard'; ?></title>
    <!-- logo -->
    <link rel="icon" type="image/png" href="/assets/images/meters_logo.png"> 
    <link rel="shortcut icon" type="image/png" href="/assets/images/meters_logo.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/theme-generated.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <link href="/assets/css/sidebar.css" rel="stylesheet">
    <link href="/assets/css/navbar.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
         <?php 
            require_once __DIR__ . '/../../app/core/Database.php';
            require_once __DIR__ . '/../../app/utils/LoginHistory.php';
            $activeUsersCount = LoginHistory::getActiveUsersCount(3);
            $currentUser = Auth::user();
            $displayName = $currentUser ? $currentUser['full_name'] : 'Guest';
            $role = $currentUser ? $currentUser['role'] : 'agent';

         
            ?>
        <!-- Sidebar -->
        <?php include_once __DIR__ . '/../partials/sidebar.php'; ?>
        
        <!-- Main Content Wrapper -->
        <div class="main-content flex-grow-1">
            <!-- Navbar -->
            <?php include_once __DIR__ . '/../partials/navbar.php'; ?>
            
            <!-- Page Content -->
            <main class="container-fluid py-4" style="background-color: #f4f6f9;">
                <?php
                $viewFile = __DIR__ . '/../' . $view . '.php';
                if (file_exists($viewFile)) {
                    require $viewFile;
                } else {
                    echo "View file not found: " . $view;
                }
                ?>
            </main>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>window.jQuery || document.write('<script src="/assets/js/jquery.min.js"><\/script>');</script>

    <!-- Bootstrap JS (โหลดครั้งเดียวเท่านั้น!) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <!-- Initialize Tom Select -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.tom-select').forEach(function(el) {
                new TomSelect(el, {
                    plugins: ['remove_button'],
                    maxOptions: null,
                    allowEmptyOption: true,
                    placeholder: el.getAttribute('placeholder') || 'เลือก...'
                });
            });
        });
    </script>

    <!-- Main JS -->
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/sidebar.js"></script>

</body>
</html>