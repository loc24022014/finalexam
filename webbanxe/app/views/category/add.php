<?php
$pageTitle = 'Thêm Phân Khúc Mới - Admin';
$activeMenu = 'categories';
ob_start();
?>

<div class="admin-page">
    <div class="back-link-wrap">
        <a href="<?= BASE_URL ?>/Category/list" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:16px;height:16px;margin-right:0.5rem;"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Quay lại danh sách
        </a>
    </div>

    <div class="form-wrapper glass-card mt-4 max-w-xl mx-auto" style="border: 1px solid var(--border-strong); padding: 2rem;">
        <div class="form-header">
            <h1 class="form-title" style="font-family: var(--font-heading); font-style: italic; text-transform: uppercase;">Thêm <span>Phân Khúc Mới</span></h1>
            <p class="form-subtitle" style="color: var(--text-muted); font-size: 0.9rem;">Tạo phân khúc / danh mục xe mới để phân loại các sản phẩm trong hệ thống</p>
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

        <form id="add-category-form" class="premium-form">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="name" class="form-label" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; color: var(--text-muted); font-weight: 700;">Tên Danh Mục <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Ví dụ: Xe Điện, Xe SUV hạng sang..." style="background: rgba(0,0,0,0.5); border: 1px solid var(--border); color: #fff; padding: 0.8rem;" required>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="slug" class="form-label" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; color: var(--text-muted); font-weight: 700;">Đường Dẫn Thân Thiện (Slug)</label>
                <input type="text" id="slug" name="slug" class="form-input" placeholder="Ví dụ: xe-dien-hang-sang (Để trống tự sinh)" style="background: rgba(0,0,0,0.5); border: 1px solid var(--border); color: #fff; padding: 0.8rem;">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="description" class="form-label" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; color: var(--text-muted); font-weight: 700;">Mô Tả Danh Mục</label>
                <textarea id="description" name="description" class="form-input" rows="4" placeholder="Mô tả ngắn gọn đặc trưng của phân khúc xe này..." style="background: rgba(0,0,0,0.5); border: 1px solid var(--border); color: #fff; padding: 0.8rem;"></textarea>
            </div>

            <div class="form-actions mt-4 border-top pt-4 d-flex justify-end gap-2" style="border-top: 1px solid var(--border-strong); padding-top: 1.5rem;">
                <a href="<?= BASE_URL ?>/Category/list" class="btn btn-outline">Huỷ</a>
                <button type="submit" class="btn btn-primary">Tạo Danh Mục</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#add-category-form').on('submit', function(e) {
        e.preventDefault();
        
        var data = {
            name: $('#name').val(),
            slug: $('#slug').val(),
            description: $('#description').val()
        };
        
        $('#ajax-errors').hide();
        $('#error-list').empty();

        $.ajax({
            url: '<?= BASE_URL ?>/api/category',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?= BASE_URL ?>/Category/list';
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
