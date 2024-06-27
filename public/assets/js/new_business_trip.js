document.addEventListener('DOMContentLoaded', function() {
    const newBusinessTripButton = document.getElementById('new-business-trip-button');
    const mainContent = document.getElementById('main-content');
    const formContainer = document.getElementById('new-business-trip-form-container');

    newBusinessTripButton.addEventListener('click', function() {
        mainContent.style.display = 'none';
        formContainer.style.display = 'block';
        formContainer.innerHTML = `
            <div class="container main-content">
                <h2>Nowa Delegacja</h2>
                <form id="business-trip-form" method="POST" action="index.php?action=dashboard" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="dashboard" />
                    <div class="form-group">
                        <label for="trip-purpose">Cel podróży:</label>
                        <input type="text" id="trip-purpose" name="trip-purpose" required>
                    </div>
                    <div class="form-group">
                        <label for="transportation-mode">Środek transportu:</label>
                        <select id="transportation-mode" name="transportation-mode" required>
                            <option value="auto">Auto</option>
                            <option value="pociąg">Pociąg</option>
                            <option value="samolot">Samolot</option>
                            <option value="komunikacja miejska">Komunikacja miejska</option>
                        </select>
                    </div>
                    <div id="expenses-container">
                        <div class="expense-item">
                            <h3>Wydatek 1</h3>
                            <div class="form-group">
                                <label for="expense-date-1">Data:</label>
                                <input type="date" id="expense-date-1" name="expense-date[]" required>
                            </div>
                            <div class="form-group">
                                <label for="expense-amount-1">Kwota:</label>
                                <input type="number" id="expense-amount-1" name="expense-amount[]" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="expense-currency-1">Waluta:</label>
                                <select id="expense-currency-1" name="expense-currency[]" required>
                                    <option value="PLN">PLN</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="expense-description-1">Opis:</label>
                                <input type="text" id="expense-description-1" name="expense-description[]" required>
                            </div>
                            <div class="form-group">
                                <label for="expense-attachment-1">Załącznik (opcjonalny):</label>
                                <input type="file" id="expense-attachment-1" name="expense-attachment[]">
                            </div>
                            <button type="button" class="remove-expense-button" onclick="removeExpense(this)">Usuń wydatek</button>
                        </div>
                    </div>
                    <button type="button" id="add-expense-button" class="add-expense-button">Dodaj wydatek</button>
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
    });

    function addExpense() {
        const expensesContainer = document.getElementById('expenses-container');
        const expenseCount = expensesContainer.children.length + 1;
        const expenseItem = document.createElement('div');
        expenseItem.className = 'expense-item';
        expenseItem.innerHTML = `
            <h3>Wydatek ${expenseCount}</h3>
            <div class="form-group">
                <label for="expense-date-${expenseCount}">Data:</label>
                <input type="date" id="expense-date-${expenseCount}" name="expense-date[]" required>
            </div>
            <div class="form-group">
                <label for="expense-amount-${expenseCount}">Kwota:</label>
                <input type="number" id="expense-amount-${expenseCount}" name="expense-amount[]" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="expense-currency-${expenseCount}">Waluta:</label>
                <select id="expense-currency-${expenseCount}" name="expense-currency[]" required>
                    <option value="PLN">PLN</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
            <div class="form-group">
                <label for="expense-description-${expenseCount}">Opis:</label>
                <input type="text" id="expense-description-${expenseCount}" name="expense-description[]" required>
            </div>
            <div class="form-group">
                <label for="expense-attachment-${expenseCount}">Załącznik (opcjonalny):</label>
                <input type="file" id="expense-attachment-${expenseCount}" name="expense-attachment[]">
            </div>
            <button type="button" class="remove-expense-button" onclick="removeExpense(this)">Usuń wydatek</button>
        `;
        expensesContainer.appendChild(expenseItem);
    }

    window.removeExpense = function(button) {
        const expenseItem = button.parentElement;
        const expensesContainer = document.getElementById('expenses-container');
        if (expensesContainer.children.length > 1) {
            expensesContainer.removeChild(expenseItem);
        }
    };
});
