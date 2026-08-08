import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    if (window.dashboardData) {
        const { monthly, pipeline } = window.dashboardData;

        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthly.labels,
                datasets: [{
                    label: 'Progress %',
                    data: monthly.data,
                    borderColor: '#1B5E8C',
                    backgroundColor: '#EAF2F8',
                    fill: true,
                    tension: 0.3,
                }],
            },
            options: { plugins: { legend: { display: false } } },
        });

        new Chart(document.getElementById('pipelineChart'), {
            type: 'bar',
            data: {
                labels: pipeline.labels,
                datasets: [{
                    label: 'Partners',
                    data: pipeline.data,
                    backgroundColor: '#E67E22',
                    borderRadius: 6,
                }],
            },
            options: { plugins: { legend: { display: false } } },
        });
    }

    if (window.financeData) {
        const { labels, income, expenses } = window.financeData;

        new Chart(document.getElementById('cashFlowChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Income', data: income, backgroundColor: '#3E8E41', borderRadius: 4 },
                    { label: 'Expenses', data: expenses, backgroundColor: '#E67E22', borderRadius: 4 },
                ],
            },
            options: { plugins: { legend: { position: 'bottom' } } },
        });
    }
});
