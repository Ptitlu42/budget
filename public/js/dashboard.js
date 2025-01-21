document.addEventListener('DOMContentLoaded', () => {
    const incomeChart = document.getElementById('incomeChart');
    const expensesChart = document.getElementById('expensesChart');
    if (!incomeChart || !expensesChart) return;

    const users = JSON.parse(incomeChart.dataset.users);
    const expenses = JSON.parse(expensesChart.dataset.expenses);

    initIncomeChart(users);
    initExpensesChart(expenses);

    document.querySelectorAll('.progress-bar').forEach(bar => {
        bar.style.width = `${bar.dataset.width}%`;
    });
});

function initIncomeChart(users) {
    const isMobile = window.innerWidth < 768;
    const incomeData = {
        labels: users.map(user => user.name),
        datasets: [{
            data: users.map(user => user.total_income),
            backgroundColor: [
                '#FFD700',
                '#6366F1'
            ]
        }]
    };

    new Chart(document.getElementById('incomeChart'), {
        type: 'pie',
        data: incomeData,
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

function initExpensesChart(expenses) {
    const isMobile = window.innerWidth < 768;
    const labels = {
        rent: 'Rent',
        insurance: 'Insurance',
        utilities: 'Utilities',
        groceries: 'Groceries',
        other: 'Other'
    };

    const expensesData = {
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

    new Chart(document.getElementById('expensesChart'), {
        type: 'pie',
        data: expensesData,
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
