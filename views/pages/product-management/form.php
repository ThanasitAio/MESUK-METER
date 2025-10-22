<?php
// โหลด config
$headerConfig = require BASE_PATH . '/config/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card <?php echo isset($headerConfig['classes']['filter_card']) ? $headerConfig['classes']['filter_card'] : ''; ?>">
                <div class="card-body py-2">
                    <div class="form-header mb-3">
                        <i class="fas fa-<?php echo $data['action'] === 'create' ? 'plus' : 'edit'; ?>"></i>
                        <h1 class="h4 d-inline-block ms-2 mb-0"><?php echo htmlspecialchars($data['title']); ?></h1>
                    </div>
                    <form id="userForm" action="/products/update/<?php echo htmlspecialchars($data['product']['pcode']); ?>" method="POST">
                        <input type="hidden" name="pcode" value="<?php echo htmlspecialchars($data['product']['pcode']); ?>">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12 col-md-2 mb-2">
                                    <div class="form-group">
                                        <label for="pcode"><?php echo t('product_management.product_code'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="pcode" value="<?php echo htmlspecialchars($data['product']['pcode']); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-2">
                                    <div class="form-group">
                                        <label for="pdesc"><?php echo t('product_management.description'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="pdesc" value="<?php echo htmlspecialchars($data['product']['pdesc']); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="groupname"><?php echo t('product_management.group'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="groupname" value="<?php echo htmlspecialchars($data['product']['groupname']); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="category"><?php echo t('product_management.category'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="category" value="<?php echo htmlspecialchars($data['product']['cate_name']); ?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-12 col-md-2 mb-2">
                                    <div class="form-group">
                                        <label for="meter_0_latest"><?php echo t('product_management.meter_0_latest'); ?></label>
                                        <input type="text" class="form-control form-control-sm text-end" id="meter_0_latest" 
                                            value="<?php echo htmlspecialchars(isset($data['product']['meter_0_latest']) ? $data['product']['meter_0_latest'] : '0'); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mb-2">
                                    <div class="form-group">
                                        <label for="meter_1_latest"><?php echo t('product_management.meter_1_latest'); ?></label>
                                        <input type="text" class="form-control form-control-sm text-end" id="meter_1_latest" 
                                            value="<?php echo htmlspecialchars(isset($data['product']['meter_1_latest']) ? $data['product']['meter_1_latest'] : '0'); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mb-2">
                                    <div class="form-group">
                                        <label for="meter_0_ppu"><?php echo t('product_management.meter_0_ppu'); ?></label>
                                        <input type="text" class="form-control form-control-sm text-end" id="meter_0_ppu" name="meter_0_ppu" 
                                            value="<?php echo htmlspecialchars(isset($data['product']['meter_0_ppu']) ? $data['product']['meter_0_ppu'] : '0'); ?>" autocomplete="off" OnKeyPress="return chkNumber(this)" onfocus="selectAllText(this)">
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 mb-2">
                                    <div class="form-group">
                                        <label for="meter_1_ppu"><?php echo t('product_management.meter_1_ppu'); ?></label>
                                        <input type="text" class="form-control form-control-sm text-end" id="meter_1_ppu" name="meter_1_ppu" 
                                            value="<?php echo htmlspecialchars(isset($data['product']['meter_1_ppu']) ? $data['product']['meter_1_ppu'] : '0'); ?>" autocomplete="off" OnKeyPress="return chkNumber(this)" onfocus="selectAllText(this)">
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="investor_owner"><?php echo t('product_management.investor_owner'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="investor_owner" name="investor_owner" value="<?php echo htmlspecialchars(isset($data['product']['investor_owner']) ? $data['product']['investor_owner'] : ''); ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="investor_address"><?php echo t('product_management.investor_address'); ?></label>
                                        <textarea class="form-control form-control-sm" id="investor_address" name="investor_address"><?php echo htmlspecialchars(isset($data['product']['investor_address']) ? $data['product']['investor_address'] : ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="investor_phone"><?php echo t('product_management.investor_phone'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="investor_phone" name="investor_phone" value="<?php echo htmlspecialchars(isset($data['product']['investor_phone']) ? $data['product']['investor_phone'] : ''); ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="tenant"><?php echo t('product_management.tenant'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="tenant" name="tenant" value="<?php echo htmlspecialchars(isset($data['product']['tenant']) ? $data['product']['tenant'] : ''); ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="tenant_phone"><?php echo t('product_management.tenant_phone'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="tenant_phone" name="tenant_phone" value="<?php echo htmlspecialchars(isset($data['product']['tenant_phone']) ? $data['product']['tenant_phone'] : ''); ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="tenant_tax_id"><?php echo t('product_management.tenant_tax_id'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="tenant_tax_id" name="tenant_tax_id" value="<?php echo htmlspecialchars(isset($data['product']['tenant_tax_id']) ? $data['product']['tenant_tax_id'] : ''); ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="bank_acc_name"><?php echo t('product_management.bank_acc_name'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="bank_acc_name" name="bank_acc_name" value="<?php echo htmlspecialchars(isset($data['product']['bank_acc_name']) ? $data['product']['bank_acc_name'] : ''); ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="bank_name"><?php echo t('product_management.bank_name'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="bank_name" name="bank_name" value="<?php echo htmlspecialchars(isset($data['product']['bank_name']) ? $data['product']['bank_name'] : ''); ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="bank_branch"><?php echo t('product_management.bank_branch'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="bank_branch" name="bank_branch" value="<?php echo htmlspecialchars(isset($data['product']['bank_branch']) ? $data['product']['bank_branch'] : ''); ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label for="bank_acc_no"><?php echo t('product_management.bank_acc_no'); ?></label>
                                        <input type="text" class="form-control form-control-sm" id="bank_acc_no" name="bank_acc_no" value="<?php echo htmlspecialchars(isset($data['product']['bank_acc_no']) ? $data['product']['bank_acc_no'] : ''); ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-footer">
                            <a href="/products" class="btn btn-secondary">
                                <i class="fas fa-times"></i> <?php echo t('user_management.cancel'); ?>
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo t('user_management.save'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php unset($_SESSION['old']); ?>

<script>
    // Handle form submission
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + <?php echo json_encode(t('product_management.save')); ?> + '...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(function(response) { 
            return response.json(); 
        })
        .then(function(data) {
            if (data.success) {
                Swal.fire({
                    title: <?php echo json_encode(t('product_management.update_success')); ?>,
                    text: data.message,
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false
                }).then(function() {
                    window.location.replace('/products');
                });
            } else {
                var errorMsg = data.errors ? data.errors.join('<br>') : data.message;
                Swal.fire({
                    title: 'Error',
                    html: errorMsg,
                    icon: 'error'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(function(error) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            Swal.fire({
                title: 'Error',
                text: 'Could not connect to server',
                icon: 'error'
            });
        });
    });

    function chkNumber(ele){
        var vchar = String.fromCharCode(event.keyCode);
        if ((vchar<'0' || vchar>'9') && (vchar != '.')) return false;
            ele.onKeyPress=vchar;
        
    }

    function selectAllText(element) {
    element.select();
}
</script>