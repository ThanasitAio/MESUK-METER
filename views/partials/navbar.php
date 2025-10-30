<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid">
        <!-- Toggle Sidebar Button -->
        <button class="navbar-toggler custom-toggler me-3 d-none d-lg-flex" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Brand Logo & Name -->
        <a class="navbar-brand fw-bold text-dark d-flex align-items-center" href="/">
            <!-- <div class="brand-logo me-2 d-flex align-items-center justify-content-center rounded" 
                 style="width: 40px; height: 40px; background-color: #D3EE98;margin: 0;">
                <i class="bi bi-grid-3x3-gap-fill text-dark fs-5"></i>
            </div> -->
            <div class=" me-2 d-flex align-items-center justify-content-center rounded" 
                style="width: 40px; height: 40px;margin: 0;">
                <img src="/assets/images/meters_logo.png" alt="Description of the image" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="d-none d-sm-block">
                <span class="fw-bold"><?php echo t('app.name', 'MESUK'); ?></span>
                <small class="d-block text-muted" style="font-size: 0.7rem; line-height: 1;">
                    <?php echo t('app.management_system', 'Management System'); ?>
                </small>
            </div>
        </a>

        <!-- Mobile/Tablet Top Bar -->
        <div class="d-lg-none d-flex align-items-center ms-auto gap-2">
            <!-- Mobile Active Users Stats -->
            <?php 
            require_once __DIR__ . '/../../app/core/Database.php';
            require_once __DIR__ . '/../../app/utils/LoginHistory.php';
            $activeUsersCount = LoginHistory::getActiveUsersCount(3);
            $currentUser = Auth::user();
            $displayName = $currentUser ? $currentUser['full_name'] : 'Guest';
            $role = $currentUser ? $currentUser['role'] : 'agent';

      
            ?>
            <div class="mobile-stats">
                <div class="d-flex align-items-center text-muted px-2 py-1 rounded" 
                     style="background-color: #f8f9fa; border: 1px solid #dee2e6;"
                     title="<?php echo t('navbar.active_users', 'Active Users'); ?> (3 <?php echo t('navbar.days', 'Days'); ?>)">
                    <i class="bi bi-people-fill me-1" style="color: #28a745; font-size: 0.9rem;"></i>
                    <small class="fw-bold"><?php echo $activeUsersCount; ?></small>
                </div>
            </div>

            <!-- Mobile Language Switcher -->
            <div class="mobile-language-switcher">
                <div class="dropdown">
                    <a class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center" 
                       href="#" role="button" data-bs-toggle="dropdown"
                       style="background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 0.25rem 0.5rem;">
                        <i class="bi bi-translate me-1"></i>
                        <span class="text-uppercase"><?php echo currentLang(); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <form method="POST" action="/language/switch" class="dropdown-item p-0">
                                <input type="hidden" name="lang" value="en">
                                <button type="submit" class="dropdown-item d-flex align-items-center py-2 <?php echo currentLang() === 'en' ? 'active' : ''; ?>">
                                    <i class="bi bi-flag me-2"></i>
                                    <span>English</span>
                                </button>
                            </form>
                        </li>
                        <li>
                            <form method="POST" action="/language/switch" class="dropdown-item p-0">
                                <input type="hidden" name="lang" value="th">
                                <button type="submit" class="dropdown-item d-flex align-items-center py-2 <?php echo currentLang() === 'th' ? 'active' : ''; ?>">
                                    <i class="bi bi-flag me-2"></i>
                                    <span>ไทย</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Mobile User Menu -->
            <div class="mobile-user-menu">
                <div class="dropdown">
                    <a class="btn btn-sm d-flex align-items-center p-1" 
                       href="#" role="button" data-bs-toggle="dropdown"
                       style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 32px; height: 32px; background-color: #D3EE98; overflow: hidden;">
                            <i class="bi bi-person-fill text-dark"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 200px;">
                        <!-- User Info Header -->
                        <li class="px-3 py-2 border-bottom">
                            <div class="d-flex align-items-center mb-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px; background-color: #D3EE98;">
                                    <i class="bi bi-person-fill text-dark fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        <?php echo htmlspecialchars($displayName); ?>
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                                        <i class="bi bi-shield-check me-1"></i>
                                        <?php echo htmlspecialchars(ucfirst($role)); ?>
                                    </small>
                                </div>
                            </div>
                        </li>
                        
                        <!-- Menu Items -->
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="/profile">
                                <i class="bi bi-person me-3" style="color: #D3EE98;"></i>
                                <span><?php echo t('navbar.profile', 'Profile'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="/settings">
                                <i class="bi bi-gear me-3" style="color: #D3EE98;"></i>
                                <span><?php echo t('navbar.settings', 'Settings'); ?></span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="/logout">
                                <i class="bi bi-box-arrow-right me-3"></i>
                                <span><?php echo t('navbar.logout', 'Logout'); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Navbar Items (Desktop) -->
        <div class="navbar-collapse collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Quick Stats - Active Users in Last 3 Days (Desktop) -->
                <li class="nav-item mx-2 d-none d-lg-block">
                    <div class="d-flex align-items-center text-muted" title="ผู้ใช้งานใน 3 วันล่าสุด">
                        <i class="bi bi-people-fill me-1" style="color: #28a745;"></i>
                        <small class="fw-bold"><?php echo $activeUsersCount; ?></small>
                        <small class="ms-1"><?php echo t('navbar.active_users', 'Active Users'); ?></small>
                    </div>
                </li>
                <li class="nav-item mx-2 d-none d-lg-block">
                    <div class="d-flex align-items-center text-muted">
                        <i class="bi bi-clock-history me-1"></i>
                        <small>3 <?php echo t('navbar.days', 'Days'); ?></small>
                    </div>
                </li>

                <!-- Language Switcher - Desktop -->
                <li class="nav-item dropdown ms-2 d-none d-lg-block">
                    <a class="nav-link dropdown-toggle d-flex align-items-center p-1 rounded" 
                       href="#" role="button" data-bs-toggle="dropdown"
                       style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                        <i class="bi bi-translate me-1"></i>
                        <span class="text-uppercase"><?php echo currentLang(); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <form method="POST" action="/mesuk/language/switch" class="dropdown-item p-0">
                                <input type="hidden" name="lang" value="en">
                                <button type="submit" class="dropdown-item d-flex align-items-center py-2 <?php echo currentLang() === 'en' ? 'active' : ''; ?>">
                                    <i class="bi bi-flag me-2"></i>
                                    <span>English</span>
                                </button>
                            </form>
                        </li>
                        <li>
                            <form method="POST" action="/mesuk/language/switch" class="dropdown-item p-0">
                                <input type="hidden" name="lang" value="th">
                                <button type="submit" class="dropdown-item d-flex align-items-center py-2 <?php echo currentLang() === 'th' ? 'active' : ''; ?>">
                                    <i class="bi bi-flag me-2"></i>
                                    <span>ไทย</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

                <!-- User Menu (Desktop) -->
                <li class="nav-item dropdown ms-2 d-none d-lg-block">
                    <a class="nav-link dropdown-toggle d-flex align-items-center p-1" 
                       href="#" role="button" data-bs-toggle="dropdown"
                       style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                             style="width: 36px; height: 36px; background-color: #D3EE98; overflow: hidden;">
                            <i class="bi bi-person-fill text-dark"></i>
                        </div>
                        <div class="d-none d-md-block">
                            <span class="fw-medium text-dark"><?php echo htmlspecialchars($displayName); ?></span>
                            <small class="d-block text-muted" style="font-size: 0.7rem; line-height: 1;">
                                <?php echo htmlspecialchars(ucfirst($role)); ?>
                            </small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="/profile">
                                <i class="bi bi-person me-3" style="color: #D3EE98;"></i>
                                <span><?php echo t('navbar.profile', 'Profile'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="/settings">
                                <i class="bi bi-gear me-3" style="color: #D3EE98;"></i>
                                <span><?php echo t('navbar.settings', 'Settings'); ?></span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="/logout">
                                <i class="bi bi-box-arrow-right me-3"></i>
                                <span><?php echo t('navbar.logout', 'Logout'); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav d-lg-none">
    <div class="mobile-nav-container">
        <a href="/" class="mobile-nav-item">
            <i class="fa-solid fa-gauge"></i>
            <span><?php echo t('sidebar.dashboard'); ?></span>
        </a>
        
        <a href="#" class="mobile-nav-item mobile-menu-toggle">
            <i class="fa-solid fa-bars"></i>
            <span><?php echo t('mobile.menu'); ?></span>
        </a>
        
        <a href="/profile" class="mobile-nav-item">
            <i class="fa-solid fa-user"></i>
            <span><?php echo t('mobile.profile'); ?></span>
        </a>
    </div>
</nav>