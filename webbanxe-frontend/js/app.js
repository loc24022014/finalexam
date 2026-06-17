/**
 * js/app.js – Main Application Logic
 * Router, page controllers, CRUD handlers
 */

// ═══════════════════════════════════════════════
//  State
// ═══════════════════════════════════════════════
let _allProducts   = [];
let _allCategories = [];

// ═══════════════════════════════════════════════
//  Init
// ═══════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  syncAuthUI();
  loadHomePage();
  loadStats();
});

// ═══════════════════════════════════════════════
//  Page Router
// ═══════════════════════════════════════════════
function showPage(name) {
  // Guard: admin yêu cầu đăng nhập
  if (name === 'admin' && !Auth.isLoggedIn()) {
    toast('Vui lòng đăng nhập trước!', 'error');
    showPage('login'); return;
  }

  // Ẩn tất cả pages
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

  // Hiện page tương ứng
  const page = document.getElementById('page-' + name);
  if (page) page.classList.add('active');
  const nl = document.getElementById('nl-' + name);
  if (nl) nl.classList.add('active');

  // Lazy load data khi vào page
  if (name === 'products')   loadProducts();
  if (name === 'categories') loadCategories();
  if (name === 'admin')      { loadAdminProducts(); loadAdminCategories(); }
}

// ═══════════════════════════════════════════════
//  Auth UI sync
// ═══════════════════════════════════════════════
function syncAuthUI() {
  const user = Auth.user();
  const badge = document.getElementById('user-badge');
  const btnAuth = document.getElementById('btn-auth');
  const nlAdmin = document.getElementById('nl-admin');

  if (user) {
    badge.style.display = 'flex';
    badge.innerHTML = `👤 ${user.username} <button class="btn-logout" onclick="doLogout()">Đăng xuất</button>`;
    btnAuth.style.display = 'none';
    if (user.role === 'admin') nlAdmin.style.display = 'inline-flex';
  } else {
    badge.style.display = 'none';
    btnAuth.style.display = 'inline-block';
    btnAuth.textContent = 'Đăng nhập';
    nlAdmin.style.display = 'none';
  }
}

// ═══════════════════════════════════════════════
//  HOME PAGE
// ═══════════════════════════════════════════════
async function loadHomePage() {
  const res = await ProductApi.list();
  if (!res.ok) return;
  _allProducts = res.data.data || [];
  renderFeatured(_allProducts.slice(0, 6));
}

async function loadStats() {
  const [pr, cr] = await Promise.all([ProductApi.list(), CategoryApi.list()]);
  if (pr.ok) document.getElementById('stat-products').textContent   = pr.data.count ?? '–';
  if (cr.ok) document.getElementById('stat-categories').textContent = cr.data.count ?? '–';
}

function renderFeatured(products) {
  const grid = document.getElementById('featured-grid');
  if (!products.length) {
    grid.innerHTML = '<div class="skeleton-row">Chưa có sản phẩm nào.</div>'; return;
  }
  grid.innerHTML = products.map(p => productCardHTML(p)).join('');
}

// ═══════════════════════════════════════════════
//  PRODUCTS PAGE
// ═══════════════════════════════════════════════
async function loadProducts() {
  const grid = document.getElementById('product-grid');
  grid.innerHTML = '<div class="skeleton-row">Đang tải sản phẩm...</div>';

  const [pr, cr] = await Promise.all([ProductApi.list(), CategoryApi.list()]);

  if (!pr.ok) { grid.innerHTML = '<div class="empty-state"><div class="ei">😢</div>Không thể tải sản phẩm</div>'; return; }

  _allProducts   = pr.data.data || [];
  _allCategories = cr.ok ? (cr.data.data || []) : [];

  // Populate filter dropdown
  const sel = document.getElementById('filter-category');
  sel.innerHTML = '<option value="">Tất cả danh mục</option>' +
    _allCategories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');

  renderProducts(_allProducts);
}

function renderProducts(list) {
  const grid = document.getElementById('product-grid');
  if (!list.length) {
    grid.innerHTML = '<div class="empty-state"><div class="ei">🔍</div>Không tìm thấy sản phẩm nào</div>'; return;
  }
  grid.innerHTML = list.map(p => productCardHTML(p)).join('');
}

