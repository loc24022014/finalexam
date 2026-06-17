<?php
$pageTitle = 'Dịch Vụ & Hỗ Trợ - Loc_Car_1273';
include 'app/views/shares/header.php';
?>

<style>
/* ── Service Page Styles ── */
.service-hero {
    position: relative;
    padding: 7rem 2rem 5rem;
    text-align: center;
    overflow: hidden;
    background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(255,59,48,0.18) 0%, transparent 70%);
}
.service-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff3b30' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}
.service-hero h1 {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 900;
    background: linear-gradient(135deg, #fff 0%, var(--primary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}
.service-hero p {
    color: var(--text-muted);
    font-size: 1.15rem;
    max-width: 600px;
    margin: 0 auto 2.5rem;
    line-height: 1.7;
}
.service-hero-badges {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}
.service-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.2rem;
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 99px;
    font-size: 0.85rem;
    color: var(--text-muted);
    backdrop-filter: blur(10px);
}
.service-hero-badge span { color: var(--primary); font-weight: 700; }

/* ── Services Grid ── */
.services-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 4rem 2rem;
}
.section-title {
    text-align: center;
    margin-bottom: 3rem;
}
.section-title h2 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 0.5rem;
}
.section-title p {
    color: var(--text-muted);
    font-size: 1rem;
}
.section-divider {
    width: 50px;
    height: 3px;
    background: var(--primary);
    margin: 1rem auto 0;
    border-radius: 2px;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 4rem;
}
.service-card {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
}
.service-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--primary);
    transform: scaleX(0);
    transition: transform 0.3s ease;
    transform-origin: left;
}
.service-card:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(255,59,48,0.15);
}
.service-card:hover::before { transform: scaleX(1); }
.service-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, rgba(255,59,48,0.2), rgba(255,59,48,0.05));
    border: 1px solid rgba(255,59,48,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1.2rem;
}
.service-card h3 {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.6rem;
}
.service-card p {
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 1.2rem;
}
.service-card-link {
    color: var(--primary);
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    transition: gap 0.2s;
}
.service-card-link:hover { gap: 0.7rem; }

/* ── Process Steps ── */
.process-section {
    background: var(--dark2);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: 4rem 2rem;
}
.process-inner {
    max-width: 1000px;
    margin: 0 auto;
}
.process-steps {
    display: flex;
    gap: 0;
    position: relative;
}
.process-steps::before {
    content: '';
    position: absolute;
    top: 28px;
    left: calc(100% / 8);
    right: calc(100% / 8);
    height: 2px;
    background: linear-gradient(90deg, var(--primary), var(--border));
    z-index: 0;
}
.process-step {
    flex: 1;
    text-align: center;
    padding: 0 1rem;
    position: relative;
    z-index: 1;
}
.step-num {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: var(--dark3);
    border: 2px solid var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--primary);
    margin: 0 auto 1rem;
    position: relative;
    box-shadow: 0 0 20px rgba(255,59,48,0.3);
}
.process-step h4 {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.4rem;
}
.process-step p {
    font-size: 0.8rem;
    color: var(--text-muted);
    line-height: 1.5;
}

/* ── FAQ ── */
.faq-section {
    max-width: 800px;
    margin: 0 auto;
    padding: 4rem 2rem;
}
.faq-item {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 1rem;
    overflow: hidden;
    transition: border-color 0.2s;
}
.faq-item.open { border-color: var(--primary); }
.faq-question {
    width: 100%;
    background: none;
    border: none;
    padding: 1.2rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    color: var(--text);
    font-size: 0.95rem;
    font-weight: 600;
    font-family: inherit;
    text-align: left;
    transition: color 0.2s;
}
.faq-item.open .faq-question { color: var(--primary); }
.faq-chevron {
    flex-shrink: 0;
    width: 20px; height: 20px;
    transition: transform 0.3s ease;
    color: var(--text-muted);
}
.faq-item.open .faq-chevron { transform: rotate(180deg); color: var(--primary); }
.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease, padding 0.3s;
    padding: 0 1.5rem;
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.7;
}
.faq-item.open .faq-answer {
    max-height: 300px;
    padding: 0 1.5rem 1.2rem;
}

/* ── Contact CTA ── */
.contact-cta {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem 5rem;
}
.contact-cta-inner {
    background: linear-gradient(135deg, rgba(255,59,48,0.15) 0%, rgba(255,59,48,0.03) 100%);
    border: 1px solid rgba(255,59,48,0.3);
    border-radius: 24px;
    padding: 3.5rem;
    display: flex;
    gap: 3rem;
    align-items: center;
    flex-wrap: wrap;
}
.cta-left { flex: 1; min-width: 260px; }
.cta-left h2 {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 0.7rem;
}
.cta-left p { color: var(--text-muted); line-height: 1.6; }
.contact-cards {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.contact-card {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.2rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    transition: all 0.2s;
    min-width: 180px;
}
.contact-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(255,59,48,0.15);
}
.contact-card-icon { font-size: 1.8rem; }
.contact-card-info strong {
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.15rem;
}
.contact-card-info span {
    font-size: 0.8rem;
    color: var(--text-muted);
}

