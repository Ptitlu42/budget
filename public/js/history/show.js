document.addEventListener('DOMContentLoaded', () => {
    const incomesChart = document.getElementById('incomesChart');
    const expensesChart = document.getElementById('expensesChart');
    if (!incomesChart || !expensesChart) return;

    const data = {
        shares: JSON.parse(incomesChart.dataset.shares),
        total_expenses: parseFloat(incomesChart.dataset.totalExpenses),
        total_shared_expenses: parseFloat(incomesChart.dataset.totalSharedExpenses)
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
        const incomesData = {
            labels: this.data.shares.map(share => share.name),
            datasets: [{
                data: this.data.shares.map(share => share.total_income),
                backgroundColor: ['#6366F1', '#EC4899'],
                borderWidth: 0
            }]
        };

        new Chart(
            document.getElementById('incomesChart'),
            { ...this.chartConfig, data: incomesData }
        );
    }

    createExpensesChart() {
        const expensesData = {
            labels: ['Dépenses communes', 'Dépenses individuelles'],
            datasets: [{
                data: [
                    this.data.total_shared_expenses,
                    this.data.total_expenses - this.data.total_shared_expenses
                ],
                backgroundColor: ['#6366F1', '#EC4899'],
                borderWidth: 0
            }]
        };

        new Chart(
            document.getElementById('expensesChart'),
            { ...this.chartConfig, data: expensesData }
        );
    }
}
