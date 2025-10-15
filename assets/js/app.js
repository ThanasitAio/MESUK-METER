// public/assets/js/app.js
class UniversalApp {
    constructor() {
        this.baseUrl = window.location.origin;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        this.setupAjax();
        this.setupEventListeners();
        this.loadInitialData();
    }
    setupAjax() {
        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.csrfToken
            },
            error: (xhr, status, error) => {
                this.handleAjaxError(xhr, status, error);
            }
        });
    }

    async apiCall(url, method = 'GET', data = null) {
        try {
            const config = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            };

            if (data && method !== 'GET') {
                config.body = JSON.stringify(data);
            }

            if (method === 'GET' && data) {
                const params = new URLSearchParams(data);
                url += '?' + params.toString();
            }

            const response = await fetch(this.baseUrl + url, config);
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Request failed');
            }

            return result;

        } catch (error) {
            this.showNotification(error.message, 'error');
            throw error;
        }
    }

    async loadTableData(tableId, url, params = {}) {
        try {
            this.showLoading(tableId);
            
            const response = await this.apiCall(url, 'GET', params);
            
            if (response.success) {
                this.renderTable(tableId, response.data);
                this.renderPagination(tableId, response.data.pagination);
            } else {
                this.showNotification('Failed to load data', 'error');
            }
        } catch (error) {
            console.error('Load table error:', error);
        } finally {
            this.hideLoading(tableId);
        }
    }

    renderTable(tableId, data) {
        const table = document.getElementById(tableId);
        if (!table) return;

        // Implementation depends on your table structure
        console.log('Rendering table with data:', data);
    }

    renderPagination(tableId, pagination) {
        const paginationContainer = document.querySelector(`#${tableId}-pagination`);
        if (!paginationContainer || !pagination) return;

        let html = '';
        
        if (pagination.current_page > 1) {
            html += `<button class="page-btn" onclick="app.loadTableData('${tableId}', '/api/users', {page: ${pagination.current_page - 1}})">Previous</button>`;
        }

        for (let i = 1; i <= pagination.total_pages; i++) {
            const active = i === pagination.current_page ? 'active' : '';
            html += `<button class="page-btn ${active}" onclick="app.loadTableData('${tableId}', '/api/users', {page: ${i}})">${i}</button>`;
        }

        if (pagination.current_page < pagination.total_pages) {
            html += `<button class="page-btn" onclick="app.loadTableData('${tableId}', '/api/users', {page: ${pagination.current_page + 1}})">Next</button>`;
        }

        paginationContainer.innerHTML = html;
    }

    async submitForm(formId, url, method = 'POST') {
        const form = document.getElementById(formId);
        if (!form) return;

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            this.showLoading(formId);
            
            const response = await this.apiCall(url, method, data);
            
            if (response.success) {
                this.showNotification(response.message, 'success');
                form.reset();
                
                // Trigger custom event
                document.dispatchEvent(new CustomEvent('formSubmitted', {
                    detail: { formId, data: response.data }
                }));
            }
        } catch (error) {
            console.error('Form submission error:', error);
        } finally {
            this.hideLoading(formId);
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <span class="message">${this.escapeHtml(message)}</span>
            <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    showLoading(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.classList.add('loading');
        }
    }

    hideLoading(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.classList.remove('loading');
        }
    }

    handleAjaxError(xhr, status, error) {
        let message = 'An error occurred';

        if (xhr.status === 422) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                message = Object.values(errors).flat().join(', ');
            }
        } else if (xhr.status === 401) {
            message = 'Please login again';
            window.location.href = '/login';
        } else if (xhr.status === 403) {
            message = 'Access denied';
        } else if (xhr.status === 404) {
            message = 'Resource not found';
        } else if (xhr.status === 500) {
            message = 'Server error';
        }

        this.showNotification(message, 'error');
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    setupEventListeners() {
        // Global form handler
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.classList.contains('ajax-form')) {
                e.preventDefault();
                this.submitForm(form.id, form.action, form.method);
            }
        });

        // Global click handler for ajax links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('.ajax-link');
            if (link) {
                e.preventDefault();
                this.loadContent(link.href);
            }
        });
    }

    async loadContent(url) {
        try {
            const response = await this.apiCall(url);
            if (response.success) {
                document.getElementById('content-area').innerHTML = response.data.html;
            }
        } catch (error) {
            console.error('Content load error:', error);
        }
    }

    loadInitialData() {
        // Load initial data on page load
        if (document.getElementById('users-table')) {
            this.loadTableData('users-table', '/api/users');
        }
    }
}

// Initialize app
const app = new UniversalApp();