function productCardHTML(p) {
  const emoji = carEmoji(p.brand, p.category_name);
  return `
  <div class="product-card" onclick="showProductDetail(${p.id})">
    <div class="product-thumb">${emoji}</div>
    <div class="product-body">
      <div class="product-name">${p.name}</div>
      <div class="product-brand">${p.brand || 'Không rõ hãng'}</div>
      <div class="product-price">${fmtPrice(p.price)}</div>
      <div class="product-cat">📂 ${p.category_name || 'Chưa phân loại'}</div>
    </div>
    <div class="product-footer">
      <button class="btn-detail" onclick="showProductDetail(${p.id}); event.stopPropagation()">Chi tiết</button>
      <span style="font-size:.75rem;color:var(--muted)">ID: ${p.id}</span>
    </div>
  </div>`;
}

function filterProducts() {
  const keyword = document.getElementById('search-product').value.toLowerCase();
  const catId   = document.getElementById('filter-category').value;
  const filtered = _allProducts.filter(p => {
    const matchText = !keyword ||
      p.name.toLowerCase().includes(keyword) ||
      (p.brand || '').toLowerCase().includes(keyword);
    const matchCat = !catId || String(p.category_id) === catId;
    return matchText && matchCat;
  });
  renderProducts(filtered);
}

async function showProductDetail(id) {
  const modal = document.getElementById('modal-detail');
  document.getElementById('detail-body').innerHTML = '<div class="skeleton-row">Đang tải...</div>';
  modal.classList.add('open');

  const res = await ProductApi.detail(id);
  if (!res.ok) {
    document.getElementById('detail-body').innerHTML = '<div class="empty-state">Không tìm thấy sản phẩm</div>'; return;
  }
  const p = res.data.data;
  document.getElementById('detail-title').textContent = p.name;
  document.getElementById('detail-body').innerHTML = `
    <div class="detail-big-icon">${carEmoji(p.brand, p.category_name)}</div>
    <div class="detail-grid">
      <div class="detail-item"><label>Giá bán</label><span style="color:var(--accent2);font-size:1.2rem">${fmtPrice(p.price)}</span></div>
      <div class="detail-item"><label>Hãng xe</label><span>${p.brand || '–'}</span></div>
      <div class="detail-item"><label>Danh mục</label><span>${p.category_name || '–'}</span></div>
      <div class="detail-item"><label>Mã sản phẩm</label><span>#${p.id}</span></div>
    </div>
    <div class="detail-desc">${p.description || 'Không có mô tả.'}</div>
  `;
}

// ═══════════════════════════════════════════════
//  CATEGORIES PAGE
// ═══════════════════════════════════════════════
const CAT_ICONS = ['🚗','🏎','🚙','🛻','🚐','🚕','🚌','🛵','🏍','🚘'];

async function loadCategories() {
  const grid = document.getElementById('category-grid');
  grid.innerHTML = '<div class="skeleton-row">Đang tải danh mục...</div>';

  const res = await CategoryApi.list();
  if (!res.ok) { grid.innerHTML = '<div class="empty-state"><div class="ei">😢</div>Lỗi tải danh mục</div>'; return; }

  _allCategories = res.data.data || [];
  if (!_allCategories.length) { grid.innerHTML = '<div class="empty-state"><div class="ei">📂</div>Chưa có danh mục</div>'; return; }

  grid.innerHTML = _allCategories.map((c, i) => `
    <div class="cat-card" onclick="filterByCat(${c.id})">
      <div class="cat-icon">${CAT_ICONS[i % CAT_ICONS.length]}</div>
      <div class="cat-name">${c.name}</div>
      <div class="cat-slug">${c.slug || '–'}</div>
      <div class="cat-count">${c.product_count || 0} sản phẩm →</div>
    </div>`).join('');
}

function filterByCat(id) {
  showPage('products');
  // Sau khi load xong products, set filter
  setTimeout(() => {
    document.getElementById('filter-category').value = id;
    filterProducts();
  }, 600);
}

