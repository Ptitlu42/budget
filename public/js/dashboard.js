document.addEventListener('DOMContentLoaded', () => {
    const revenusChart = document.getElementById('revenusChart');
    const depensesChart = document.getElementById('depensesChart');
    if (!revenusChart || !depensesChart) return;

    const users = JSON.parse(revenusChart.dataset.users);
    const expenses = JSON.parse(depensesChart.dataset.expenses);

    initRevenusChart(users);
    initDepensesChart(expenses);

    document.querySelectorAll('.progress-bar').forEach(bar => {
        bar.style.width = `${bar.dataset.width}%`;
    });
});

function initRevenusChart(users) {
    const isMobile = window.innerWidth < 768;
    const revenusData = {
        labels: users.map(user => user.name),
        datasets: [{
            data: users.map(user => user.total_income),
            backgroundColor: [
                '#FFD700',
                '#6366F1'
            ]
        }]
    };

    new Chart(document.getElementById('revenusChart'), {
        type: 'pie',
        data: revenusData,
        options: {
            responsive: true,
            maintainAspectRatio: !isMobile,
            plugins: {
                legend: {
                    position: isMobile ? 'bottom' : 'right',
                    labels: {
                        color: 'white',
                        font: {
                            size: isMobile ? 12 : 14
                        },
                        padding: isMobile ? 10 : 20
                    }
                }
            }
        }
    });
}

function initDepensesChart(expenses) {
    const isMobile = window.innerWidth < 768;
    const labels = {
        rent: 'Rent',
        insurance: 'Insurance',
        utilities: 'Utilities',
        groceries: 'Groceries',
        other: 'Other'
    };

    const depensesData = {
        labels: Object.values(labels),
        datasets: [{
            data: Object.values(expenses),
            backgroundColor: [
                '#6366F1',
                '#FFD700',
                '#14B8A6',
                '#8B5CF6',
                '#F43F5E'
            ]
        }]
    };

    new Chart(document.getElementById('depensesChart'), {
        type: 'pie',
        data: depensesData,
        options: {
            responsive: true,
            maintainAspectRatio: !isMobile,
            plugins: {
                legend: {
                    position: isMobile ? 'bottom' : 'right',
                    labels: {
                        color: 'white',
                        font: {
                            size: isMobile ? 12 : 14
                        },
                        padding: isMobile ? 10 : 20
                    }
                }
            }
        }
    });
}
