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
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/theme-generated.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
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
                    // ใช้ require แทน require_once เพื่อให้ตัวแปรจาก extract() ทำงานได้
                    require $viewFile;
                } else {
                    echo "View file not found: " . $view;
                }
                ?>
            </main>
        </div>
    </div>

        <!-- jQuery (เก็บไว้สำหรับ legacy code ที่ใช้ เช่น SweetAlert, app.js) -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>window.jQuery || document.write('<script src="/assets/js/jquery.min.js"><\/script>');</script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Tom Select JS (ไม่ต้องใช้ jQuery) -->
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

        <!-- Initialize Tom Select -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Tom Select for all select elements with class 'tom-select'
                document.querySelectorAll('.tom-select').forEach(function(el) {
                    new TomSelect(el, {
                        plugins: ['remove_button'],
                        maxOptions: null,
                        allowEmptyOption: true,
                        placeholder: el.getAttribute('placeholder') || 'เลือก...',
                        onInitialize: function() {
                            console.log('TomSelect initialized for:', el.id);
                        }
                    });
                });
            });
        </script>

        <!-- Main JS -->
        <script src="/assets/js/app.js"></script>
        <script src="/assets/js/sidebar.js"></script>

</body>
</html>

