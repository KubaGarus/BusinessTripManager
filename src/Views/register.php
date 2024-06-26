<?php require '../src/templates/header.php'; ?>

<div class="register-container">
    <form id="register-form" action="index.php?action=register" method="POST">
        <h2>Rejestracja</h2>
        <?php 
        echo "<p class='" . $this->response->cssClass . "'>" . $this->response->message . "</p>";
        ?>
        <input type="hidden" name="action" value="register" />
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
        <p>Masz już konto? <a style="color:blue;" href="index.php?action=login">Zaloguj się tutaj</a>.</p>
    </form>
</div>

<?php require '../src/templates/footer.php'; ?>