// ═══════════════════════════════════════════════
//  LOGIN / LOGOUT
// ═══════════════════════════════════════════════
async function doLogin(e) {
  e.preventDefault();
  const btn   = document.getElementById('btn-submit');
  const label = document.getElementById('btn-submit-label');
  const errEl = document.getElementById('login-error');
  errEl.textContent = '';
  btn.disabled = true;
  label.textContent = 'Đang đăng nhập...';

  const username = document.getElementById('inp-username').value.trim();
  const password = document.getElementById('inp-password').value;

  const res = await AuthApi.login(username, password);
  btn.disabled = false;
  label.textContent = 'Đăng nhập';

  if (res.ok && res.data.success) {
    Auth.save(res.data.token, res.data.user);
    toast(`✅ Chào mừng, ${res.data.user.username}!`, 'success');
    syncAuthUI();
    showPage(res.data.user.role === 'admin' ? 'admin' : 'home');
  } else {
    errEl.textContent = res.data.message || 'Sai username hoặc password';
    toast('❌ ' + (res.data.message || 'Đăng nhập thất bại'), 'error');
  }
}

function doLogout() {
  Auth.logout();
  syncAuthUI();
  toast('Đã đăng xuất', 'info');
  showPage('home');
}

// ═══════════════════════════════════════════════
//  ADMIN – PRODUCTS
// ═══════════════════════════════════════════════
async function loadAdminProducts() {
  const tbody = document.getElementById('tbody-product');
  tbody.innerHTML = `<tr><td colspan="6" class="skeleton-row">Đang tải...</td></tr>`;
  document.getElementById('admin-user-info').textContent =
    `👤 ${Auth.user()?.username} | ${Auth.user()?.role}`;

  const [pr, cr] = await Promise.all([ProductApi.list(), CategoryApi.list()]);
  if (!pr.ok) { tbody.innerHTML = `<tr><td colspan="6" class="skeleton-row">Lỗi tải dữ liệu</td></tr>`; return; }

  _allProducts   = pr.data.data || [];
  _allCategories = cr.ok ? (cr.data.data || []) : [];

  if (!_allProducts.length) {
    tbody.innerHTML = `<tr><td colspan="6" class="skeleton-row">Chưa có sản phẩm nào</td></tr>`; return;
  }

  tbody.innerHTML = _allProducts.map(p => `
    <tr>
      <td>${p.id}</td>
      <td class="td-name" title="${p.name}">${p.name}</td>
      <td class="price-cell">${fmtPrice(p.price)}</td>
      <td><span class="brand-tag">${p.brand || '–'}</span></td>
      <td>${p.category_name || '–'}</td>
      <td class="action-btns">
        <button class="btn-edit" onclick="editProduct(${p.id})">✏ Sửa</button>
        <button class="btn-del"  onclick="deleteProduct(${p.id},'${p.name.replace(/'/g,"\\'")}')">🗑 Xóa</button>
      </td>
    </tr>`).join('');
}

function openProductModal(product = null) {
  document.getElementById('p-id').value   = product?.id || '';
  document.getElementById('p-name').value = product?.name || '';
  document.getElementById('p-brand').value= product?.brand || '';
  document.getElementById('p-price').value= product?.price || '';
  document.getElementById('p-desc').value = product?.description || '';
  document.getElementById('modal-product-title').textContent = product ? `Sửa sản phẩm #${product.id}` : 'Thêm sản phẩm mới';
  document.getElementById('btn-save-product').textContent = product ? '💾 Cập nhật' : '💾 Thêm mới';

  // Populate category select
  const sel = document.getElementById('p-category');
  sel.innerHTML = '<option value="">-- Chọn danh mục --</option>' +
    _allCategories.map(c => `<option value="${c.id}" ${product?.category_id == c.id ? 'selected':''}>${c.name}</option>`).join('');

  document.getElementById('modal-product').classList.add('open');
}

async function editProduct(id) {
  const res = await ProductApi.detail(id);
  if (!res.ok) { toast('Không tải được sản phẩm', 'error'); return; }
  openProductModal(res.data.data);
}

async function submitProduct(e) {
  e.preventDefault();
  const btn = document.getElementById('btn-save-product');
  btn.disabled = true;

  const id   = document.getElementById('p-id').value;
  const payload = {
    name:        document.getElementById('p-name').value.trim(),
    description: document.getElementById('p-desc').value.trim(),
    price:       Number(document.getElementById('p-price').value),
    brand:       document.getElementById('p-brand').value.trim(),
    category_id: document.getElementById('p-category').value || null
  };

  const res = id
    ? await ProductApi.update(id, payload)
    : await ProductApi.create(payload);

  btn.disabled = false;

  if (res.ok && res.data.success) {
    toast(res.data.message || '✅ Thành công!', 'success');
    closeModal('modal-product');
    loadAdminProducts();
  } else {
    const msg = res.data.message || JSON.stringify(res.data.errors || 'Lỗi');
    toast('❌ ' + msg, 'error');
    if (res.status === 401) doLogout();
  }
}

