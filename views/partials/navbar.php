<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
    <div class="container-fluid">
        <!-- Toggle Sidebar Button -->
        <button class="navbar-toggler custom-toggler me-3 d-none d-lg-flex" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Brand Logo & Name -->
        <a class="navbar-brand fw-bold text-dark d-flex align-items-center" href="/mesuk">
            <div class="brand-logo me-2 d-flex align-items-center justify-content-center rounded" 
                 style="width: 40px; height: 40px; background-color: #D3EE98;margin: 0;">
                <i class="bi bi-grid-3x3-gap-fill text-dark fs-5"></i>
            </div>
            <div class="d-none d-sm-block">
                <span class="fw-bold"><?php echo t('app.name', 'MESUK'); ?></span>
                <small class="d-block text-muted" style="font-size: 0.7rem; line-height: 1;">
                    <?php echo t('app.management_system', 'Management System'); ?>
                </small>
            </div>
        </a>

        <!-- Mobile Language Switcher - แทนที่ Mobile Page Title -->
        <div class="mobile-language-switcher d-lg-none ms-2 " style="margin-right: 90px;">
            <div class="dropdown " >
                <a class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center mr-50" 
                   href="#" role="button" data-bs-toggle="dropdown"
                   style="background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 0.25rem 0.5rem;">
                    <i class="bi bi-translate me-1"></i>
                    <span class="text-uppercase"><?php echo currentLang(); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-start shadow border-0" >
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
            </div>
        </div>

        <!-- Navbar Items -->
        <div class="navbar-collapse collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Quick Stats -->
                <li class="nav-item mx-2 d-none d-lg-block">
                    <div class="d-flex align-items-center text-muted">
                        <i class="bi bi-people me-1"></i>
                        <small>150 <?php echo t('navbar.users', 'Users'); ?></small>
                    </div>
                </li>
                <li class="nav-item mx-2 d-none d-lg-block">
                    <div class="d-flex align-items-center text-muted">
                        <i class="bi bi-file-text me-1"></i>
                        <small>45 <?php echo t('navbar.posts', 'Posts'); ?></small>
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

                <!-- User Menu -->
                <li class="nav-item dropdown ms-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center p-1 " 
                       href="#" role="button" data-bs-toggle="dropdown"
                       style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                             style="width: 36px; height: 36px; background-color: #D3EE98; overflow: hidden;">
                            <i class="bi bi-person-fill text-dark"></i>
                        </div>
                        <div class="d-none d-md-block">
                            <span class="fw-medium text-dark">John Doe</span>
                            <small class="d-block text-muted" style="font-size: 0.7rem; line-height: 1;">Administrator</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="/mesuk/profile">
                                <i class="bi bi-person me-3" style="color: #D3EE98;"></i>
                                <span><?php echo t('navbar.profile', 'Profile'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="/mesuk/settings">
                                <i class="bi bi-gear me-3" style="color: #D3EE98;"></i>
                                <span><?php echo t('navbar.settings', 'Settings'); ?></span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="/mesuk/logout">
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
        <a href="/mesuk" class="mobile-nav-item">
            <i class="bi bi-speedometer2"></i>
            <span><?php echo t('sidebar.dashboard'); ?></span>
        </a>
        
        
        <a href="#" class="mobile-nav-item mobile-menu-toggle">
            <i class="bi bi-list"></i>
            <span><?php echo t('mobile.menu'); ?></span>
        </a>
        
        <a href="/mesuk/profile" class="mobile-nav-item">
            <i class="bi bi-person"></i>
            <span><?php echo t('mobile.profile'); ?></span>
        </a>
    </div>
    
</nav>
