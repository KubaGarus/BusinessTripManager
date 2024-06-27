document.addEventListener('DOMContentLoaded', function() {
    const newBusinessTripButton = document.getElementById('new-business-trip-button');
    const mainContent = document.getElementById('main-content');
    const formContainer = document.getElementById('new-business-trip-form-container');

    newBusinessTripButton.addEventListener('click', function() {
        showForm();
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('edit-trip-button')) {
            const tripData = JSON.parse(event.target.getAttribute('data-trip'));
            showForm(tripData);
        }
    });

    function showForm(tripData = null) {
        mainContent.style.display = 'none';
        formContainer.style.display = 'block';

        const tripPurpose = tripData ? tripData.purpose : '';
        const transportationMode = tripData ? tripData.transport : '';
        const expenses = tripData && tripData.expenses ? tripData.expenses : [];
        
        let expensesHTML = '';
        if (expenses.length === 0) {
            expensesHTML = generateExpenseHTML(1);
        } else {
            expenses.forEach((expense, index) => {
                expensesHTML += generateExpenseHTML(index + 1, expense);
            });
        }

        formContainer.innerHTML = `
            <div class="container main-content">
                <h2>${tripData ? 'Edytuj Delegację' : 'Nowa Delegacja'}</h2>
                <form id="business-trip-form" method="POST" action="index.php?action=dashboard" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="dashboard" />
                    <input type="hidden" name="business_trip_id" value="${tripData ? tripData.business_trip_id : ''}" />
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="trip-purpose">Cel podróży:</label>
                            <input type="text" id="trip-purpose" name="trip-purpose" value="${tripPurpose}" required>
                        </div>
                        <div class="form-group">
                            <label for="transportation-mode">Środek transportu:</label>
                            <select id="transportation-mode" name="transportation-mode" required>
                                <option value="auto" ${transportationMode === 'auto' ? 'selected' : ''}>Auto</option>
                                <option value="pociąg" ${transportationMode === 'pociąg' ? 'selected' : ''}>Pociąg</option>
                                <option value="samolot" ${transportationMode === 'samolot' ? 'selected' : ''}>Samolot</option>
                                <option value="komunikacja miejska" ${transportationMode === 'komunikacja miejska' ? 'selected' : ''}>Komunikacja miejska</option>
                            </select>
                        </div>
                    </div>
                    <div id="expenses-container">
                        ${expensesHTML}
                    </div>
                    <button type="button" id="add-expense-button" class="add-expense-button">Dodaj wydatek</button>
                    <br/><br/><div class="form-group">
                        <label for="total-amount">Całkowity koszt:</label>
                        <input type="number" id="total-amount" name="total-amount" step="0.01" readonly>
                    </div>
                    <div class="form-actions">
                        <button type="button" id="cancel-button">Anuluj</button>
                        <button type="submit">Zapisz delegację</button>
                    </div>
                </form>
            </div>
        `;

        document.getElementById('add-expense-button').addEventListener('click', addExpense);
        document.getElementById('cancel-button').addEventListener('click', function() {
            formContainer.style.display = 'none';
            mainContent.style.display = 'block';
        });

        updateTotalAmount();
        document.querySelectorAll('[id^="expense-amount-"]').forEach(input => {
            input.addEventListener('input', updateTotalAmount);
        });
    }

    function generateExpenseHTML(expenseCount, expense = {}) {
        return `
            <div class="expense-item" id="expense-item-${expenseCount}">
                <h3>Wydatek ${expenseCount}</h3>
                <div class="form-group">
                    <label for="expense-date-${expenseCount}">Data:</label>
                    <input type="date" id="expense-date-${expenseCount}" name="expense-date[]" value="${expense.expense_date || ''}" required>
                </div>
                <div class="form-group">
                    <label for="expense-amount-${expenseCount}">Kwota:</label>
                    <input type="number" id="expense-amount-${expenseCount}" name="expense-amount[]" step="0.01" value="${expense.cost || ''}" required>
                </div>
                <div class="form-group">
                    <label for="expense-description-${expenseCount}">Opis:</label>
                    <input type="text" id="expense-description-${expenseCount}" name="expense-description[]" value="${expense.note || ''}" required>
                </div>
                <button type="button" class="remove-expense-button" onclick="removeExpense(${expenseCount})">Usuń wydatek</button>
            </div>
        `;
    }

    function addExpense() {
        const expensesContainer = document.getElementById('expenses-container');
        const expenseCount = expensesContainer.children.length + 1;
        const expenseItem = document.createElement('div');
        expenseItem.className = 'expense-item';
        expenseItem.id = `expense-item-${expenseCount}`;
        expenseItem.innerHTML = generateExpenseHTML(expenseCount);
        expensesContainer.appendChild(expenseItem);

        document.getElementById(`expense-amount-${expenseCount}`).addEventListener('input', updateTotalAmount);
    }

    window.removeExpense = function(expenseCount) {
        const expenseItem = document.getElementById(`expense-item-${expenseCount}`);
        const expensesContainer = document.getElementById('expenses-container');
        if (expensesContainer.children.length > 1) {
            expensesContainer.removeChild(expenseItem);
            updateTotalAmount();
        }
    };

    function updateTotalAmount() {
        const expenseAmounts = document.querySelectorAll('[id^="expense-amount-"]');
        let totalAmount = 0;
        expenseAmounts.forEach(input => {
            totalAmount += parseFloat(input.value) || 0;
        });
        document.getElementById('total-amount').value = totalAmount.toFixed(2);
    }
});
