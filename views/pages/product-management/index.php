<?php

// โหลด config
$headerConfig = require BASE_PATH . '/config/header.php';
?>
<style>
#productsTable thead {
  position: sticky;
  top: 0;
  z-index: 2;
  background: #f8f9fa; /* หรือใช้ theme สีที่ต้องการ */
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Header -->
            <?php
           
            
            echo pageHeader(t('product_management.title'), '', '', 'fas fa-warehouse'); 
            ?>

            <!-- สถิติ -->
            <div class="row">
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card text-center">
                        <div class="card-body py-2">
                            <div class="small text-muted"><?php echo t('product_management.total_products'); ?></div>
                            <h4 class="mb-0 text-primary"><?php echo number_format(isset($stats['total']) ? $stats['total'] : 0); ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตัวกรองข้อมูล -->
            <div class="card <?php echo $headerConfig['classes']['filter_card']; ?>" style="position: relative; z-index: 100; overflow: visible;">
                <div class="card-body py-2" style="overflow: visible;">
                    <div class="row align-items-end" style="overflow: visible;">
                        <div class="col-md-6 col-lg-4">
                            <div class="mb-0">
                                <label for="searchUser" class="form-label fw-bold small mb-1">
                                    <i class="fas fa-search"></i> <?php echo t('product_management.search'); ?>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm mb-1" id="searchUser" 
                                           placeholder="<?php echo t('product_management.product_code'); ?>...">
                                    <button type="button" class="btn btn-outline-secondary btn-sm mb-1" id="resetSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                  
                    </div>
                </div>
            </div>

            <!-- ตารางผู้ใช้ -->
            <div class="card" style="position: relative; z-index: 1;">
                <?php 
                $productCount = isset($products) && is_array($products) ? count($products) : 0;
                echo cardHeader(
                    t('product_management.product_list'), 
                    $productCount, 
                    'fas fa-warehouse'
                ); 
                ?>
                
                <div class="card-body p-0">
                    <?php if (isset($products) && !empty($products)): ?>
                        <div class="table-responsive" style="<?php echo $headerConfig['styles']['table_responsive']; ?>; position: relative; z-index: 1;">
                            <table class="table table-sm table-hover mb-0" id="productsTable">
                                <thead class="table-light" >
                                    <tr>
                                        <th width="40" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>">#</th>
                                        <th width="120" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.product_code'); ?></th>
                                        <th class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.description'); ?></th>
                                        <th width="300" class="d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.group'); ?></th>
                                        <th width="300" class="d-none d-lg-table-cell" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.category'); ?></th>
                                        <th width="100" class="text-center" style="<?php echo $headerConfig['styles']['table_header']; ?>"><?php echo t('product_management.actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $index => $product): ?>
                                        <tr data-pcode="<?php echo htmlspecialchars($product['pcode']); ?>" 
                                            data-pdesc="<?php echo htmlspecialchars(!empty($product['pdesc']) ? $product['pdesc'] : ''); ?>"
                                            data-category="<?php echo htmlspecialchars(!empty($product['cate_name']) ? $product['cate_name'] : ''); ?>"
                                            data-group="<?php echo htmlspecialchars(!empty($product['groupname']) ? $product['groupname'] : ''); ?>">
                                            <td class="text-center text-muted small"><?php echo $index + 1; ?></td>
                                         
                                            <td>
                                                <div class="fw-bold small"><?php echo htmlspecialchars($product['pcode']); ?></div>
                                            </td>
                                            <td>
                                                <div class="small"><?php echo htmlspecialchars(!empty($product['pdesc']) ? $product['pdesc'] : '-'); ?></div>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <div class="small"><?php echo htmlspecialchars(!empty($product['cate_name']) ? $product['cate_name'] : '-'); ?></div>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <div class="small"><?php echo htmlspecialchars(!empty($product['groupname']) ? $product['groupname'] : '-'); ?></div>
                                            </td>
                                         
                                         
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?php echo url('/products/edit/' . $product['pcode']); ?>" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="<?php echo t('product_management.edit'); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted"><?php echo t('user_management.user_not_found'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// รอให้ DOM โหลดเสร็จ
document.addEventListener('DOMContentLoaded', function() {
    const searchUser = document.getElementById('searchUser');
    const resetSearch = document.getElementById('resetSearch');

    function filterTable() {
        const searchValue = searchUser ? searchUser.value.toLowerCase().trim() : '';

        document.querySelectorAll('#productsTable tbody tr').forEach(function(row) {
            const pcode = (row.getAttribute('data-pcode') || '').toLowerCase();
            const pdesc = (row.getAttribute('data-pdesc') || '').toLowerCase();
            const category = (row.getAttribute('data-category') || '').toLowerCase();
            const group = (row.getAttribute('data-group') || '').toLowerCase();

            // ค้นหาจาก pcode, pdesc, category หรือ group
            const matchSearch = !searchValue || 
                              pcode.includes(searchValue) || 
                              pdesc.includes(searchValue) ||
                              category.includes(searchValue) ||
                              group.includes(searchValue);

            row.style.display = matchSearch ? '' : 'none';
        });

        // นับจำนวนแถวที่แสดง
        const visibleRows = document.querySelectorAll('#productsTable tbody tr:not([style*="display: none"])').length;
        console.log('Products found:', visibleRows);
    }

    if (searchUser) {
        searchUser.addEventListener('input', filterTable);
    }

    if (resetSearch) {
        resetSearch.addEventListener('click', function() {
            if (searchUser) searchUser.value = '';
            filterTable();
        });
    }
});
</script>


