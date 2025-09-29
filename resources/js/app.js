// index.js
import "preline/preline";
import Chart from 'chart.js/auto';
import ApexCharts from 'apexcharts';

// Make ApexCharts available globally
window.ApexCharts = ApexCharts;

// Initialize Preline UI on page load
document.addEventListener('livewire:initialized', () => {
    window.HSStaticMethods.autoInit();
});
