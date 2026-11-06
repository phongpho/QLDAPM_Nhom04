/**
 * GymBro Admin - Unified Search Engine 2.0
 * 1. Global Spotlight Search (Ctrl + K) với Điều hướng bàn phím (ArrowUp/Down/Enter)
 * 2. Phân nhóm & Tab lọc nhanh (Đơn hàng, Sản phẩm, Khách hàng, Nhân sự, Tin tức, Voucher...)
 * 3. In-table Live Search Debounce & Tự động Highlight từ khóa trong bảng
 */

let globalSearchData = null;
let currentSearchQuery = '';
let currentSearchCategory = 'all';

document.addEventListener('DOMContentLoaded', function () {
    initGlobalSearch();
    initInTableLiveSearch();
    highlightTableKeywordsOnLoad();
});

// --- PHẦN 1: GLOBAL COMMAND SEARCH (CTRL + K) ---
function initGlobalSearch() {
    // Phím tắt Ctrl + K (hoặc Cmd + K)
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            openGlobalSearch();
        }
    });

    const input = document.getElementById('globalSearchInput');
    if (!input) return;

    let debounceTimer = null;

    input.addEventListener('input', function () {
        const query = this.value.trim();
        currentSearchQuery = query;
        clearTimeout(debounceTimer);

        if (query.length === 0) {
            globalSearchData = null;
            renderGlobalDefaultState();
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchGlobalSearchResults(query);
        }, 220);
    });

    // Phím điều hướng kết quả: ArrowDown, ArrowUp, Enter, Escape
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeGlobalSearch();
            return;
        }

        const items = document.querySelectorAll('#globalSearchResults .gb-search-item');
        if (items.length === 0) return;

        let activeIndex = -1;
        items.forEach((item, idx) => {
            if (item.classList.contains('active')) {
                activeIndex = idx;
            }
        });

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            let nextIndex = activeIndex + 1;
            if (nextIndex >= items.length) nextIndex = 0;
            updateActiveSearchItem(items, nextIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            let prevIndex = activeIndex - 1;
            if (prevIndex < 0) prevIndex = items.length - 1;
            updateActiveSearchItem(items, prevIndex);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIndex >= 0 && items[activeIndex]) {
                window.location.href = items[activeIndex].href;
            } else if (items.length > 0) {
                window.location.href = items[0].href;
            }
        }
    });

    // Xử lý Click các Tab lọc danh mục bên trong Spotlight
    const tabButtons = document.querySelectorAll('#spotlightCategoryTabs .gb-tab-btn');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            tabButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentSearchCategory = this.getAttribute('data-cat') || 'all';
            if (globalSearchData && currentSearchQuery) {
                renderGlobalResults(globalSearchData, currentSearchQuery);
            }
        });
    });
}

function updateActiveSearchItem(items, newIndex) {
    items.forEach(it => it.classList.remove('active'));
    if (items[newIndex]) {
        items[newIndex].classList.add('active');
        items[newIndex].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }
}

function openGlobalSearch() {
    const modalEl = document.getElementById('globalSearchModal');
    if (!modalEl) return;
    
    let modal = bootstrap.Modal.getInstance(modalEl);
    if (!modal) {
        modal = new bootstrap.Modal(modalEl);
    }
    modal.show();

    setTimeout(() => {
        const input = document.getElementById('globalSearchInput');
        if (input) {
            input.focus();
            input.select();
        }
    }, 180);
}

function closeGlobalSearch() {
    const modalEl = document.getElementById('globalSearchModal');
    if (!modalEl) return;
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) {
        modal.hide();
    }
}

