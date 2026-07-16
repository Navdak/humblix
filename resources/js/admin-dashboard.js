import Chart from 'chart.js/auto';

const data = window.HUMELIX_ADMIN_CHARTS;

if (data) {
    Chart.defaults.font.family = '"Segoe UI", Arial, sans-serif';
    Chart.defaults.color = '#64748b';
    Chart.defaults.borderColor = '#e7edf5';

    const common = {
        responsive: true,
        maintainAspectRatio: false,
        animation: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? false : { duration: 500 },
        plugins: { legend: { display: false }, tooltip: { padding: 10, cornerRadius: 8 } },
    };

    const trendCanvas = document.querySelector('[data-enquiry-trend]');
    if (trendCanvas && data.enquiryTrend.values.some(Number)) {
        new Chart(trendCanvas, {
            type: 'line',
            data: {
                labels: data.enquiryTrend.labels,
                datasets: [{
                    data: data.enquiryTrend.values,
                    borderColor: '#075ed1',
                    backgroundColor: 'rgba(7, 94, 209, .09)',
                    fill: true,
                    borderWidth: 2.5,
                    tension: .38,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                }],
            },
            options: { ...common, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { precision: 0 }, border: { display: false } } } },
        });
    }

    const enquiryTypeCanvas = document.querySelector('[data-enquiry-type-chart]');
    if (enquiryTypeCanvas && data.enquiryTypes.values.some(Number)) {
        new Chart(enquiryTypeCanvas, {
            type: 'doughnut',
            data: {
                labels: data.enquiryTypes.labels,
                datasets: [{ data: data.enquiryTypes.values, backgroundColor: ['#075ed1', '#22b8cf', '#f7b731', '#7c3aed', '#16a34a', '#94a3b8'], borderWidth: 0, hoverOffset: 4 }],
            },
            options: { ...common, cutout: '68%', plugins: { legend: { position: 'right', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 8, padding: 14, font: { size: 11 } } } } },
        });
    }

    const projectCanvas = document.querySelector('[data-project-chart]');
    if (projectCanvas && data.projects.values.some(Number)) {
        new Chart(projectCanvas, {
            type: 'bar',
            data: {
                labels: data.projects.labels,
                datasets: [{ data: data.projects.values, backgroundColor: ['#075ed1', '#22b8cf', '#16a34a', '#f7b731'], borderRadius: 7, maxBarThickness: 32 }],
            },
            options: { ...common, indexAxis: 'y', scales: { x: { beginAtZero: true, ticks: { precision: 0 }, border: { display: false } }, y: { grid: { display: false }, border: { display: false } } } },
        });
    }

    const videoEquipmentCanvas = document.querySelector('[data-video-equipment-chart]');
    if (videoEquipmentCanvas && data.catalogue.values.some(Number)) {
        new Chart(videoEquipmentCanvas, {
            type: 'bar',
            data: {
                labels: data.catalogue.labels,
                datasets: [
                    { label: 'Catalogue records', data: data.catalogue.values, backgroundColor: '#075ed1', borderRadius: 7, maxBarThickness: 28 },
                ],
            },
            options: { ...common, plugins: { ...common.plugins, legend: { display: true, labels: { boxWidth: 10, font: { size: 11 } } } }, scales: { x: { grid: { display: false }, border: { display: false } }, y: { beginAtZero: true, ticks: { precision: 0 }, border: { display: false } } } },
        });
    }

    const visitorTrendCanvas = document.querySelector('[data-visitor-trend-chart]');
    if (visitorTrendCanvas && data.visitorTrend.values.some(Number)) {
        new Chart(visitorTrendCanvas, {
            type: 'line',
            data: {
                labels: data.visitorTrend.labels,
                datasets: [{
                    label: 'Visits',
                    data: data.visitorTrend.values,
                    borderColor: '#0f766e',
                    backgroundColor: 'rgba(15, 118, 110, .1)',
                    fill: true,
                    borderWidth: 2.5,
                    tension: .36,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                }],
            },
            options: { ...common, plugins: { ...common.plugins, legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { precision: 0 }, border: { display: false } } } },
        });
    }

    const topPagesCanvas = document.querySelector('[data-top-pages-chart]');
    if (topPagesCanvas && data.topPages.values.some(Number)) {
        new Chart(topPagesCanvas, {
            type: 'bar',
            data: {
                labels: data.topPages.labels,
                datasets: [{
                    label: 'Visits',
                    data: data.topPages.values,
                    backgroundColor: '#075ed1',
                    borderRadius: 7,
                    maxBarThickness: 28,
                }],
            },
            options: { ...common, indexAxis: 'y', scales: { x: { beginAtZero: true, ticks: { precision: 0 }, border: { display: false } }, y: { grid: { display: false }, border: { display: false } } } },
        });
    }

    const reviewCanvas = document.querySelector('[data-review-chart]');
    if (reviewCanvas && data.reviews.values.some(Number)) {
        new Chart(reviewCanvas, {
            type: 'doughnut',
            data: {
                labels: data.reviews.labels,
                datasets: [{ data: data.reviews.values, backgroundColor: ['#16a34a', '#f7b731'], borderWidth: 0, hoverOffset: 4 }],
            },
            options: { ...common, cutout: '68%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 8, padding: 14, font: { size: 11 } } } } },
        });
    }
}
