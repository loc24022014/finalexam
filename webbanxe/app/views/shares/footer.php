<?php require_once 'app/helpers/SessionHelper.php'; SessionHelper::start(); ?>



<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <div class="navbar-brand" style="margin-bottom:1rem;">
                <div class="brand-logo">LC</div>
                <div class="brand-text">
                    <span class="brand-name">Loc_Car_1273</span>
                    <span class="brand-tagline">Racing with your dream</span>
                </div>
            </div>
            <p>Hệ thống mua bán xe ô tô uy tín hàng đầu Việt Nam. Chúng tôi mang đến những chiếc xe tốt nhất với giá cả hợp lý nhất.</p>
            <div class="social-links">
                <a href="#" class="social-link" title="Facebook">f</a>
                <a href="#" class="social-link" title="Instagram">in</a>
                <a href="#" class="social-link" title="YouTube">▶</a>
                <a href="#" class="social-link" title="Zalo">Z</a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Dòng xe</h4>
            <ul class="footer-links">
                <li><a href="<?= BASE_URL ?>/Search/index?brand=Toyota">Toyota</a></li>
                <li><a href="<?= BASE_URL ?>/Search/index?brand=BMW">BMW</a></li>
                <li><a href="<?= BASE_URL ?>/Search/index?brand=Mercedes-Benz">Mercedes-Benz</a></li>
                <li><a href="<?= BASE_URL ?>/Search/index?brand=Ford">Ford</a></li>
                <li><a href="<?= BASE_URL ?>/Search/index?brand=VinFast">VinFast</a></li>
                <li><a href="<?= BASE_URL ?>/Search/index?brand=Lamborghini">Lamborghini</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Danh mục</h4>
            <ul class="footer-links">
                <li><a href="<?= BASE_URL ?>/Search/index?category_id=1">Xe SUV</a></li>
                <li><a href="<?= BASE_URL ?>/Search/index?category_id=2">Xe Sedan</a></li>
                <li><a href="<?= BASE_URL ?>/Search/index?category_id=3">Xe Thương mại</a></li>
                <li><a href="<?= BASE_URL ?>/Search/index?category_id=4">Xe Điện</a></li>
                <li><a href="<?= BASE_URL ?>/Search/index?category_id=5">Xe Thể thao</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Hỗ trợ</h4>
            <ul class="footer-links">
                <li><a href="#">Liên hệ</a></li>
                <li><a href="#">Chính sách bảo hành</a></li>
                <li><a href="#">Thanh toán & trả góp</a></li>
                <li><a href="#">Đăng ký lái thử</a></li>
                <li><a href="<?= BASE_URL ?>/Auth/login">Đăng nhập</a></li>
                <li><a href="<?= BASE_URL ?>/Auth/register">Đăng ký</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <span>© 2025 <span>Loc_Car_1273</span>. All rights reserved. Made with ❤️ in Vietnam.</span>
        <span>🏎️ Racing with your dream</span>
    </div>
</footer>

<script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>