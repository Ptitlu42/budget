document.addEventListener('DOMContentLoaded', () => {
    const chart = document.getElementById('evolutionChart');
    if (!chart) return;

    const chartData = {
        months: JSON.parse(chart.dataset.months).reverse(),
        incomes: JSON.parse(chart.dataset.incomes).reverse(),
        expenses: JSON.parse(chart.dataset.expenses).reverse(),
        sharedExpenses: JSON.parse(chart.dataset.sharedExpenses).reverse(),
        individualIncomes: JSON.parse(chart.dataset.individualIncomes).reverse(),
        individualBalances: JSON.parse(chart.dataset.individualBalances).reverse(),
        groupBalance: JSON.parse(chart.dataset.groupBalance).reverse()
    };

    console.log('Chart Data:', chartData);

    new HistoryEvolutionChart(chartData);
});

class HistoryEvolutionChart {
    constructor(data) {
        this.data = data;
        this.init();
    }

    createCheckbox(dataset, index) {
        const savedState = localStorage.getItem(`chart_dataset_${index}`);
        const isChecked = savedState ? savedState === 'visible' : !dataset.hidden;

        const label = document.createElement('label');
        label.className = 'flex items-center space-x-2 cursor-pointer';

        const input = document.createElement('input');
        input.type = 'checkbox';
        input.checked = isChecked;
        input.className = 'form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out';

        const span = document.createElement('span');
        span.className = 'ml-2';
        span.style.color = dataset.borderColor;
        span.textContent = dataset.label;

        label.appendChild(input);
        label.appendChild(span);

        return { label, input };
    }

    init() {
        const isMobile = window.innerWidth < 768;
        const datasets = [
            {
                label: 'Total Income',
                data: this.data.incomes,
                borderColor: '#6366F1',
                backgroundColor: '#6366F122',
                fill: true,
                tension: 0.4,
                hidden: false
            }
        ];

        if (this.data.individualIncomes.length > 0) {
            const firstMonth = this.data.individualIncomes[0];
            Object.entries(firstMonth).forEach(([userId, data], index) => {
                if (userId === 'unknown') return;

                const colors = ['#FFD700', '#14B8A6', '#8B5CF6'];
                datasets.push({
                    label: `${data.name}'s Income`,
                    data: this.data.individualIncomes.map(month => month[userId]?.amount || 0),
                    borderColor: colors[index % colors.length],
                    backgroundColor: colors[index % colors.length] + '22',
                    fill: true,
                    tension: 0.4,
                    hidden: true
                });
            });
        }

        datasets.push(
            {
                label: 'Total Expenses',
                data: this.data.expenses,
                borderColor: '#F43F5E',
                backgroundColor: '#F43F5E22',
                fill: true,
                tension: 0.4,
                hidden: false
            },
            {
                label: 'Shared Expenses',
                data: this.data.sharedExpenses,
                borderColor: '#8B5CF6',
                backgroundColor: '#8B5CF622',
                fill: true,
                tension: 0.4,
                hidden: true
            }
        );

        if (this.data.individualBalances.length > 0) {
            const firstMonth = this.data.individualBalances[0];
            Object.entries(firstMonth).forEach(([userId, data], index) => {
                if (userId === 'unknown') return;

                const colors = ['#10B981', '#F59E0B', '#3B82F6'];
                datasets.push({
                    label: `${data.name}'s Balance`,
                    data: this.data.individualBalances.map(month => month[userId]?.amount || 0),
                    borderColor: colors[index % colors.length],
                    backgroundColor: colors[index % colors.length] + '22',
                    fill: true,
                    tension: 0.4,
                    hidden: true
                });
            });
        }

        datasets.push({
            label: 'Group Balance',
            data: this.data.groupBalance,
            borderColor: '#EC4899',
            backgroundColor: '#EC489922',
            fill: true,
            tension: 0.4,
            hidden: true
        });

        const chart = new Chart(document.getElementById('evolutionChart'), {
            type: 'line',
            data: {
                labels: this.data.months,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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

        // Create checkboxes
        const controls = document.getElementById('chartControls');
        datasets.forEach((dataset, index) => {
            const { label, input } = this.createCheckbox(dataset, index);
            controls.appendChild(label);

            input.addEventListener('change', (e) => {
                const meta = chart.getDatasetMeta(index);
                meta.hidden = !e.target.checked;
                localStorage.setItem(`chart_dataset_${index}`, meta.hidden ? 'hidden' : 'visible');
                chart.update();
            });

            // Set initial visibility
            const meta = chart.getDatasetMeta(index);
            meta.hidden = !input.checked;
        });

        chart.update();
    }
}
