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
        const isMobile = window.innerWidth < 768;
        const datasets = [
            {
                label: 'Total Income',
                data: this.data.incomes.reverse(),
                borderColor: '#6366F1',
                backgroundColor: '#6366F122',
                fill: true,
                tension: 0.4
            },
            {
                label: "P'tit Lu Income",
                data: this.data.individualIncomes.reverse().map(inc => inc["P'tit Lu"] || 0),
                borderColor: '#FFD700',
                backgroundColor: '#FFD70022',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Lemon Income',
                data: this.data.individualIncomes.map(inc => inc['Lemon'] || 0),
                borderColor: '#14B8A6',
                backgroundColor: '#14B8A622',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Total Expenses',
                data: this.data.expenses.reverse(),
                borderColor: '#F43F5E',
                backgroundColor: '#F43F5E22',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Shared Expenses',
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: isMobile ? 'bottom' : 'top',
                        align: 'start',
                        labels: {
                            color: '#fff',
                            font: {
                                size: isMobile ? 10 : 12
                            },
                            padding: isMobile ? 8 : 15,
                            boxWidth: isMobile ? 30 : 40,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#fff',
                            font: {
                                size: isMobile ? 10 : 12
                            }
                        },
                        grid: {
                            color: '#ffffff22'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#fff',
                            font: {
                                size: isMobile ? 10 : 12
                            },
                            maxRotation: isMobile ? 45 : 0,
                            minRotation: isMobile ? 45 : 0
                        },
                        grid: {
                            color: '#ffffff22'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    },
                    line: {
                        borderWidth: 2
                    }
                }
            }
        });
    }
}
