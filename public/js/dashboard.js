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
    const revenusData = {
        labels: users.map(user => user.name),
        datasets: [{
            data: users.map(user => user.total_income),
            backgroundColor: [
                '#6366F1',
                '#FFD700'
            ]
        }]
    };

    new Chart(document.getElementById('revenusChart'), {
        type: 'pie',
        data: revenusData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    });
}

function initDepensesChart(expenses) {
    const labels = {
        rent: 'Loyer',
        insurance: 'Assurance',
        utilities: 'Charges',
        groceries: 'Courses',
        other: 'Autre'
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
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    });
}
