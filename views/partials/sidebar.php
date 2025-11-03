<nav id="sidebar" class="sidebar">
    <div class="sidebar-content">
        <div class="sidebar-nav-container">
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/'); ?>">
                        <div class="nav-icon"><i class="fa-solid fa-gauge"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.dashboard'); ?></span>
                    </a>
                </li>

                <!-- มิเตอร์ -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/meters'); ?>">
                        <div class="nav-icon"><i class="fa-solid fa-jug-detergent"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.meter_data'); ?></span>
                    </a>
                </li>

                <!-- ใบแจ้งหนี้ -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/invoices'); ?>">
                        <div class="nav-icon"><i class="fa-solid fa-file-invoice"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.invoice'); ?></span>
                    </a>
                </li>

                <!-- รับชำระหนี้ -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/payments'); ?>">
                        <div class="nav-icon"><i class="fa-brands fa-paypal"></i></i></div>
                        <span class="nav-text"><?php echo t('sidebar.payment'); ?></span>
                    </a>
                </li>

                
                <!-- สินค้า -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/products'); ?>">
                        <div class="nav-icon"><i class="fa-solid fa-warehouse"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.product_data'); ?></span>
                    </a>
                </li>

                <?
                if($role === 'admin'){
                    ?>
                    <!-- ผู้ใช้ -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('/users'); ?>">
                            <div class="nav-icon"><i class="fas fa-users-cog"></i></div>
                            <span class="nav-text"><?php echo t('sidebar.user_management'); ?></span>
                        </a>
                    </li>
                    <!-- นำเข้าผู้ใช้ -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('/import-users'); ?>">
                            <div class="nav-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                            <span class="nav-text"><?php echo t('sidebar.import_user_data'); ?></span>
                        </a>
                    </li>
                    <?
                }
                ?>

                

                

                <!-- รายงาน -->
                <!-- <li class="nav-item has-submenu">
                    <a href="#" class="nav-link submenu-toggle">
                        <div class="nav-icon"><i class="bi bi-graph-up"></i></div>
                        <span class="nav-text"><?php echo t('sidebar.reports'); ?></span>
                        <i class="bi bi-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="<?php echo url('/reports/sales'); ?>"><?php echo t('sidebar.sales_report'); ?></a></li>
                        <li><a href="<?php echo url('/reports/users'); ?>"><?php echo t('sidebar.user_report'); ?></a></li>
                        <li><a href="<?php echo url('/reports/system'); ?>"><?php echo t('sidebar.system_logs'); ?></a></li>
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
                        <li><a href="<?php echo url('/settings/general'); ?>"><?php echo t('sidebar.general_settings'); ?></a></li>
                        <li><a href="<?php echo url('/settings/appearance'); ?>"><?php echo t('sidebar.appearance'); ?></a></li>
                        <li><a href="<?php echo url('/settings/notifications'); ?>"><?php echo t('sidebar.notifications'); ?></a></li>
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



<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay"></div>