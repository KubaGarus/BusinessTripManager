document.addEventListener('DOMContentLoaded', function() {
    const newUserButton = document.getElementById('new-user-button');
    const formContainer = document.getElementById('registration-form-container');
    const userList = document.getElementById('user-list');

    newUserButton.addEventListener('click', function() {
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
    });
});