@media (max-width: 768px) {
    .process-steps { flex-direction: column; gap: 1.5rem; }
    .process-steps::before { display: none; }
    .contact-cta-inner { flex-direction: column; }
    .services-grid { grid-template-columns: 1fr; }
}
</style>

<!-- Hero -->
<section class="service-hero">
    <h1>Dịch Vụ & Hỗ Trợ</h1>
    <p>Chúng tôi đồng hành cùng bạn trong từng bước — từ lúc chọn xe đến khi lăn bánh và hơn thế nữa.</p>
    <div class="service-hero-badges">
        <div class="service-hero-badge">🔧 <span>Bảo hành</span> 3 năm</div>
        <div class="service-hero-badge">📞 Hỗ trợ <span>24/7</span></div>
        <div class="service-hero-badge">🚗 <span>Giao xe</span> tận nơi</div>
        <div class="service-hero-badge">💳 Hỗ trợ <span>tài chính</span></div>
    </div>
</section>

<!-- Services Grid -->
<section class="services-section">
    <div class="section-title">
        <h2>Dịch Vụ Của Chúng Tôi</h2>
        <p>Trải nghiệm đẳng cấp tại mỗi điểm chạm</p>
        <div class="section-divider"></div>
    </div>

    <div class="services-grid">
        <div class="service-card">
            <div class="service-icon">🚗</div>
            <h3>Tư Vấn Chọn Xe</h3>
            <p>Chuyên gia của chúng tôi sẽ giúp bạn tìm được mẫu xe phù hợp nhất với nhu cầu và ngân sách. Tư vấn 1-1, miễn phí hoàn toàn.</p>
            <a href="<?= BASE_URL ?>/Search/index" class="service-card-link">Xem xe ngay →</a>
        </div>

        <div class="service-card">
            <div class="service-icon">🔧</div>
            <h3>Bảo Dưỡng & Sửa Chữa</h3>
            <p>Trung tâm bảo dưỡng hiện đại, kỹ thuật viên được đào tạo bài bản. Đặt lịch online, nhận xe đúng hẹn.</p>
            <a href="#contact" class="service-card-link">Đặt lịch ngay →</a>
        </div>

        <div class="service-card">
            <div class="service-icon">💳</div>
            <h3>Hỗ Trợ Tài Chính</h3>
            <p>Giải pháp vay mua xe linh hoạt, lãi suất ưu đãi từ các ngân hàng đối tác. Duyệt hồ sơ nhanh trong 24h.</p>
            <a href="#contact" class="service-card-link">Tính khoản vay →</a>
        </div>

        <div class="service-card">
            <div class="service-icon">🛡️</div>
            <h3>Bảo Hiểm Xe</h3>
            <p>Gói bảo hiểm toàn diện, bảo vệ tối đa cho xe và người lái. Đối tác với các công ty bảo hiểm hàng đầu.</p>
            <a href="#contact" class="service-card-link">Xem gói bảo hiểm →</a>
        </div>

        <div class="service-card">
            <div class="service-icon">🚚</div>
            <h3>Giao Xe Tận Nơi</h3>
            <p>Dịch vụ giao xe đến tận địa chỉ của bạn trong phạm vi 50km, miễn phí và an toàn, có GPS giám sát.</p>
            <a href="<?= BASE_URL ?>/Search/index" class="service-card-link">Mua xe ngay →</a>
        </div>

        <div class="service-card">
            <div class="service-icon">🔄</div>
            <h3>Đổi Xe / Thu Đổi</h3>
            <p>Muốn nâng cấp xe? Chúng tôi định giá xe cũ nhanh chóng và minh bạch, hỗ trợ đổi xe ngay tại showroom.</p>
            <a href="#contact" class="service-card-link">Liên hệ ngay →</a>
        </div>
    </div>
</section>

