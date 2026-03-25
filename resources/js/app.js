import './bootstrap';
import Chart from 'chart.js/auto';

// Make Chart available globally for inline scripts
window.Chart = Chart;

// Sidebar collapse functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if sidebar exists
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    
    const toggleBtn = document.getElementById('sidebar-toggle');
    const mainContent = document.getElementById('main-content');
    
    if (!toggleBtn) return;
    
    // Check saved state
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed) {
        sidebar.classList.add('collapsed');
        mainContent?.classList.add('sidebar-collapsed');
    }
    
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        mainContent?.classList.toggle('sidebar-collapsed');
        
        // Save state
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    });
});

