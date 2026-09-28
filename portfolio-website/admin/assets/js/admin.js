// Admin Panel JavaScript
// Save as: admin/assets/js/admin.js

// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all admin panel features
    initAdminPanel();
});

function initAdminPanel() {
    // Auto-hide alerts after 5 seconds
    autoHideAlerts();
    
    // Confirm delete actions
    confirmDeletions();
    
    // Table row search functionality
    initTableSearch();
    
    // Form validation for admin forms
    initFormValidation();
    
    // Dashboard statistics animation
    animateStats();
    
    // Modal close functionality
    initModalClose();
    
    // Bulk actions for messages
    initBulkActions();
    
    // Real-time message filtering
    initMessageFilters();
}

// Auto-hide alert messages after 5 seconds
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 500);
        }, 5000);
    });
}

// Confirm before delete actions
function confirmDeletions() {
    const deleteButtons = document.querySelectorAll('.btn-delete, .delete-message, .delete-project');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const confirmMessage = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item? This action cannot be undone.';
            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
        });
    });
}

// Table search functionality
function initTableSearch() {
    // Create search input if it doesn't exist
    const tables = document.querySelectorAll('.data-table');
    
    tables.forEach(table => {
        // Check if search box already exists for this table
        const tableWrapper = table.closest('.table-responsive');
        if (!tableWrapper) return;
        
        // Create search container if not exists
        let searchContainer = tableWrapper.querySelector('.table-search');
        if (!searchContainer) {
            searchContainer = document.createElement('div');
            searchContainer.className = 'table-search';
            searchContainer.innerHTML = `
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search in table..." class="search-input">
                    <button class="clear-search" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            tableWrapper.insertBefore(searchContainer, table);
        }
        
        const searchInput = searchContainer.querySelector('.search-input');
        const clearButton = searchContainer.querySelector('.clear-search');
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');
                let hasResults = false;
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                        hasResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Show/hide clear button
                if (clearButton) {
                    clearButton.style.display = searchTerm ? 'flex' : 'none';
                }
                
                // Show no results message
                let noResultsRow = table.querySelector('.no-results-row');
                if (!hasResults) {
                    if (!noResultsRow) {
                        noResultsRow = document.createElement('tr');
                        noResultsRow.className = 'no-results-row';
                        noResultsRow.innerHTML = '<td colspan="100" style="text-align: center;">No results found</td>';
                        table.querySelector('tbody').appendChild(noResultsRow);
                    }
                    noResultsRow.style.display = '';
                } else if (noResultsRow) {
                    noResultsRow.style.display = 'none';
                }
            });
            
            // Clear search
            if (clearButton) {
                clearButton.addEventListener('click', function() {
                    searchInput.value = '';
                    searchInput.dispatchEvent(new Event('keyup'));
                });
            }
        }
    });
}

// Form validation
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Don't add validation to login form (it has its own)
        if (form.classList.contains('login-form')) return;
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                // Remove existing error messages
                const existingError = field.parentElement.querySelector('.field-error');
                if (existingError) existingError.remove();
                
                // Check if field is empty
                if (!field.value.trim()) {
                    isValid = false;
                    showFieldError(field, 'This field is required');
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                    
                    // Email validation
                    if (field.type === 'email' && field.value) {
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailPattern.test(field.value)) {
                            isValid = false;
                            showFieldError(field, 'Please enter a valid email address');
                            field.classList.add('error');
                        }
                    }
                    
                    // URL validation
                    if (field.type === 'url' && field.value) {
                        const urlPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
                        if (!urlPattern.test(field.value)) {
                            isValid = false;
                            showFieldError(field, 'Please enter a valid URL');
                            field.classList.add('error');
                        }
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
        });
        
        // Remove error on input
        form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('error');
                const error = this.parentElement.querySelector('.field-error');
                if (error) error.remove();
            });
        });
    });
}

// Show field error message
function showFieldError(field, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    errorDiv.style.cssText = `
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    `;
    field.parentElement.appendChild(errorDiv);
    
    // Remove error after 3 seconds on input
    setTimeout(() => {
        if (errorDiv.parentElement) {
            errorDiv.style.opacity = '0';
            setTimeout(() => {
                if (errorDiv.parentElement) errorDiv.remove();
            }, 300);
        }
    }, 3000);
}

// Animate statistics numbers
function animateStats() {
    const statNumbers = document.querySelectorAll('.stat-info h3, .stat-card .stat-number');
    
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        if (isNaN(target)) return;
        
        let current = 0;
        const increment = target / 50;
        const duration = 2000;
        const stepTime = duration / 50;
        
        const updateNumber = () => {
            current += increment;
            if (current < target) {
                stat.textContent = Math.floor(current);
                setTimeout(updateNumber, stepTime);
            } else {
                stat.textContent = target;
            }
        };
        
        // Only animate if element is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateNumber();
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(stat);
    });
}

// Modal close functionality
function initModalClose() {
    const modalOverlay = document.getElementById('modalOverlay');
    const closeButtons = document.querySelectorAll('.close-modal, .btn-cancel');
    
    if (modalOverlay) {
        // Close when clicking outside
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    }
    
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal-overlay');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal-overlay[style*="display: flex"]');
            if (openModal) {
                openModal.style.display = 'none';
            }
        }
    });
}

// Bulk actions for messages
function initBulkActions() {
    const selectAllCheckbox = document.getElementById('select-all');
    const bulkActionSelect = document.getElementById('bulk-action');
    const applyBulkBtn = document.getElementById('apply-bulk');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.message-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });
    }
    
    const messageCheckboxes = document.querySelectorAll('.message-checkbox');
    messageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionButton);
    });
    
    if (applyBulkBtn) {
        applyBulkBtn.addEventListener('click', function() {
            const action = bulkActionSelect ? bulkActionSelect.value : '';
            const selectedIds = getSelectedMessageIds();
            
            if (selectedIds.length === 0) {
                showNotification('Please select at least one message', 'error');
                return;
            }
            
            if (!action) {
                showNotification('Please select an action', 'error');
                return;
            }
            
            if (confirm(`Are you sure you want to ${action} ${selectedIds.length} message(s)?`)) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `?bulk_action=${action}`;
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids';
                input.value = JSON.stringify(selectedIds);
                form.appendChild(input);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
}

// Get selected message IDs
function getSelectedMessageIds() {
    const checkboxes = document.querySelectorAll('.message-checkbox:checked');
    const ids = [];
    checkboxes.forEach(checkbox => {
        ids.push(checkbox.value);
    });
    return ids;
}

// Update bulk action button state
function updateBulkActionButton() {
    const checkboxes = document.querySelectorAll('.message-checkbox:checked');
    const applyBtn = document.getElementById('apply-bulk');
    if (applyBtn) {
        applyBtn.disabled = checkboxes.length === 0;
    }
}

// Real-time message filtering
function initMessageFilters() {
    const filterStatus = document.getElementById('filter-status');
    const filterDate = document.getElementById('filter-date');
    const applyFilters = document.getElementById('apply-filters');
    const resetFilters = document.getElementById('reset-filters');
    
    if (applyFilters) {
        applyFilters.addEventListener('click', function() {
            const status = filterStatus ? filterStatus.value : '';
            const date = filterDate ? filterDate.value : '';
            
            let url = new URL(window.location.href);
            if (status) url.searchParams.set('status', status);
            if (date) url.searchParams.set('date', date);
            
            window.location.href = url.toString();
        });
    }
    
    if (resetFilters) {
        resetFilters.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
    }
}

// Show notification
function showNotification(message, type = 'success') {
    // Remove existing notification
    const existingNotification = document.querySelector('.admin-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `admin-notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Style notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideIn 0.3s ease;
        font-size: 14px;
    `;
    
    document.body.appendChild(notification);
    
    // Add close functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.style.cssText = `
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 16px;
        padding: 5px;
    `;
    
    closeBtn.addEventListener('click', () => {
        notification.remove();
    });
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .field-error {
        animation: slideIn 0.3s ease;
    }
    
    .search-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        position: relative;
    }
    
    .search-wrapper i {
        position: absolute;
        left: 12px;
        color: #9ca3af;
    }
    
    .search-input {
        width: 300px;
        padding: 10px 35px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .clear-search {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        cursor: pointer;
        color: #9ca3af;
        display: flex;
        align-items: center;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.3s;
    }
    
    .clear-search:hover {
        background: #f3f4f6;
        color: #374151;
    }
    
    .error {
        border-color: #ef4444 !important;
    }
    
    .table-search {
        margin-bottom: 20px;
    }
    
    .bulk-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
    }
    
    .bulk-actions select {
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
    }
    
    .bulk-actions button {
        padding: 8px 16px;
        background: #6366f1;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .bulk-actions button:hover {
        background: #4f46e5;
    }
    
    .bulk-actions button:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .search-input {
            width: 100%;
        }
        
        .bulk-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .notification {
            width: 90%;
            right: 5%;
            left: 5%;
        }
    }
`;

document.head.appendChild(style);

// Export functions for use in other scripts if needed
window.AdminPanel = {
    showNotification,
    animateStats,
    initTableSearch,
    confirmDeletions
};

// Console log to confirm loaded
console.log('Admin panel JavaScript loaded successfully');