function fetchGlobalSearchResults(query) {
    const resultsContainer = document.getElementById('globalSearchResults');
    if (!resultsContainer) return;

    resultsContainer.innerHTML = `
        <div class="text-center py-4 text-muted">
            <i class="fa fa-circle-notch fa-spin fs-4 text-primary mb-2"></i>
            <div class="small">Đang tra cứu dữ liệu GymBro...</div>
        </div>
    `;

    fetch(`../api/timkiem_toancuc.php?q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                globalSearchData = res.data;
                renderGlobalResults(res.data, query);
            } else {
                resultsContainer.innerHTML = `<div class="text-danger p-3 small text-center">${res.message}</div>`;
            }
        })
        .catch(() => {
            resultsContainer.innerHTML = `<div class="text-muted p-3 small text-center">Không thể kết nối đến máy chủ tra cứu.</div>`;
        });
}

function renderGlobalResults(data, query) {
    const container = document.getElementById('globalSearchResults');
    if (!container) return;

    let groups = [];

    if (currentSearchCategory === 'all' || currentSearchCategory === 'donhang') {
        if (data.donhang && data.donhang.length > 0) {
            groups.push({
                name: 'Đơn hàng',
                icon: 'fa-receipt',
                color: '#2563eb',
                bg: '#eff6ff',
                items: data.donhang
            });
        }
    }

    if (currentSearchCategory === 'all' || currentSearchCategory === 'sanpham') {
        if (data.sanpham && data.sanpham.length > 0) {
            groups.push({
                name: 'Hàng hóa & Sản phẩm',
                icon: 'fa-box',
                color: '#16a34a',
                bg: '#f0fdf4',
                items: data.sanpham
            });
        }
    }

    if (currentSearchCategory === 'all' || currentSearchCategory === 'khachhang') {
        if (data.khachhang && data.khachhang.length > 0) {
            groups.push({
                name: 'Khách hàng',
                icon: 'fa-users',
                color: '#7c3aed',
                bg: '#f5f3ff',
                items: data.khachhang
            });
        }
    }

    if (currentSearchCategory === 'all' || currentSearchCategory === 'nhansu') {
        if (data.nhansu && data.nhansu.length > 0) {
            groups.push({
                name: 'Nhân sự & Quản trị',
                icon: 'fa-user-shield',
                color: '#d97706',
                bg: '#fffbeb',
                items: data.nhansu
            });
        }
    }

    if (currentSearchCategory === 'all' || currentSearchCategory === 'tintuc') {
        if (data.tintuc && data.tintuc.length > 0) {
            groups.push({
                name: 'Bài viết & Tin tức',
                icon: 'fa-newspaper',
                color: '#0284c7',
                bg: '#f0f9ff',
                items: data.tintuc
            });
        }
    }

    if (currentSearchCategory === 'all' || currentSearchCategory === 'voucher') {
        if (data.voucher && data.voucher.length > 0) {
            groups.push({
                name: 'Mã giảm giá & Voucher',
                icon: 'fa-tag',
                color: '#db2777',
                bg: '#fdf2f8',
                items: data.voucher
            });
        }
    }

    if (currentSearchCategory === 'all' || currentSearchCategory === 'danhmuc') {
        if (data.danhmuc && data.danhmuc.length > 0) {
            groups.push({
                name: 'Danh mục sản phẩm',
                icon: 'fa-folder',
                color: '#ea580c',
                bg: '#fff7ed',
                items: data.danhmuc
            });
        }
    }

    if (currentSearchCategory === 'all') {
        if (data.thuonghieu && data.thuonghieu.length > 0) {
            groups.push({
                name: 'Thương hiệu đối tác',
                icon: 'fa-award',
                color: '#059669',
                bg: '#ecfdf5',
                items: data.thuonghieu
            });
        }
    }

    if (groups.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="text-muted mb-2"><i class="fa fa-magnifying-glass fs-2 opacity-50"></i></div>
                <div class="fw-semibold text-dark">Không tìm thấy kết quả nào</div>
                <div class="text-muted small mt-1">Không có dữ liệu khớp với "<strong>${escapeHtml(query)}</strong>" trong mục đã chọn.</div>
            </div>
        `;
        return;
    }

    let html = '';
    let isFirst = true;

    groups.forEach(g => {
        html += `<div class="gb-search-group-header mt-2"><i class="fa ${g.icon} me-1"></i> ${g.name} (${g.items.length})</div>`;
        g.items.forEach(item => {
            const activeClass = isFirst ? ' active' : '';
            isFirst = false;

            let thumbHtml = '';
            if (item.image) {
                const isRound = (g.name.includes('Khách') || g.name.includes('Nhân'));
                const roundStyle = isRound ? 'border-radius: 50%;' : 'border-radius: 6px;';
                thumbHtml = `<img src="${item.image}" alt="" style="width: 36px; height: 36px; object-fit: cover; ${roundStyle} border: 1px solid #e2e8f0; background: #fff;">`;
            } else {
                thumbHtml = `
                    <div class="gb-icon-box" style="width: 34px; height: 34px; font-size: 0.85rem; background-color: ${g.bg}; color: ${g.color};">
                        <i class="fa ${g.icon}"></i>
                    </div>
                `;
            }

            html += `
                <a href="${item.url}" class="gb-search-item${activeClass}">
                    <div class="d-flex align-items-center gap-2.5">
                        ${thumbHtml}
                        <div>
                            <div class="gb-search-item-title">${highlightKeyword(item.title, query)}</div>
                            <div class="gb-search-item-sub">${highlightKeyword(item.subtitle, query)}</div>
                        </div>
                    </div>
                    <span class="badge border" style="background-color: ${g.bg}; color: ${g.color}; border-color: ${g.color}33 !important;">${item.badge}</span>
                </a>
            `;
        });
    });

    container.innerHTML = html;
}

