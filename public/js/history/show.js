document.addEventListener('DOMContentLoaded', () => {
    const incomesChart = document.getElementById('incomesChart');
    const expensesChart = document.getElementById('expensesChart');
    if (!incomesChart || !expensesChart) return;

    const data = {
        shares: JSON.parse(incomesChart.dataset.shares || '[]'),
        total_expenses: parseFloat(expensesChart.dataset.totalExpenses || 0),
        total_shared_expenses: parseFloat(expensesChart.dataset.totalSharedExpenses || 0)
    };

    new HistoryCharts(data);
});

class HistoryCharts {
    constructor(data) {
        this.data = data;
        this.chartConfig = {
            type: 'pie',
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#fff'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                return `${context.label}: ${new Intl.NumberFormat('en-US', { style: 'currency', currency: 'EUR' }).format(value)}`;
                            }
                        }
                    }
                }
            }
        };

        this.init();
    }

    init() {
        this.createIncomesChart();
        this.createExpensesChart();
    }

    createIncomesChart() {
        if (!this.data.shares.length) return;

        const incomesData = {
            labels: this.data.shares.map(share => share.name),
            datasets: [{
                data: this.data.shares.map(share => share.total_income),
                backgroundColor: ['#6366F1', '#EC4899', '#10B981', '#F59E0B', '#EF4444'],
                borderWidth: 0
            }]
        };

        new Chart(
            document.getElementById('incomesChart'),
            { ...this.chartConfig, data: incomesData }
        );
    }

    createExpensesChart() {
        const expensesData = JSON.parse(document.getElementById('expensesChart').dataset.expenses || '{}');
        if (Object.keys(expensesData).length === 0) return;

        const labels = {
            rent: 'Rent',
            insurance: 'Insurance',
            utilities: 'Utilities',
            groceries: 'Groceries',
            other: 'Other'
        };

        const data = {
            labels: Object.keys(expensesData).map(type => labels[type] || type),
            datasets: [{
                data: Object.values(expensesData),
                backgroundColor: ['#6366F1', '#EC4899', '#10B981', '#F59E0B', '#EF4444'],
                borderWidth: 0
            }]
        };

        new Chart(
            document.getElementById('expensesChart'),
            { ...this.chartConfig, data: data }
        );
    }
}
