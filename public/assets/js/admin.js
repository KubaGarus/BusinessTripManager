document.addEventListener('DOMContentLoaded', function() {
    const newUserButton = document.getElementById('new-user-button');
    const formContainer = document.getElementById('registration-form-container');
    const userList = document.getElementById('user-list');

    newUserButton.addEventListener('click', function() {
        showRegistrationForm();
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('edit-user-button')) {
            const userId = event.target.getAttribute('data-id');
            const firstname = event.target.getAttribute('data-firstname');
            const surname = event.target.getAttribute('data-surname');
            const username = event.target.getAttribute('data-username');
            const email = event.target.getAttribute('data-email');

            showEditForm(userId, firstname, surname, username, email);
        }
    });

    function showRegistrationForm() {
        formContainer.innerHTML = `
            <div class="container main-content">
                <h2>Rejestracja</h2>
                <form action="" method="POST">
                    <input type='hidden' name='create_user' value='1' />
                    <div class="form-group">
                        <label for="firstname">Imię:</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="surname">Nazwisko:</label>
                        <input type="text" id="surname" name="surname" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Nazwa użytkownika:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Potwierdź hasło:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit">Zarejestruj się</button>
                    <button type="button" id="cancel-registration-button">Anuluj</button>
                </form>
            </div>
        `;

        const cancelButton = document.getElementById('cancel-registration-button');
        cancelButton.addEventListener('click', function() {
            formContainer.innerHTML = '';
            userList.style.display = 'block';
        });

        userList.style.display = 'none';
    }

    function showEditForm(userId, firstname, surname, username, email) {
        formContainer.innerHTML = `
            <div class="container main-content">
                <h2>Edytuj użytkownika</h2>
                <form id="edit-form" action="" method="POST">
                    <input type='hidden' name='edit_user' value='1' />
                    <input type='hidden' name='user_id' value='${userId}' />
                    <div class="form-group">
                        <label for="edit_firstname">Imię:</label>
                        <input type="text" id="edit_firstname" name="firstname" value="${firstname}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_surname">Nazwisko:</label>
                        <input type="text" id="edit_surname" name="surname" value="${surname}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email:</label>
                        <input type="email" id="edit_email" name="email" value="${email}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_username">Nazwa użytkownika:</label>
                        <input type="text" id="edit_username" name="username" value="${username}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_function">Funkcja:</label>
                        <select id="edit_function" name='function'>
                            <option selected='selected' value='1'>Pracownik</option>
                            <option value='3'>Manager</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_password">Hasło:</label>
                        <input type="password" id="edit_password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="edit_confirm_password">Potwierdź hasło:</label>
                        <input type="password" id="edit_confirm_password" name="confirm_password">
                    </div>
                    <button type="submit">Zapisz zmiany</button>
                    <button type="button" id="cancel-edit-button">Anuluj</button>
                </form>
            </div>
        `;

        const cancelButton = document.getElementById('cancel-edit-button');
        cancelButton.addEventListener('click', function() {
            formContainer.innerHTML = '';
            userList.style.display = 'block';
        });

        userList.style.display = 'none';

        const editForm = document.getElementById('edit-form');
        editForm.addEventListener('submit', function(event) {
            const password = document.getElementById('edit_password').value;
            const confirmPassword = document.getElementById('edit_confirm_password').value;

            if ((password && !confirmPassword) || (!password && confirmPassword)) {
                event.preventDefault();
                alert('Jeśli pole hasła lub potwierdzenia hasła jest wypełnione, oba pola muszą być wypełnione.');
            }
        });
    }
});
