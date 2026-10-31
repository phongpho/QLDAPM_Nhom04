/**
 * GymBro Admin - Unified Search Engine
 * 1. Global Spotlight Search (Ctrl + K)
 * 2. In-table Live Search Debounce & Highlight
 */

document.addEventListener('DOMContentLoaded', function () {
    initGlobalSearch();
    initInTableLiveSearch();
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
        clearTimeout(debounceTimer);

        if (query.length === 0) {
            renderGlobalDefaultState();
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchGlobalSearchResults(query);
        }, 250);
    });

    // Bấm phím Escape đóng modal
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeGlobalSearch();
        }
    });
}

function openGlobalSearch() {
    const modalEl = document.getElementById('globalSearchModal');
    if (!modalEl) return;
    
    // Sử dụng Bootstrap Modal instance
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
    }, 200);
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
                renderGlobalResults(res.data, query, res.total);
            } else {
                resultsContainer.innerHTML = `<div class="text-danger p-3 small text-center">${res.message}</div>`;
            }
        })
        .catch(err => {
            resultsContainer.innerHTML = `<div class="text-muted p-3 small text-center">Không thể kết nối đến máy chủ tra cứu.</div>`;
        });
}

function renderGlobalResults(data, query, total) {
    const container = document.getElementById('globalSearchResults');
    if (!container) return;

    if (total === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="text-muted mb-2"><i class="fa fa-magnifying-glass fs-2 opacity-50"></i></div>
                <div class="fw-semibold text-dark">Không tìm thấy kết quả nào</div>
                <div class="text-muted small mt-1">Không có đơn hàng, sản phẩm hoặc khách hàng nào khớp với "<strong>${escapeHtml(query)}</strong>"</div>
            </div>
        `;
        return;
    }

    let html = '';

    // Nhóm 1: Đơn hàng
    if (data.donhang && data.donhang.length > 0) {
        html += `<div class="gb-search-group-header"><i class="fa fa-receipt me-1"></i> Đơn hàng (${data.donhang.length})</div>`;
        data.donhang.forEach(item => {
            html += `
                <a href="${item.url}" class="gb-search-item">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="gb-icon-box" style="width: 34px; height: 34px; font-size: 0.85rem; background-color: #eff6ff; color: #2563eb;">
                            <i class="fa fa-receipt"></i>
                        </div>
                        <div>
                            <div class="gb-search-item-title">${highlightKeyword(item.title, query)}</div>
                            <div class="gb-search-item-sub">${highlightKeyword(item.subtitle, query)}</div>
                        </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">${item.badge}</span>
                </a>
            `;
        });
    }

    // Nhóm 2: Sản phẩm
    if (data.sanpham && data.sanpham.length > 0) {
        html += `<div class="gb-search-group-header mt-2"><i class="fa fa-box me-1"></i> Hàng hóa & Sản phẩm (${data.sanpham.length})</div>`;
        data.sanpham.forEach(item => {
            html += `
                <a href="${item.url}" class="gb-search-item">
                    <div class="d-flex align-items-center gap-2.5">
                        <img src="${item.image}" alt="" style="width: 36px; height: 36px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                        <div>
                            <div class="gb-search-item-title">${highlightKeyword(item.title, query)}</div>
                            <div class="gb-search-item-sub">${highlightKeyword(item.subtitle, query)}</div>
                        </div>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">${item.badge}</span>
                </a>
            `;
        });
    }

    // Nhóm 3: Khách hàng
    if (data.khachhang && data.khachhang.length > 0) {
        html += `<div class="gb-search-group-header mt-2"><i class="fa fa-users me-1"></i> Khách hàng (${data.khachhang.length})</div>`;
        data.khachhang.forEach(item => {
            html += `
                <a href="${item.url}" class="gb-search-item">
                    <div class="d-flex align-items-center gap-2.5">
                        <img src="${item.image}" alt="" style="width: 36px; height: 36px; object-fit: cover; border-radius: 50%; border: 1px solid #e2e8f0;">
                        <div>
                            <div class="gb-search-item-title">${highlightKeyword(item.title, query)}</div>
                            <div class="gb-search-item-sub">${highlightKeyword(item.subtitle, query)}</div>
                        </div>
                    </div>
                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle">${item.badge}</span>
                </a>
            `;
        });
    }

    // Nhóm 4: Danh mục
    if (data.danhmuc && data.danhmuc.length > 0) {
        html += `<div class="gb-search-group-header mt-2"><i class="fa fa-grid-2 me-1"></i> Danh mục hàng (${data.danhmuc.length})</div>`;
        data.danhmuc.forEach(item => {
            html += `
                <a href="${item.url}" class="gb-search-item">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="gb-icon-box" style="width: 34px; height: 34px; font-size: 0.85rem; background-color: #fff7ed; color: #ea580c;">
                            <i class="fa fa-folder"></i>
                        </div>
                        <div>
                            <div class="gb-search-item-title">${highlightKeyword(item.title, query)}</div>
                            <div class="gb-search-item-sub">${item.subtitle}</div>
                        </div>
                    </div>
                    <span class="badge bg-slate-100 text-slate-700 border">${item.badge}</span>
                </a>
            `;
        });
    }

    container.innerHTML = html;
}

function renderGlobalDefaultState() {
    const container = document.getElementById('globalSearchResults');
    if (!container) return;

    container.innerHTML = `
        <div class="p-3 text-center text-muted">
            <div class="d-flex justify-content-center gap-2 mb-2">
                <span class="badge bg-slate-100 text-slate-700 border">Mã đơn hàng</span>
                <span class="badge bg-slate-100 text-slate-700 border">Tên sản phẩm</span>
                <span class="badge bg-slate-100 text-slate-700 border">Số điện thoại khách</span>
            </div>
            <div class="small">Nhập bất kỳ từ khóa nào để bắt đầu tìm kiếm tức thì trên GymBro Admin.</div>
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
                // Tự động submit form để khôi phục danh sách
                if (input.form) {
                    input.form.submit();
                }
            });

            input.addEventListener('input', toggleClearBtn);
        }

        // Tự động submit sau 450ms nếu người dùng dừng gõ
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

