<?php

$role = $user['role'];

?>
<div class="container-fluid p-0 m-0" style="width:100%">
    <!-- Welcome Header -->
    <div class="row mb-2 p-0 m-0">
        <div class="col-12">
            <div class="p-4 text-white rounded animate-fade-in" style="background: linear-gradient(135deg, #87b12dff 0%, #8eb951ff 100%); box-shadow: 0 10px 30px rgba(135, 177, 45, 0.3);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="h2 mb-2 fw-bold animate-slide-down"><?php echo t('dashboard.title'); ?></h1>
                        <p class="mb-0 fs-5 opacity-85 animate-slide-up"><?php echo t('dashboard.welcome'); ?></p>
                        <div class="mt-2">
                            <small class="opacity-85 fs-6 animate-fade-in-delay"><?php echo date('l, F j, Y'); ?> | <?php echo date('H:i:s'); ?></small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center animate-pulse" style="background: rgba(255, 255, 255, 0.2); width: 60px; height: 60px;">
                            <i class="bi bi-graph-up-arrow fs-4 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 p-0 m-0">
        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card border-0 shadow h-100 animate-card-pop" style="border-left: 4px solid #D3EE98 !important; animation-delay: 0.1s;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-uppercase mb-2" style="color: #8BA85F; font-size: 0.9rem;">
                                <i class="bi bi-people me-1"></i><?php echo t('user_management.total_users'); ?>
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800 counter" data-target="<?php echo $stats['totalUsers']; ?>">0</div>
                            <div class="mt-2">
                                <small class="text-success fw-medium" style="font-size: 0.85rem;">
                                    <i class="bi bi-arrow-up animate-bounce"></i> <?php echo t('user_management.active_users'); ?>
                                </small>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <div class="p-3 rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #D3EE98, #C4E87A); width: 70px; height: 70px;">
                                <i class="bi bi-people fs-2 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card border-0 shadow h-100 animate-card-pop" style="border-left: 4px solid #A8D46F !important; animation-delay: 0.2s;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-uppercase mb-2" style="color: #7AA35C; font-size: 0.9rem;">
                                <i class="bi bi-speedometer2 me-1"></i><?php echo t('meter_management.title'); ?>
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800 counter" data-target="<?php echo $stats['totalMeters']; ?>">0</div>
                            <div class="mt-2">
                                <small class="text-info fw-medium" style="font-size: 0.85rem;">
                                    <i class="bi bi-clock me-1"></i> <?php echo t('dashboard.this_month'); ?>: <span class="counter" data-target="<?php echo $stats['currentMonthMeters']; ?>">0</span>
                                </small>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <div class="p-3 rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #A8D46F, #95C257); width: 70px; height: 70px;">
                                <i class="bi bi-speedometer2 fs-2 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card border-0 shadow h-100 animate-card-pop" style="border-left: 4px solid #8BA85F !important; animation-delay: 0.3s;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-uppercase mb-2" style="color: #6C8B47; font-size: 0.9rem;">
                                <i class="bi bi-receipt me-1"></i><?php echo t('invoice_management.title'); ?>
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800 counter" data-target="<?php echo $stats['totalInvoices']; ?>">0</div>
                            <div class="mt-2">
                                <small class="text-warning fw-medium" style="font-size: 0.85rem;">
                                    <i class="bi bi-currency-exchange me-1"></i><span class="counter" data-target="<?php echo $stats['totalInvoiceAmount']; ?>">0</span>&nbsp;<?php echo t('currency.baht'); ?>
                                </small>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <div class="p-3 rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #8BA85F, #7A974B); width: 70px; height: 70px;">
                                <i class="bi bi-receipt fs-2 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card border-0 shadow h-100 animate-card-pop" style="border-left: 4px solid #7AA35C !important; animation-delay: 0.4s;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-uppercase mb-2" style="color: #5A7A3C; font-size: 0.9rem;">
                                <i class="bi bi-credit-card me-1"></i><?php echo t('payment_management.title'); ?>
                            </div>
                            <div class="h3 mb-0 fw-bold text-gray-800 counter" data-target="<?php echo $stats['totalPayments']; ?>">0</div>
                            <div class="mt-2">
                                <small class="text-success fw-medium" style="font-size: 0.85rem;">
                                    <i class="bi bi-cash-coin me-1"></i><span class="counter" data-target="<?php echo $stats['totalPaymentAmount']; ?>">0</span>&nbsp;<?php echo t('currency.baht'); ?>
                                </small>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <div class="p-3 rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #7AA35C, #698F47); width: 70px; height: 70px;">
                                <i class="bi bi-credit-card fs-2 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <?
    if($role == 'admin'){
        ?>
        <div class="row g-3 p-0 m-0">
        <!-- Monthly Revenue Chart -->
        <div class="col-lg-6 col-md-12">
            <div class="card shadow border-0 h-100 animate-slide-left">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #F8FDF0;">
                    <h5 class="m-0 fw-bold" style="color: #5A7A3C;">
                        <i class="bi bi-graph-up me-2"></i><?php echo t('dashboard.monthly_revenue'); ?>
                    </h5>
                    <span class="badge rounded-pill" style="background-color: #D3EE98; color: #5A7A3C;">
                        <?php echo date('Y'); ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meter Reading Trends -->
        <div class="col-lg-6 col-md-12">
            <div class="card shadow border-0 h-100 animate-slide-right">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #F8FDF0;">
                    <h5 class="m-0 fw-bold" style="color: #5A7A3C;">
                        <i class="bi bi-speedometer me-2"></i><?php echo t('dashboard.meter_trends'); ?>
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn active" style="background-color: #D3EE98; color: #5A7A3C; border: none;"><?php echo t('meter_management.water'); ?></button>
                        <button type="button" class="btn" style="background-color: #F8FDF0; color: #5A7A3C; border: none;"><?php echo t('meter_management.electricity'); ?></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <canvas id="meterTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Additional Charts -->
    <div class="row g-3 p-0 m-0">
        <!-- User Distribution -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow border-0 h-100 animate-zoom-in">
                <div class="card-header bg-white py-3" style="border-bottom: 2px solid #F8FDF0;">
                    <h5 class="m-0 fw-bold" style="color: #5A7A3C;">
                        <i class="bi bi-pie-chart me-2"></i><?php echo t('dashboard.user_distribution'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 200px;">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                    <div class="row text-center mt-3">
                        <?php
                        $totalUsers = $stats['totalUsers'];
                        $userStats = array(
                            'agent' => 0, 
                            'admin' => 0
                        );
                        
                        foreach ($chartData['userDistribution'] as $dist) {
                            if (isset($userStats[$dist['role']])) {
                                $userStats[$dist['role']] = $dist['count'];
                            }
                        }
                        
                        $colors = array(
                            'agent' => array('class' => 'text-warning', 'label' => t('user_management.agent'), 'bg' => '#F5A623'),
                            'admin' => array('class' => 'text-success', 'label' => t('user_management.admin'), 'bg' => '#7ED321')
                        );
                        
                        foreach ($userStats as $role => $count):
                            $percentage = $totalUsers > 0 ? round(($count / $totalUsers) * 100) : 0;
                        ?>
                        <div class="col-6">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle me-2" style="width: 12px; height: 12px; background-color: <?php echo $colors[$role]['bg']; ?>"></div>
                                <div class="fw-bold <?php echo $colors[$role]['class']; ?>"><?php echo $percentage; ?>%</div>
                            </div>
                            <small class="text-muted"><?php echo $colors[$role]['label']; ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Status -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow border-0 h-100 animate-zoom-in" style="animation-delay: 0.1s;">
                <div class="card-header bg-white py-3" style="border-bottom: 2px solid #F8FDF0;">
                    <h5 class="m-0 fw-bold" style="color: #5A7A3C;">
                        <i class="bi bi-receipt me-2"></i><?php echo t('dashboard.invoice_status'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 200px;">
                        <canvas id="invoiceStatusChart"></canvas>
                    </div>
                    <div class="row text-center mt-3">
                        <?php
                        $totalInvoices = isset($chartData['invoiceStatus']['total']) ? $chartData['invoiceStatus']['total'] : 0;
                        $paidInvoices = isset($chartData['invoiceStatus']['paid']) ? $chartData['invoiceStatus']['paid'] : 0;
                        $unpaidInvoices = isset($chartData['invoiceStatus']['unpaid']) ? $chartData['invoiceStatus']['unpaid'] : 0;
                        
                        $paidPercentage = $totalInvoices > 0 ? round(($paidInvoices / $totalInvoices) * 100) : 0;
                        $unpaidPercentage = $totalInvoices > 0 ? round(($unpaidInvoices / $totalInvoices) * 100) : 0;
                        ?>
                        <div class="col-6">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle me-2" style="width: 12px; height: 12px; background-color: #8BA85F;"></div>
                                <div class="fw-bold text-success"><?php echo $paidPercentage; ?>%</div>
                            </div>
                            <small class="text-muted"><?php echo t('payment_management.paid'); ?></small>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="rounded-circle me-2" style="width: 12px; height: 12px; background-color: #7AA35C;"></div>
                                <div class="fw-bold text-warning"><?php echo $unpaidPercentage; ?>%</div>
                            </div>
                            <small class="text-muted"><?php echo t('payment_management.unpaid'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Comparison -->
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card shadow border-0 h-100 animate-zoom-in" style="animation-delay: 0.2s;">
                <div class="card-header bg-white py-3" style="border-bottom: 2px solid #F8FDF0;">
                    <h5 class="m-0 fw-bold" style="color: #5A7A3C;">
                        <i class="bi bi-bar-chart me-2"></i><?php echo t('dashboard.monthly_comparison'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 200px;">
                        <canvas id="monthlyComparisonChart"></canvas>
                    </div>
                    <div class="row text-center mt-3">
                        <?php
                        $currentData = array();
                        $previousData = array();
                        
                        foreach ($chartData['monthlyComparison'] as $data) {
                            if ($data['period'] === 'current') {
                                $currentData = $data;
                            } else {
                                $previousData = $data;
                            }
                        }
                        
                        $meterGrowth = (isset($previousData['meters']) && $previousData['meters'] > 0) ? 
                            round((($currentData['meters'] - $previousData['meters']) / $previousData['meters']) * 100) : 0;
                        $invoiceGrowth = (isset($previousData['invoices']) && $previousData['invoices'] > 0) ? 
                            round((($currentData['invoices'] - $previousData['invoices']) / $previousData['invoices']) * 100) : 0;
                        ?>
                        <div class="col-6">
                            <div class="fw-bold animate-count-up" data-target="<?php echo abs($meterGrowth); ?>" style="color: #D3EE98; font-size: 1.5rem;">
                                <?php echo $meterGrowth >= 0 ? '+' : '-'; ?><span>0</span>%
                            </div>
                            <small class="text-muted"><?php echo t('meter_management.title'); ?></small>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold animate-count-up" data-target="<?php echo abs($invoiceGrowth); ?>" style="color: #A8D46F; font-size: 1.5rem;">
                                <?php echo $invoiceGrowth >= 0 ? '+' : '-'; ?><span>0</span>%
                            </div>
                            <small class="text-muted"><?php echo t('invoice_management.title'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <?
    }
    ?>

    <!-- Recent Activity & Quick Actions -->
    <div class="row g-3 p-0 m-0">
        <div class="col-lg-8 col-md-12">
            <div class="card shadow border-0 h-100 animate-slide-up">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid #F8FDF0;">
                    <h5 class="m-0 fw-bold" style="color: #5A7A3C;">
                        <i class="bi bi-clock-history me-2"></i><?php echo t('dashboard.recent_activity'); ?>
                    </h5>
                    <span class="badge rounded-pill" style="background-color: #D3EE98; color: #5A7A3C;">
                        <?php echo count($recentActivities); ?> <?php echo t('dashboard.activities'); ?>
                    </span>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recentActivities)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentActivities as $index => $activity): ?>
                                <div class="list-group-item d-flex align-items-center border-0 py-3 px-3 animate-fade-in" style="animation-delay: <?php echo $index * 0.1; ?>s; border-bottom: 1px solid #F8FDF0 !important;">
                                    <div class="p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="background-color: #F8FDF0; width: 45px; height: 45px;">
                                        <i class="<?php echo $activity['icon']; ?> <?php echo $activity['color']; ?>" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold" style="font-size: 1rem;"><?php echo $activity['message']; ?></div>
                                        <small class="text-muted fw-medium" style="font-size: 0.9rem;">
                                            <i class="bi bi-clock me-1"></i><?php echo $activity['time']; ?>
                                        </small>
                                    </div>
                                    <span class="badge fw-medium animate-pulse" style="background-color: #D3EE98; color: #5A7A3C; font-size: 0.85rem;">
                                        <?php echo t('dashboard.' . $activity['type']); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 animate-fade-in">
                            <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                            <p class="text-muted mt-2 mb-0 fw-medium" style="font-size: 1.1rem;"><?php echo t('dashboard.no_recent_activity'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card shadow border-0 h-100 animate-slide-up" style="animation-delay: 0.1s;">
                <div class="card-header bg-white py-3" style="border-bottom: 2px solid #F8FDF0;">
                    <h5 class="m-0 fw-bold" style="color: #5A7A3C;">
                        <i class="bi bi-lightning me-2"></i><?php echo t('dashboard.quick_actions'); ?>
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-3">
                        <a href="/meters" class="btn text-start p-3 rounded d-flex align-items-center fw-medium animate-hover-grow" style="background: linear-gradient(135deg, #F8FDF0, #E8F8D8); border: 2px solid #D3EE98; font-size: 1rem; transition: all 0.3s ease;">
                            <div class="p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #A8D46F, #95C257); width: 45px; height: 45px;">
                                <i class="bi bi-speedometer2 fs-5 text-white"></i>
                            </div>
                            <span><?php echo t('meter_management.title'); ?></span>
                            <i class="bi bi-arrow-right ms-auto fs-6" style="color: #A8D46F;"></i>
                        </a>
                        <a href="/invoices" class="btn text-start p-3 rounded d-flex align-items-center fw-medium animate-hover-grow" style="background: linear-gradient(135deg, #F8FDF0, #E8F8D8); border: 2px solid #D3EE98; font-size: 1rem; transition: all 0.3s ease;">
                            <div class="p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #8BA85F, #7A974B); width: 45px; height: 45px;">
                                <i class="bi bi-receipt fs-5 text-white"></i>
                            </div>
                            <span><?php echo t('invoice_management.title'); ?></span>
                            <i class="bi bi-arrow-right ms-auto fs-6" style="color: #8BA85F;"></i>
                        </a>
                        <a href="/payments" class="btn text-start p-3 rounded d-flex align-items-center fw-medium animate-hover-grow" style="background: linear-gradient(135deg, #F8FDF0, #E8F8D8); border: 2px solid #D3EE98; font-size: 1rem; transition: all 0.3s ease;">
                            <div class="p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #7AA35C, #698F47); width: 45px; height: 45px;">
                                <i class="bi bi-credit-card fs-5 text-white"></i>
                            </div>
                            <span><?php echo t('payment_management.title'); ?></span>
                            <i class="bi bi-arrow-right ms-auto fs-6" style="color: #7AA35C;"></i>
                        </a>
                        <a href="/products" class="btn text-start p-3 rounded d-flex align-items-center fw-medium animate-hover-grow" style="background: linear-gradient(135deg, #F8FDF0, #E8F8D8); border: 2px solid #D3EE98; font-size: 1rem; transition: all 0.3s ease;">
                            <div class="p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #D3EE98, #C4E87A); width: 45px; height: 45px;">
                                <i class="bi bi-house-exclamation-fill fs-5 text-white"></i>
                            </div>
                            <span><?php echo t('product_management.title'); ?></span>
                            <i class="bi bi-arrow-right ms-auto fs-6" style="color: #D3EE98;"></i>
                        </a>
                    </div> 
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Animation Classes */
.animate-fade-in {
    animation: fadeIn 0.8s ease-in-out;
}

.animate-fade-in-delay {
    animation: fadeIn 1s ease-in-out 0.3s both;
}

.animate-slide-down {
    animation: slideDown 0.8s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
}

.animate-slide-left {
    animation: slideLeft 0.8s ease-out;
}

.animate-slide-right {
    animation: slideRight 0.8s ease-out;
}

.animate-card-pop {
    animation: cardPop 0.6s ease-out;
}

.animate-zoom-in {
    animation: zoomIn 0.6s ease-out;
}

.animate-pulse {
    animation: pulse 2s infinite;
}

.animate-bounce {
    animation: bounce 2s infinite;
}

.animate-hover-grow {
    transition: all 0.3s ease;
}

.animate-hover-grow:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 8px 25px rgba(211, 238, 152, 0.3) !important;
}

/* Keyframe Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from { 
        opacity: 0;
        transform: translateY(-30px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(30px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideLeft {
    from { 
        opacity: 0;
        transform: translateX(-30px);
    }
    to { 
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideRight {
    from { 
        opacity: 0;
        transform: translateX(30px);
    }
    to { 
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes cardPop {
    0% {
        opacity: 0;
        transform: scale(0.8) translateY(20px);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: scale(0.5);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        transform: translate3d(0,0,0);
    }
    40%, 43% {
        transform: translate3d(0,-8px,0);
    }
    70% {
        transform: translate3d(0,-4px,0);
    }
    90% {
        transform: translate3d(0,-2px,0);
    }
}

/* Card Hover Effects */
.card {
    transition: all 0.3s ease;
    border: 1px solid rgba(211, 238, 152, 0.1);
}

.card:hover {
    /* transform: translateY(-5px); */
    box-shadow: 0 12px 35px rgba(211, 238, 152, 0.15) !important;
    border-color: rgba(211, 238, 152, 0.3);
}

/* Custom Scrollbar */
.list-group-flush::-webkit-scrollbar {
    width: 6px;
}

.list-group-flush::-webkit-scrollbar-track {
    background: #F8FDF0;
}

.list-group-flush::-webkit-scrollbar-thumb {
    background: #D3EE98;
    border-radius: 3px;
}

.list-group-flush::-webkit-scrollbar-thumb:hover {
    background: #A8D46F;
}

/* Gradient Text */
.gradient-text {
    background: linear-gradient(135deg, #87b12dff, #8eb951ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .row.g-3 {
        margin-left: -5px;
        margin-right: -5px;
    }
    
    .row.g-3 > [class*="col-"] {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .h3 { font-size: 1.5rem !important; }
    .h4 { font-size: 1.3rem !important; }
    .h5 { font-size: 1.1rem !important; }
    
    .card-body {
        padding: 1rem !important;
    }
    
    .d-grid .btn {
        min-height: 50px;
        padding: 0.75rem !important;
    }
    
    .chart-container {
        height: 200px !important;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .row.g-3 {
        margin-left: -3px;
        margin-right: -3px;
    }
    
    .row.g-3 > [class*="col-"] {
        padding-left: 3px;
        padding-right: 3px;
    }
    
    .h3 { font-size: 1.3rem !important; }
    .h4 { font-size: 1.1rem !important; }
    .h5 { font-size: 1rem !important; }
    
    .chart-container {
        height: 180px !important;
    }
}

/* Smooth transitions */
* {
    transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
}

/* Custom badge styles */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* Button enhancements */
.btn {
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target.toLocaleString();
                }
            };
            
            updateCounter();
        });
    }

    // Percentage Counter Animation
    function animatePercentageCounters() {
        const percentageCounters = document.querySelectorAll('.animate-count-up');
        percentageCounters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const span = counter.querySelector('span');
            const duration = 1500;
            const step = target / (duration / 16);
            let current = 0;
            
            const updateCounter = () => {
                current += step;
                if (current < target) {
                    span.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    span.textContent = target;
                }
            };
            
            updateCounter();
        });
    }

    // Initialize animations after page load
    setTimeout(() => {
        animateCounters();
        animatePercentageCounters();
    }, 500);

    // Chart colors based on theme
    const chartColors = {
        primary: '#D3EE98',
        secondary: '#A8D46F',
        success: '#8BA85F',
        warning: '#7AA35C',
        info: '#6C8B47',
        light: '#F8FDF0'
    };

    // Prepare data from PHP
    const monthlyRevenueData = <?php echo json_encode($chartData['monthlyRevenue']); ?>;
    const monthlyMetersData = <?php echo json_encode($chartData['monthlyMeters']); ?>;
    const userDistributionData = <?php echo json_encode($chartData['userDistribution']); ?>;
    const invoiceStatusData = <?php echo json_encode($chartData['invoiceStatus']); ?>;
    const monthlyComparisonData = <?php echo json_encode($chartData['monthlyComparison']); ?>;

    // Process monthly revenue data
    const months = ['<?php echo t("month.january"); ?>', '<?php echo t("month.february"); ?>', '<?php echo t("month.march"); ?>', '<?php echo t("month.april"); ?>', '<?php echo t("month.may"); ?>', '<?php echo t("month.june"); ?>', '<?php echo t("month.july"); ?>', '<?php echo t("month.august"); ?>', '<?php echo t("month.september"); ?>', '<?php echo t("month.october"); ?>', '<?php echo t("month.november"); ?>', '<?php echo t("month.december"); ?>'];
    const revenueByMonth = Array(12).fill(0);
    
    monthlyRevenueData.forEach(function(item) {
        const monthIndex = parseInt(item.month) - 1;
        revenueByMonth[monthIndex] = parseFloat(item.total) || 0;
    });

    // Process meter trends data
    const waterMetersByMonth = Array(12).fill(0);
    const electricityMetersByMonth = Array(12).fill(0);
    
    monthlyMetersData.forEach(function(item) {
        const monthIndex = parseInt(item.month) - 1;
        waterMetersByMonth[monthIndex] = parseInt(item.water_meters) || 0;
        electricityMetersByMonth[monthIndex] = parseInt(item.electricity_meters) || 0;
    });

    // Process user distribution data
    const userRoles = { agent: 0, admin: 0 };
    userDistributionData.forEach(function(item) {
        if (userRoles.hasOwnProperty(item.role)) {
            userRoles[item.role] = parseInt(item.count) || 0;
        }
    });

    // Process invoice status data
    const totalInvoices = parseInt(invoiceStatusData.total) || 0;
    const paidInvoices = parseInt(invoiceStatusData.paid) || 0;
    const unpaidInvoices = parseInt(invoiceStatusData.unpaid) || 0;

    // Process monthly comparison data
    let currentData = {}, previousData = {};
    monthlyComparisonData.forEach(function(item) {
        if (item.period === 'current') {
            currentData = item;
        } else {
            previousData = item;
        }
    });

    // Initialize Charts with animations
    initializeCharts();

    function initializeCharts() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: '<?php echo t("dashboard.revenue"); ?> (<?php echo t("currency.baht"); ?>)',
                    data: revenueByMonth,
                    borderColor: chartColors.primary,
                    backgroundColor: 'rgba(211, 238, 152, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: chartColors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: getChartOptions()
        });

        // Meter Trends Chart
        const meterTrendsCtx = document.getElementById('meterTrendsChart').getContext('2d');
        new Chart(meterTrendsCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: '<?php echo t("meter_management.water"); ?>',
                        data: waterMetersByMonth,
                        backgroundColor: chartColors.info,
                        borderColor: chartColors.info,
                        borderWidth: 1
                    },
                    {
                        label: '<?php echo t("meter_management.electricity"); ?>',
                        data: electricityMetersByMonth,
                        backgroundColor: chartColors.warning,
                        borderColor: chartColors.warning,
                        borderWidth: 1
                    }
                ]
            },
            options: getChartOptions(true)
        });

        // User Distribution Chart
        const userDistributionCtx = document.getElementById('userDistributionChart').getContext('2d');
        new Chart(userDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['<?php echo t("user_management.agent"); ?>', '<?php echo t("user_management.admin"); ?>'],
                datasets: [{
                    data: [userRoles.agent, userRoles.admin],
                    backgroundColor: ['#F5A623', '#7ED321'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: getDoughnutOptions()
        });

        // Invoice Status Chart
        const invoiceStatusCtx = document.getElementById('invoiceStatusChart').getContext('2d');
        new Chart(invoiceStatusCtx, {
            type: 'pie',
            data: {
                labels: ['<?php echo t("payment_management.paid"); ?>', '<?php echo t("payment_management.unpaid"); ?>'],
                datasets: [{
                    data: [paidInvoices, unpaidInvoices],
                    backgroundColor: [chartColors.success, chartColors.warning],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: getPieOptions()
        });

        // Monthly Comparison Chart
        const monthlyComparisonCtx = document.getElementById('monthlyComparisonChart').getContext('2d');
        new Chart(monthlyComparisonCtx, {
            type: 'bar',
            data: {
                labels: ['<?php echo t("meter_management.title"); ?>', '<?php echo t("invoice_management.title"); ?>', '<?php echo t("payment_management.title"); ?>'],
                datasets: [{
                    label: '<?php echo t("dashboard.this_month"); ?>',
                    data: [
                        parseInt(currentData.meters) || 0,
                        parseInt(currentData.invoices) || 0,
                        parseInt(currentData.payments) || 0
                    ],
                    backgroundColor: chartColors.primary,
                    borderColor: chartColors.primary,
                    borderWidth: 1
                }, {
                    label: '<?php echo t("dashboard.last_month"); ?>',
                    data: [
                        parseInt(previousData.meters) || 0,
                        parseInt(previousData.invoices) || 0,
                        parseInt(previousData.payments) || 0
                    ],
                    backgroundColor: chartColors.secondary,
                    borderColor: chartColors.secondary,
                    borderWidth: 1
                }]
            },
            options: getChartOptions(true)
        });
    }

    function getChartOptions(showLegend = false) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    display: showLegend,
                    position: 'top',
                    labels: {
                        font: { size: 12 },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 },
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        };
    }

    function getDoughnutOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        };
    }

    function getPieOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 11 },
                        usePointStyle: true
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        };
    }
});
</script>