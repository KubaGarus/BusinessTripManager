<?php require '../src/templates/header.php'; ?>

<div class="login-container">
    <form id="login-form" action="index.php?action=login" method="POST">
        <h2>Logowanie</h2>
        <?php
        if ($this->response->error === true) {
            echo "<p class='" . $this->response->cssClass . "'>" . $this->response->message . "</p>";
        }
        ?>
        <div class="form-group">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Hasło:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Zaloguj się</button>
        <p>Nie masz konta? <a style="color:blue;" href="index.php?action=register">Zarejestruj się tutaj</a>.</p>
    </form>
</div>

<?php require '../src/templates/footer.php'; ?>
