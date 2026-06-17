/**
 * js/api.js – API Service Layer
 * Kết nối tới Backend: http://localhost:8386
 */

const API_BASE = 'http://localhost:8386/api';
const TOKEN_KEY = 'wbx_token';
const USER_KEY  = 'wbx_user';

// ─── Token helpers ────────────────────────────────────────
const Auth = {
  save(token, user) {
    localStorage.setItem(TOKEN_KEY, token);
    localStorage.setItem(USER_KEY, JSON.stringify(user));
  },
  token()     { return localStorage.getItem(TOKEN_KEY) || null; },
  user()      { const u = localStorage.getItem(USER_KEY); return u ? JSON.parse(u) : null; },
  isLoggedIn(){ return !!this.token(); },
  isAdmin()   { return this.user()?.role === 'admin'; },
  logout()    { localStorage.removeItem(TOKEN_KEY); localStorage.removeItem(USER_KEY); }
};

// ─── Core fetch wrapper ───────────────────────────────────
async function apiRequest(method, path, body = null, auth = false) {
  const headers = { 'Content-Type': 'application/json' };
  if (auth && Auth.token()) headers['Authorization'] = 'Bearer ' + Auth.token();

  const opts = { method, headers };
  if (body) opts.body = JSON.stringify(body);

  const res = await fetch(API_BASE + path, opts);
  const data = await res.json().catch(() => ({}));
  return { ok: res.ok, status: res.status, data };
}

// ─── Auth API ─────────────────────────────────────────────
const AuthApi = {
  async login(username, password) {
    return apiRequest('POST', '/auth/login', { username, password });
  },
  async me() {
    return apiRequest('GET', '/auth/me', null, true);
  }
};

// ─── Product API ──────────────────────────────────────────
const ProductApi = {
  async list()          { return apiRequest('GET',    '/product'); },
  async detail(id)      { return apiRequest('GET',    `/product/${id}`); },
  async create(data)    { return apiRequest('POST',   '/product', data, true); },
  async update(id, data){ return apiRequest('PUT',    `/product/${id}`, data, true); },
  async remove(id)      { return apiRequest('DELETE', `/product/${id}`, null, true); }
};

// ─── Category API ─────────────────────────────────────────
const CategoryApi = {
  async list()          { return apiRequest('GET',    '/category'); },
  async detail(id)      { return apiRequest('GET',    `/category/${id}`); },
  async create(data)    { return apiRequest('POST',   '/category', data, true); },
  async update(id, data){ return apiRequest('PUT',    `/category/${id}`, data, true); },
  async remove(id)      { return apiRequest('DELETE', `/category/${id}`, null, true); }
};

// ─── Toast ────────────────────────────────────────────────
function toast(msg, type = 'info') {
  const el = document.createElement('div');
  el.className = `toast ${type}`;
  el.textContent = msg;
  document.getElementById('toast-wrap').appendChild(el);
  setTimeout(() => {
    el.style.animation = 'none';
    el.style.opacity = '0';
    el.style.transform = 'translateX(110%)';
    el.style.transition = 'all .3s';
    setTimeout(() => el.remove(), 300);
  }, 3000);
}

// ─── Format helpers ───────────────────────────────────────
function fmtPrice(n) {
  return Number(n).toLocaleString('vi-VN') + ' ₫';
}

function carEmoji(brand = '', cat = '') {
  const b = (brand + cat).toLowerCase();
  if (b.includes('lambo') || b.includes('ferrari') || b.includes('thể thao')) return '🏎';
  if (b.includes('toyota') || b.includes('honda') || b.includes('sedan')) return '🚗';
  if (b.includes('mercedes') || b.includes('bmw') || b.includes('audi')) return '🚙';
  if (b.includes('ford') || b.includes('suv') || b.includes('jeep')) return '🛻';
  if (b.includes('truck') || b.includes('tải')) return '🚚';
  return '🚘';
}
