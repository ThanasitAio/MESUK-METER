<nav id="sidebar" class="sidebar">
    <div class="sidebar-content">
        <div class="sidebar-nav-container">
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/">
                        <div class="nav-icon"><i class="bi bi-speedometer2"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.dashboard'); ?></span>
                    </a>
                </li>

                <!-- รับชำระหนี้ -->
                <li class="nav-item">
                    <a class="nav-link" href="/payments">
                        <div class="nav-icon"><i class="fa-brands fa-paypal"></i></i></div>
                        <span class="nav-text"><?php echo t('sidebar.payment'); ?></span>
                    </a>
                </li>

                <!-- ใบแจ้งหนี้ -->
                <li class="nav-item">
                    <a class="nav-link" href="/invoices">
                        <div class="nav-icon"><i class="fa-solid fa-file-invoice"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.invoice'); ?></span>
                    </a>
                </li>

                <!-- มิเตอร์ -->
                <li class="nav-item">
                    <a class="nav-link" href="/meters">
                        <div class="nav-icon"><i class="fa-solid fa-jug-detergent"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.meter_data'); ?></span>
                    </a>
                </li>
                
                <!-- สินค้า -->
                <li class="nav-item">
                    <a class="nav-link" href="/products">
                        <div class="nav-icon"><i class="fa-solid fa-warehouse"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.product_data'); ?></span>
                    </a>
                </li>

                <!-- ตัวอย่างเมนูหลักปกติ -->
                <li class="nav-item">
                    <a class="nav-link" href="/users">
                        <div class="nav-icon"><i class="bi bi-people"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.user_management'); ?></span>
                    </a>
                </li>

                <!-- ตัวอย่างเมนูหลักปกติ -->
                <li class="nav-item">
                    <a class="nav-link" href="/import-users">
                        <div class="nav-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.import_user_data'); ?></span>
                    </a>
                </li>

                

                <!-- อีกตัวอย่างของ submenu -->
                <!-- <li class="nav-item has-submenu">
                    <a href="#" class="nav-link submenu-toggle">
                        <div class="nav-icon"><i class="bi bi-graph-up"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.reports'); ?></span>
                        <i class="bi bi-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="/reports/sales"><?php echo t('sidebar.sales_report'); ?></a></li>
                        <li><a href="/reports/users"><?php echo t('sidebar.user_report'); ?></a></li>
                        <li><a href="/reports/system"><?php echo t('sidebar.system_logs'); ?></a></li>
                    </ul>
                </li> -->

                <!-- ✅ ตัวอย่างเมนูหลักที่มี Submenu -->
                <!-- <li class="nav-item has-submenu">
                    <a href="#" class="nav-link submenu-toggle">
                        <div class="nav-icon"><i class="bi bi-gear"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.settings'); ?></span>
                        <i class="bi bi-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="/settings/general"><?php echo t('sidebar.general_settings'); ?></a></li>
                        <li><a href="/settings/appearance"><?php echo t('sidebar.appearance'); ?></a></li>
                        <li><a href="/settings/notifications"><?php echo t('sidebar.notifications'); ?></a></li>
                    </ul>
                </li> -->

                
            </ul>
        </div>

        <div class="sidebar-footer">
            <div class="small">
                <div class="fw-medium"><?php echo t('app.name'); ?> <?php echo t('app.version'); ?></div>
                <div>&copy; <?php echo date('Y'); ?> <?php echo t('app.copyright'); ?></div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav d-lg-none">
    <div class="mobile-nav-container">
        <a href="/" class="mobile-nav-item">
            <i class="bi bi-speedometer2"></i>
            <span><?php echo t('sidebar.dashboard'); ?></span>
        </a>
        <a href="#" class="mobile-nav-item mobile-menu-toggle">
            <i class="bi bi-list"></i>
            <span><?php echo t('mobile.menu'); ?></span>
        </a>
        <a href="#" class="mobile-nav-item mobile-notifications-toggle">
            <i class="bi bi-bell"></i>
            <span><?php echo t('mobile.alerts'); ?></span>
            <span class="mobile-badge">3</span>
        </a>
        <a href="/profile" class="mobile-nav-item">
            <i class="bi bi-person"></i>
            <span><?php echo t('mobile.profile'); ?></span>
        </a>
    </div>
</nav>

<!-- Mobile Notifications Panel -->
<div class="mobile-notifications-panel">
    <div class="mobile-panel-header">
        <h5><?php echo t('mobile.notifications'); ?></h5>
        <button class="mobile-panel-close">&times;</button>
    </div>
    <div class="mobile-panel-content">
        <div class="mobile-notification-item">
            <div class="notification-icon">
                <i class="bi bi-person-plus"></i>
            </div>
            <div class="notification-content">
                <div class="notification-text"><?php echo t('notifications.new_user'); ?></div>
                <small class="notification-time">2 minutes ago</small>
            </div>
        </div>
        <div class="mobile-notification-item">
            <div class="notification-icon">
                <i class="bi bi-file-text"></i>
            </div>
            <div class="notification-content">
                <div class="notification-text"><?php echo t('notifications.new_report'); ?></div>
                <small class="notification-time">5 minutes ago</small>
            </div>
        </div>
        <div class="mobile-notification-item">
            <div class="notification-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="notification-content">
                <div class="notification-text"><?php echo t('notifications.system_updated'); ?></div>
                <small class="notification-time">1 hour ago</small>
            </div>
        </div>
    </div>
</div>


<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay"></div>