<!-- Process Steps -->
<section class="process-section">
    <div class="process-inner">
        <div class="section-title">
            <h2>Quy Trình Mua Xe</h2>
            <p>Đơn giản, nhanh chóng, minh bạch</p>
            <div class="section-divider"></div>
        </div>
        <div class="process-steps">
            <div class="process-step">
                <div class="step-num">1</div>
                <h4>Chọn Xe</h4>
                <p>Duyệt danh mục & lọc theo nhu cầu</p>
            </div>
            <div class="process-step">
                <div class="step-num">2</div>
                <h4>Tư Vấn</h4>
                <p>Gặp chuyên gia, lái thử xe</p>
            </div>
            <div class="process-step">
                <div class="step-num">3</div>
                <h4>Đặt Cọc</h4>
                <p>Xác nhận đơn & thanh toán cọc</p>
            </div>
            <div class="process-step">
                <div class="step-num">4</div>
                <h4>Hoàn Tất</h4>
                <p>Ký hợp đồng, nhận xe</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="faq-section">
    <div class="section-title">
        <h2>Câu Hỏi Thường Gặp</h2>
        <p>Giải đáp mọi thắc mắc của bạn</p>
        <div class="section-divider"></div>
    </div>

    <div class="faq-item" id="faq-1">
        <button class="faq-question" onclick="toggleFaq('faq-1')">
            Làm thế nào để đặt lịch xem xe?
            <svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="faq-answer">Bạn có thể đặt lịch xem xe trực tiếp trên website bằng cách chọn xe yêu thích, nhấn "Đặt lịch xem xe" và điền thông tin. Chúng tôi sẽ xác nhận và liên hệ lại trong vòng 2 giờ.</div>
    </div>

    <div class="faq-item" id="faq-2">
        <button class="faq-question" onclick="toggleFaq('faq-2')">
            Chính sách bảo hành xe như thế nào?
            <svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="faq-answer">Tất cả xe mua tại Loc_Car_1273 được bảo hành chính hãng 3 năm hoặc 100,000 km (tùy điều kiện nào đến trước). Bảo hành bao gồm: lỗi nhà máy, động cơ, hộp số và hệ thống điện.</div>
    </div>

    <div class="faq-item" id="faq-3">
        <button class="faq-question" onclick="toggleFaq('faq-3')">
            Tôi có thể vay mua xe không? Điều kiện là gì?
            <svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="faq-answer">Có, chúng tôi hỗ trợ vay mua xe qua 15+ ngân hàng đối tác. Điều kiện: CMND/CCCD, hộ khẩu, bằng chứng thu nhập. Lãi suất từ 7.5%/năm, vay tối đa 80% giá trị xe, thời hạn đến 84 tháng.</div>
    </div>

    <div class="faq-item" id="faq-4">
        <button class="faq-question" onclick="toggleFaq('faq-4')">
            Dịch vụ giao xe có phí không?
            <svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="faq-answer">Giao xe miễn phí trong bán kính 50km từ showroom. Ngoài bán kính này, phí giao xe sẽ được tính theo km thực tế. Chúng tôi cam kết giao xe đúng hẹn, có GPS theo dõi hành trình.</div>
    </div>

    <div class="faq-item" id="faq-5">
        <button class="faq-question" onclick="toggleFaq('faq-5')">
            Thủ tục đổi xe cũ lấy xe mới như thế nào?
            <svg class="faq-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="faq-answer">Mang xe cũ đến showroom để định giá miễn phí. Sau khi đồng ý giá, chúng tôi sẽ khấu trừ giá trị xe cũ vào giá xe mới. Thủ tục chuyển chủ được hỗ trợ hoàn toàn, xong trong 1-2 ngày.</div>
    </div>
</section>

<!-- Contact CTA -->
<section class="contact-cta" id="contact">
    <div class="contact-cta-inner">
        <div class="cta-left">
            <h2>Cần Hỗ Trợ Ngay?</h2>
            <p>Đội ngũ chuyên gia của chúng tôi luôn sẵn sàng tư vấn và hỗ trợ bạn 24/7. Liên hệ ngay hôm nay!</p>
        </div>
        <div class="contact-cards">
            <a href="tel:1800xxxx" class="contact-card">
                <div class="contact-card-icon">📞</div>
                <div class="contact-card-info">
                    <strong>Hotline</strong>
                    <span>1800-xxxx (Miễn phí)</span>
                </div>
            </a>
            <a href="mailto:support@loc-car-1273.vn" class="contact-card">
                <div class="contact-card-icon">✉️</div>
                <div class="contact-card-info">
                    <strong>Email</strong>
                    <span>support@loc-car-1273.vn</span>
                </div>
            </a>
            <a href="<?= BASE_URL ?>/Search/index" class="contact-card">
                <div class="contact-card-icon">🚗</div>
                <div class="contact-card-info">
                    <strong>Mua Xe Online</strong>
                    <span>Xem & đặt xe ngay</span>
                </div>
            </a>
        </div>
    </div>
</section>

<script>
function toggleFaq(id) {
    const item = document.getElementById(id);
    const isOpen = item.classList.contains('open');
    // Close all
    document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('open'));
    // Toggle current
    if (!isOpen) item.classList.add('open');
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
