// ฟังก์ชันสำหรับเปิด/ปิด Sidebar
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');

    if (sidebar.classList.contains('hidden')) {
        openSidebar();
    } else {
        closeSidebar();
    }
}

// ฟังก์ชันสำหรับเปิด Sidebar
function openSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const overlay = document.querySelector('.sidebar-overlay');

    sidebar.classList.remove('hidden');

    // สำหรับ desktop
    if (window.innerWidth > 768) {
        if (mainContent) {
            mainContent.style.marginLeft = '280px';
        }
    } else {
        // สำหรับ mobile
        sidebar.classList.add('show');
        if (overlay) {
            overlay.classList.add('show');
        }
        // ป้องกันการ scroll ด้านหลัง
        document.body.style.overflow = 'hidden';
    }

    localStorage.setItem("sidebarState", "open");
}

// ฟังก์ชันสำหรับปิด Sidebar
function closeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const overlay = document.querySelector('.sidebar-overlay');

    sidebar.classList.add('hidden');
    sidebar.classList.remove('show'); // ลบ class show สำหรับ mobile

    // สำหรับ desktop
    if (window.innerWidth > 768) {
        if (mainContent) {
            mainContent.style.marginLeft = '0';
        }
    } else {
        // สำหรับ mobile
        if (overlay) {
            overlay.classList.remove('show');
        }
        // อนุญาตให้ scroll ได้ใหม่
        document.body.style.overflow = '';
    }
    localStorage.setItem("sidebarState", "closed");
}

// ฟังก์ชันเริ่มต้นเมื่อโหลดหน้า
function initSidebar() {
    // ตรวจสอบสถานะ sidebar จาก localStorage
    const savedState = localStorage.getItem("sidebarState");
    if (savedState === "closed") {
        closeSidebar();
    } else {
        openSidebar();
    }

    // ปุ่มปิด/เปิด Sidebar หลักใน navbar (สำหรับ desktop เท่านั้น)
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    // ปุ่มเปิด Sidebar เมื่อถูกปิดแล้ว (สำหรับ desktop)
    const sidebarOpen = document.getElementById('sidebarOpen');
    if (sidebarOpen) {
        sidebarOpen.addEventListener('click', openSidebar);
    }

    // ปุ่มเปิด Sidebar สำหรับมือถือ (จาก bottom navigation)
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', openSidebar);
    }

    // สำหรับ Mobile - ปิด Sidebar เมื่อคลิก overlay
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    // สำหรับ Mobile - ปิด Sidebar เมื่อคลิกลิงก์ใน sidebar (เฉพาะลิงก์ที่ไม่ใช่ submenu)
    const navLinks = document.querySelectorAll('.sidebar .nav-link[href]:not(.submenu-toggle)');
    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    });

    // ปิด sidebar เมื่อกดปุ่ม Escape
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    // ตั้งค่า active menu ตาม URL ปัจจุบัน
    setActiveMenu();

    // ปิด sidebar เมื่อ resize หน้าจอจาก mobile ไป desktop
    window.addEventListener('resize', handleResize);

    // ตั้งค่าเริ่มต้นตามขนาดหน้าจอ
    handleResize();
}

// ฟังก์ชันตั้งค่าเมนู active ตาม URL
function setActiveMenu() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar .nav-link');

    navLinks.forEach(link => {
        link.classList.remove('active');
        const href = link.getAttribute('href');
        if (href && currentPath === href) {
            link.classList.add('active');
        }
    });
}