async function deleteProduct(id, name) {
  if (!confirm(`Xóa sản phẩm "${name}"?\nHành động này không thể hoàn tác!`)) return;
  const res = await ProductApi.remove(id);
  if (res.ok && res.data.success) {
    toast(`🗑 Đã xóa "${name}"`, 'success');
    loadAdminProducts();
  } else {
    toast('❌ ' + (res.data.message || 'Xóa thất bại'), 'error');
    if (res.status === 401 || res.status === 403) toast('Bạn không có quyền thực hiện!', 'error');
  }
}

// ═══════════════════════════════════════════════
//  ADMIN – CATEGORIES
// ═══════════════════════════════════════════════
async function loadAdminCategories() {
  const tbody = document.getElementById('tbody-category');
  tbody.innerHTML = `<tr><td colspan="5" class="skeleton-row">Đang tải...</td></tr>`;

  const res = await CategoryApi.list();
  if (!res.ok) { tbody.innerHTML = `<tr><td colspan="5" class="skeleton-row">Lỗi tải dữ liệu</td></tr>`; return; }

  _allCategories = res.data.data || [];
  if (!_allCategories.length) {
    tbody.innerHTML = `<tr><td colspan="5" class="skeleton-row">Chưa có danh mục nào</td></tr>`; return;
  }

  tbody.innerHTML = _allCategories.map(c => `
    <tr>
      <td>${c.id}</td>
      <td class="td-name">${c.name}</td>
      <td><span class="brand-tag">${c.slug || '–'}</span></td>
      <td style="text-align:center">${c.product_count || 0}</td>
      <td class="action-btns">
        <button class="btn-edit" onclick="editCategory(${c.id})">✏ Sửa</button>
        <button class="btn-del"  onclick="deleteCategory(${c.id},'${c.name.replace(/'/g,"\\'")}')">🗑 Xóa</button>
      </td>
    </tr>`).join('');
}

function openCategoryModal(cat = null) {
  document.getElementById('c-id').value   = cat?.id || '';
  document.getElementById('c-name').value = cat?.name || '';
  document.getElementById('c-slug').value = cat?.slug || '';
  document.getElementById('c-desc').value = cat?.description || '';
  document.getElementById('modal-category-title').textContent = cat ? `Sửa danh mục #${cat.id}` : 'Thêm danh mục mới';
  document.getElementById('btn-save-category').textContent = cat ? '💾 Cập nhật' : '💾 Thêm mới';
  document.getElementById('modal-category').classList.add('open');
}

async function editCategory(id) {
  const res = await CategoryApi.detail(id);
  if (!res.ok) { toast('Không tải được danh mục', 'error'); return; }
  openCategoryModal(res.data.data);
}

async function submitCategory(e) {
  e.preventDefault();
  const btn = document.getElementById('btn-save-category');
  btn.disabled = true;

  const id   = document.getElementById('c-id').value;
  const payload = {
    name:        document.getElementById('c-name').value.trim(),
    description: document.getElementById('c-desc').value.trim(),
    slug:        document.getElementById('c-slug').value.trim()
  };

  const res = id
    ? await CategoryApi.update(id, payload)
    : await CategoryApi.create(payload);

  btn.disabled = false;

  if (res.ok && res.data.success) {
    toast(res.data.message || '✅ Thành công!', 'success');
    closeModal('modal-category');
    loadAdminCategories();
  } else {
    toast('❌ ' + (res.data.message || 'Lỗi'), 'error');
  }
}

async function deleteCategory(id, name) {
  if (!confirm(`Xóa danh mục "${name}"?`)) return;
  const res = await CategoryApi.remove(id);
  if (res.ok && res.data.success) {
    toast(`🗑 Đã xóa danh mục "${name}"`, 'success');
    loadAdminCategories();
  } else {
    toast('❌ ' + (res.data.message || 'Xóa thất bại'), 'error');
  }
}

// ═══════════════════════════════════════════════
//  ADMIN TAB SWITCH
// ═══════════════════════════════════════════════
function switchAdminTab(tab) {
  document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.admin-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('atab-' + tab).classList.add('active');
  document.getElementById('admin-' + tab).classList.add('active');
}

// ═══════════════════════════════════════════════
//  MODAL UTILS
// ═══════════════════════════════════════════════
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}
