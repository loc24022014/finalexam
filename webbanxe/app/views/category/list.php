<?php
$pageTitle = 'Quản Lý Phân Khúc - Admin';
$activeMenu = 'categories';
ob_start();
?>

<div class="admin-page">
    <div class="admin-header d-flex justify-between align-center">
        <div>
            <h1 class="page-title">Quản Lý <span>Phân Khúc</span></h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">Tạo mới, chỉnh sửa và xoá các phân khúc / danh mục xe của hệ thống</p>
        </div>
        <a href="<?= BASE_URL ?>/Category/add" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Thêm Phân Khúc Mới
        </a>
    </div>

    <div class="table-wrap mt-4">
        <table class="data-table">
            <thead>
                <tr>
                    <th># ID</th>
                    <th>Tên Phân Khúc</th>
                    <th>Slug</th>
                    <th>Mô Tả</th>
                    <th style="text-align: center;">Số Lượng Xe</th>
                    <th style="text-align: right;">Thao Tác</th>
                </tr>
            </thead>
            <tbody id="category-table-body">
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">Đang tải dữ liệu...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    function loadCategories() {
        $.ajax({
            url: '<?= BASE_URL ?>/api/category',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var html = '';
                    if (response.data.length > 0) {
                        response.data.forEach(function(cat) {
                            html += `<tr>
                                <td><strong>#${cat.id}</strong></td>
                                <td style="font-weight: 600; color: #fff;">${escapeHtml(cat.name)}</td>
                                <td><code class="slug-code" style="background: rgba(255,255,255,0.05); padding: 0.2rem 0.5rem; border-radius: 4px; font-family: monospace; color: var(--gold);">${escapeHtml(cat.slug)}</code></td>
                                <td><span style="color: var(--text-muted); font-size: 0.9rem;" title="${escapeHtml(cat.description || '')}">${escapeHtml(cat.description || 'Không có mô tả')}</span></td>
                                <td style="text-align: center;"><span class="badge badge-cat">${cat.product_count || 0} mẫu xe</span></td>
                                <td>
                                    <div class="actions-cell" style="justify-content: flex-end;">
                                        <a href="<?= BASE_URL ?>/Category/edit/${cat.id}" class="btn-icon" title="Chỉnh sửa">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>
                                        <button class="btn-icon danger btn-delete" data-id="${cat.id}" title="Xoá phân khúc" style="background:none; border:none; cursor:pointer;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">Không có danh mục nào tồn tại.</td></tr>';
                    }
                    $('#category-table-body').html(html);
                }
            },
            error: function() {
                $('#category-table-body').html('<tr><td colspan="6" style="text-align: center; padding: 3rem; color: var(--primary);">Lỗi khi tải dữ liệu.</td></tr>');
            }
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    loadCategories();

    // Delete category
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn xoá phân khúc này không?')) {
            $.ajax({
                url: '<?= BASE_URL ?>/api/category/' + id,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadCategories();
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