// ฟังก์ชันจัดการการ resize หน้าจอ
function handleResize() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const overlay = document.querySelector('.sidebar-overlay');

    if (window.innerWidth > 768) {
        // บน desktop
        if (!sidebar.classList.contains('hidden')) {
            if (mainContent) {
                mainContent.style.marginLeft = '280px';
            }
        } else {
            if (mainContent) {
                mainContent.style.marginLeft = '0';
            }
        }
        document.body.style.overflow = '';

        // ซ่อน overlay และลบ class show
        if (overlay) {
            overlay.classList.remove('show');
        }
        sidebar.classList.remove('show');
    } else {
        // บน mobile
        if (mainContent) {
            mainContent.style.marginLeft = '0';
        }

        // ถ้า sidebar ไม่ใช่สถานะ hidden ให้แสดง overlay
        if (!sidebar.classList.contains('hidden')) {
            sidebar.classList.add('show');
            if (overlay) {
                overlay.classList.add('show');
            }
            document.body.style.overflow = 'hidden';
        } else {
            sidebar.classList.remove('show');
            if (overlay) {
                overlay.classList.remove('show');
            }
            document.body.style.overflow = '';
        }
    }
}

// ฟังก์ชันจัดการ Submenu
function initSubmenu() {
    const submenuToggles = document.querySelectorAll('.submenu-toggle');

    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const parent = this.closest('.has-submenu');
            const isOpen = parent.classList.contains('open');

            // บนมือถือ: ปิด submenu อื่นๆ ที่เปิดอยู่
            if (window.innerWidth <= 768) {
                const otherSubmenus = document.querySelectorAll('.has-submenu.open');
                otherSubmenus.forEach(other => {
                    if (other !== parent) {
                        other.classList.remove('open');
                    }
                });
            }

            // Toggle submenu ปัจจุบัน
            parent.classList.toggle('open');

            // บนมือถือ: ป้องกันการปิด sidebar เมื่อคลิก submenu
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('.sidebar');
                if (!sidebar.classList.contains('show')) {
                    openSidebar();
                }
            }
        });
    });

    // ปิด submenu เมื่อคลิกนอกพื้นที่บนมือถือ
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 768) {
            const isClickInsideSubmenu = e.target.closest('.has-submenu');
            const isClickInsideSidebar = e.target.closest('.sidebar');

            if (!isClickInsideSubmenu && isClickInsideSidebar) {
                const openSubmenus = document.querySelectorAll('.has-submenu.open');
                openSubmenus.forEach(submenu => {
                    submenu.classList.remove('open');
                });
            }
        }
    });
}

// ฟังก์ชันจัดการ Mobile Notifications
function initMobileNotifications() {
    const mobileNotificationsToggle = document.querySelector('.mobile-notifications-toggle');
    const mobileNotificationsPanel = document.querySelector('.mobile-notifications-panel');
    const mobilePanelClose = document.querySelector('.mobile-panel-close');

    // Toggle Notifications Panel
    function toggleNotificationsPanel() {
        mobileNotificationsPanel.classList.toggle('show');
        document.body.style.overflow = mobileNotificationsPanel.classList.contains('show') ? 'hidden' : '';
    }

    // Close Notifications Panel
    function closeNotificationsPanel() {
        mobileNotificationsPanel.classList.remove('show');
        document.body.style.overflow = '';
    }

    // Event Listeners
    if (mobileNotificationsToggle) {
        mobileNotificationsToggle.addEventListener('click', toggleNotificationsPanel);
    }

    if (mobilePanelClose) {
        mobilePanelClose.addEventListener('click', closeNotificationsPanel);
    }

    // Close panel when clicking outside
    document.addEventListener('click', function (e) {
        if (mobileNotificationsPanel.classList.contains('show') &&
            !mobileNotificationsToggle.contains(e.target) &&
            !mobileNotificationsPanel.contains(e.target)) {
            closeNotificationsPanel();
        }
    });
}

// ฟังก์ชันอัพเดท Mobile Page Title
function updateMobilePageTitle() {
    const pageTitle = document.title.split('|')[0].trim();
    const mobileTitleElement = document.querySelector('.mobile-page-title span');
    if (mobileTitleElement) {
        mobileTitleElement.textContent = pageTitle;
    }
}

// เริ่มต้นเมื่อ DOM โหลดเสร็จ
document.addEventListener('DOMContentLoaded', function () {
    initSidebar();
    initSubmenu();
    initMobileNotifications();
    updateMobilePageTitle();
});

