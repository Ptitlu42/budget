document.addEventListener('DOMContentLoaded', () => {
    const chart = document.getElementById('evolutionChart');
    if (!chart) return;

    const chartData = {
        months: JSON.parse(chart.dataset.months),
        incomes: JSON.parse(chart.dataset.incomes),
        expenses: JSON.parse(chart.dataset.expenses),
        sharedExpenses: JSON.parse(chart.dataset.sharedExpenses),
        individualIncomes: JSON.parse(chart.dataset.individualIncomes)
    };

    new HistoryEvolutionChart(chartData);
});

class HistoryEvolutionChart {
    constructor(data) {
        this.data = data;
        this.init();
    }

    init() {
        const datasets = [
            {
                label: 'Revenus totaux',
                data: this.data.incomes.reverse(),
                borderColor: '#6366F1',
                backgroundColor: '#6366F122',
                fill: true,
                tension: 0.4
            },
            {
                label: "Revenus P'tit Lu",
                data: this.data.individualIncomes.reverse().map(inc => inc["P'tit Lu"] || 0),
                borderColor: '#EC4899',
                backgroundColor: '#EC489922',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Revenus Lemon',
                data: this.data.individualIncomes.map(inc => inc['Lemon'] || 0),
                borderColor: '#14B8A6',
                backgroundColor: '#14B8A622',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Dépenses totales',
                data: this.data.expenses.reverse(),
                borderColor: '#F43F5E',
                backgroundColor: '#F43F5E22',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Dépenses communes',
                data: this.data.sharedExpenses.reverse(),
                borderColor: '#8B5CF6',
                backgroundColor: '#8B5CF622',
                fill: true,
                tension: 0.4
            }
        ];

        new Chart(document.getElementById('evolutionChart'), {
            type: 'line',
            data: {
                labels: this.data.months.reverse(),
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#fff'
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: '#ffffff22'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: '#ffffff22'
                        }
                    }
                }
            }
        });
    }
}