function renderGlobalDefaultState() {
    const container = document.getElementById('globalSearchResults');
    if (!container) return;

    container.innerHTML = `
        <div class="p-3 text-center text-muted">
            <div class="d-flex justify-content-center gap-2 mb-2 flex-wrap">
                <span class="badge bg-slate-100 text-slate-700 border">Mã đơn hàng (GYM...)</span>
                <span class="badge bg-slate-100 text-slate-700 border">Sản phẩm & Quy cách</span>
                <span class="badge bg-slate-100 text-slate-700 border">Khách hàng & SĐT</span>
                <span class="badge bg-slate-100 text-slate-700 border">Nhân sự</span>
                <span class="badge bg-slate-100 text-slate-700 border">Tin tức</span>
                <span class="badge bg-slate-100 text-slate-700 border">Voucher</span>
            </div>
            <div class="small">Nhập bất kỳ từ khóa nào và dùng phím <kbd class="gb-kbd">↑</kbd> <kbd class="gb-kbd">↓</kbd> <kbd class="gb-kbd">Enter</kbd> để mở nhanh kết quả.</div>
        </div>
    `;
}

// --- PHẦN 2: IN-TABLE LIVE SEARCH (DEBOUNCE + CLEAR BUTTON) ---
function initInTableLiveSearch() {
    const searchInputs = document.querySelectorAll('input[name="tukhoa"]');
    searchInputs.forEach(input => {
        // Tự động bọc nút X (Clear button) nếu chưa có
        const wrapper = input.closest('.input-group');
        if (wrapper && !wrapper.querySelector('.gb-search-clear-btn')) {
            wrapper.style.position = 'relative';
            const clearBtn = document.createElement('button');
            clearBtn.type = 'button';
            clearBtn.className = 'gb-search-clear-btn';
            clearBtn.innerHTML = '<i class="fa fa-xmark"></i>';
            clearBtn.title = 'Xóa từ khóa tìm kiếm';
            wrapper.appendChild(clearBtn);

            const toggleClearBtn = () => {
                clearBtn.style.display = input.value.trim().length > 0 ? 'block' : 'none';
            };

            toggleClearBtn();

            clearBtn.addEventListener('click', function () {
                input.value = '';
                toggleClearBtn();
                input.focus();
                if (input.form) {
                    input.form.submit();
                }
            });

            input.addEventListener('input', toggleClearBtn);
        }

        // Tự động submit sau 550ms khi người dùng dừng gõ
        let tableTimer = null;
        input.addEventListener('input', function () {
            clearTimeout(tableTimer);
            tableTimer = setTimeout(() => {
                if (input.form) {
                    input.form.submit();
                }
            }, 550);
        });
    });
}

// --- PHẦN 3: TỰ ĐỘNG HIGHLIGHT TỪ KHÓA TRONG BẢNG KHI TẢI TRANG ---
function highlightTableKeywordsOnLoad() {
    const urlParams = new URLSearchParams(window.location.search);
    const kw = urlParams.get('tukhoa');
    if (!kw || kw.trim().length === 0) return;

    const query = kw.trim();
    const rows = document.querySelectorAll('table.gb-table tbody tr');
    if (rows.length === 0) return;

    const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');

    rows.forEach(tr => {
        const textElements = tr.querySelectorAll('td div, td span, td a:not(.btn-action-icon)');
        textElements.forEach(el => {
            // Không highlight các badge hay nút thao tác
            if (el.classList.contains('btn') || el.classList.contains('badge') || el.closest('.btn-action-icon')) return;
            if (el.children.length === 0 && el.textContent.toLowerCase().includes(query.toLowerCase())) {
                el.innerHTML = el.textContent.replace(regex, '<mark class="gb-search-highlight">$1</mark>');
            }
        });
    });
}

// Helper: Escape HTML & Highlight Keyword
function escapeHtml(text) {
    if (!text) return '';
    return text.toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function highlightKeyword(text, keyword) {
    if (!text || !keyword) return escapeHtml(text);
    const escapedText = escapeHtml(text);
    const escapedKw = escapeHtml(keyword).trim();
    if (!escapedKw) return escapedText;

    const regex = new RegExp(`(${escapedKw.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return escapedText.replace(regex, '<mark class="gb-search-highlight">$1</mark>');
}
