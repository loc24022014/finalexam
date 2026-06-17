<?php
$pageTitle = 'Chỉnh Sửa Xe - Admin';
$activeMenu = 'products';
ob_start();
?>

<div class="container py-5">
    <div class="back-link-wrap">
        <a href="<?= BASE_URL ?>/Product" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:16px;height:16px;margin-right:0.5rem;"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Quay lại danh sách
        </a>
    </div>

    <div class="form-wrapper glass-card mt-4 max-w-3xl mx-auto">
        <div class="form-header">
            <h1 class="form-title">Chỉnh Sửa Xe</h1>
            <p class="form-subtitle">Cập nhật thông tin chi tiết và hình ảnh của mẫu xe</p>
        </div>

        <div id="ajax-errors" class="auth-alert alert-error" style="margin-bottom: 1.5rem; display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="alert-icon">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div class="alert-content">
                <strong>Có lỗi xảy ra:</strong>
                <ul id="error-list" class="mb-0 mt-1 pl-4" style="list-style-type:disc; padding-left: 1.2rem;">
                </ul>
            </div>
        </div>

        <form id="edit-product-form" class="premium-form">
            <div class="form-grid">
                <div class="form-group col-span-2">
                    <label for="name" class="form-label">Tên Dòng Xe <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-input" value="<?= htmlspecialchars($product->name) ?>" placeholder="Ví dụ: Ford Everest Titanium 2024" required>
                </div>

                <div class="form-group">
                    <label for="brand" class="form-label">Hãng Xe (Brand)</label>
                    <input type="text" id="brand" name="brand" class="form-input" value="<?= htmlspecialchars($product->brand ?? '') ?>" placeholder="Ví dụ: Ford, Toyota, BMW...">
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">Giá Bán (₫) <span class="required">*</span></label>
                    <input type="number" id="price" name="price" class="form-input" value="<?= htmlspecialchars($product->price) ?>" placeholder="Ví dụ: 1350000000" min="0" required>
                </div>

                <div class="form-group col-span-2">
                    <label for="category_id" class="form-label">Phân Khúc / Danh Mục <span class="required">*</span></label>
                    <select id="category_id" name="category_id" class="form-input" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->id ?>" <?= $category->id == $product->category_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-span-2">
                    <label for="description" class="form-label">Mô Tả Chi Tiết <span class="required">*</span></label>
                    <textarea id="description" name="description" class="form-input" rows="6" placeholder="Mô tả thông số kỹ thuật, động cơ, trang bị nội ngoại thất..." required><?= htmlspecialchars($product->description) ?></textarea>
                </div>
            </div>

            <div class="form-actions mt-4 border-top pt-4 d-flex justify-end gap-2">
                <a href="<?= BASE_URL ?>/Product" class="btn btn-outline">Huỷ</a>
                <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#edit-product-form').on('submit', function(e) {
        e.preventDefault();
        
        var id = '<?= $product->id ?>';
        var data = {
            name: $('#name').val(),
            brand: $('#brand').val(),
            price: $('#price').val(),
            category_id: $('#category_id').val(),
            description: $('#description').val()
        };
        
        $('#ajax-errors').hide();
        $('#error-list').empty();

        $.ajax({
            url: '<?= BASE_URL ?>/api/product/' + id,
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?= BASE_URL ?>/Product';
                } else {
                    $('#error-list').append('<li>' + (response.message || 'Lỗi không xác định') + '</li>');
                    $('#ajax-errors').show();
                }
            },
            error: function(xhr) {
                var res = xhr.responseJSON;
                if (res && res.errors) {
                    for (var key in res.errors) {
                        $('#error-list').append('<li>' + res.errors[key] + '</li>');
                    }
                } else {
                    $('#error-list').append('<li>' + (res && res.message ? res.message : 'Có lỗi xảy ra khi kết nối máy chủ') + '</li>');
                }
                $('#ajax-errors').show();
            }
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>