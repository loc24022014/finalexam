<?php
$pageTitle = 'Quản Lý Xe - Admin';
$activeMenu = 'products';
ob_start();
?>

<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1 class="page-title">Quản Lý <span>Xe</span></h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">Xem và quản lý tất cả các dòng xe hiện có trong hệ thống</p>
        </div>
        <a href="<?= BASE_URL ?>/Product/add" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Thêm Xe Mới
        </a>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tên Xe</th>
                    <th>Thương Hiệu</th>
                    <th>Phân Khúc</th>
                    <th>Giá Bán</th>
                    <th style="text-align: right;">Thao Tác</th>
                </tr>
            </thead>
            <tbody id="product-table-body">
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">Đang tải dữ liệu...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    function loadProducts() {
        $.ajax({
            url: '<?= BASE_URL ?>/api/product',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var html = '';
                    if (response.data.length > 0) {
                        response.data.forEach(function(product) {
                            html += `<tr>
                                <td style="font-weight: 600; color: #fff;">${escapeHtml(product.name)}</td>
                                <td>${escapeHtml(product.brand || 'N/A')}</td>
                                <td><span class="badge badge-cat">${escapeHtml(product.category_name || 'N/A')}</span></td>
                                <td class="price-text" style="color: var(--gold); font-weight: 600;">${formatPrice(product.price)} ₫</td>
                                <td>
                                    <div class="actions-cell" style="justify-content: flex-end;">
                                        <a href="<?= BASE_URL ?>/Product/show/${product.id}" class="btn-icon" title="Xem chi tiết">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                        <a href="<?= BASE_URL ?>/Product/edit/${product.id}" class="btn-icon" title="Chỉnh sửa">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>
                                        <button class="btn-icon danger btn-delete" data-id="${product.id}" title="Xóa" style="background:none; border:none; cursor:pointer;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">Không có dữ liệu sản phẩm.</td></tr>';
                    }
                    $('#product-table-body').html(html);
                }
            },
            error: function() {
                $('#product-table-body').html('<tr><td colspan="5" style="text-align: center; padding: 3rem; color: var(--primary);">Lỗi khi tải dữ liệu sản phẩm.</td></tr>');
            }
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function formatPrice(val) {
        if (!val) return '0';
        return Number(val).toLocaleString('vi-VN');
    }

    loadProducts();

    // Delete product via AJAX
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn xoá mẫu xe này không?')) {
            $.ajax({
                url: '<?= BASE_URL ?>/api/product/' + id,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadProducts();
                    } else {
                        alert(response.message || 'Có lỗi xảy ra!');
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    alert(res && res.message ? res.message : 'Lỗi khi xoá!');
                }
            });
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include 'app/views/admin/layout.php';
?>