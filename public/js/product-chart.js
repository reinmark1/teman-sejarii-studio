document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('transactionChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const labels = JSON.parse(canvas.dataset.labels || '[]');
    const quantities = JSON.parse(canvas.dataset.quantities || '[]');
    const profits = JSON.parse(canvas.dataset.profits || '[]');

    new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Unit Terjual',
                    data: quantities,
                    backgroundColor: 'rgba(200, 138, 148, 0.55)',
                    borderColor: '#C88A94',
                    borderWidth: 1,
                    borderRadius: 4,
                    yAxisID: 'y'
                },
                {
                    label: 'Keuntungan (Rp)',
                    data: profits,
                    type: 'line',
                    borderColor: '#2B211E',
                    backgroundColor: 'rgba(43, 33, 30, 0.08)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#2B211E',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    labels: {
                        color: '#2B211E',
                        font: { family: 'Plus Jakarta Sans', size: 12 }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#7A6B66', font: { size: 11 } }
                },
                y: {
                    position: 'left',
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#7A6B66', font: { size: 11 } },
                    title: { display: true, text: 'Unit', color: '#7A6B66', font: { size: 11 } }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: { color: '#7A6B66', font: { size: 11 } },
                    title: { display: true, text: 'Keuntungan (Rp)', color: '#7A6B66', font: { size: 11 } }
                }
            }
        }
    });
});