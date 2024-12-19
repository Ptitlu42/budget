document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('historyForm');
    if (!form) return;

    new HistoryForm();
});

class HistoryForm {
    constructor() {
        this.monthYearInput = document.getElementById('month_year');
        this.incomeCount = 1;
        this.expenseCount = 1;

        this.init();
    }

    init() {
        const today = new Date();
        this.monthYearInput.value = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}`;
        this.updateDates();
        this.monthYearInput.addEventListener('change', () => this.updateDates());

        document.getElementById('add-income').addEventListener('click', () => this.addIncomeEntry());
        document.getElementById('add-expense').addEventListener('click', () => this.addExpenseEntry());
    }

    updateDates() {
        const selectedDate = new Date(this.monthYearInput.value + '-01');
        document.querySelectorAll('input[type="date"]').forEach(input => {
            input.value = selectedDate.toISOString().split('T')[0];
        });
    }

    addIncomeEntry() {
        const container = document.getElementById('incomes-container');
        const template = document.querySelector('.income-entry').cloneNode(true);
        this.updateFields(template, this.incomeCount, 'incomes');
        container.appendChild(template);
        this.incomeCount++;
    }

    addExpenseEntry() {
        const container = document.getElementById('expenses-container');
        const template = document.querySelector('.expense-entry').cloneNode(true);
        this.updateFields(template, this.expenseCount, 'expenses');
        container.appendChild(template);
        this.expenseCount++;
    }

    updateFields(template, count, type) {
        const selectedDate = new Date(this.monthYearInput.value + '-01');
        template.querySelectorAll('input, select').forEach(input => {
            input.name = input.name.replace('[0]', `[${count}]`);
            if (input.type === 'date') {
                input.value = selectedDate.toISOString().split('T')[0];
            } else if (input.type === 'checkbox') {
                input.checked = false;
            } else {
                input.value = '';
            }
        });
    }
}
