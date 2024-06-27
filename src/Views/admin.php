<?php
require_once __DIR__ . '/../templates/header.php'; 
require_once __DIR__ . '/../Controllers/AdminController.php';
require_once __DIR__ . '/../Models/User.php';

$adminController = new AdminController;
$userManager = new User;
if (isset($_POST['delete'])) {
    $adminController->deleteUserHandler($_POST['id']);
}
if (isset($_POST['create_user'])) {
    $userManager->register($_POST['username'], $_POST['password'], $_POST['firstname'], $_POST['surname'], $_POST['email']);
}
if (isset($_POST['edit_user'])) {
    $adminController->updateUser($_POST);
}
?>
<header class="header">
    <div class="user-info">
        Panel Administracyjny
    </div>
    <div class="logout">
        <a href="index.php?action=logout">Wyloguj</a>
    </div>
</header>
<div class="container">
    <nav>
        <ul>
            <li><a href="index.php?action=dashboard">Moje wydatki</a></li>
        </ul>
    </nav>
    <main class="main-content">
        <div id="user-list">
        <?php
        $users = $adminController->getUsersList();
        if (!empty($users)) {
            ?>
                <div class="user-header">
                    <span>Imię</span>
                    <span>Nazwisko</span>
                    <span>Login</span>
                    <span>Email</span>
                    <span>Funkcja</span>
                    <span>Akcje</span>
                </div>
            <?php
            foreach ($users as $user) {
                echo "
                    <form method='post' action=''>
                    <div class='user-row'>
                        <span>" . htmlspecialchars($user['firstname']) . "</span>
                        <span>" . htmlspecialchars($user['surname']) . "</span>
                        <span>" . htmlspecialchars($user['username']) . "</span>
                        <span>" . htmlspecialchars($user['mail_address']) . "</span>
                        <span>" . htmlspecialchars($user['function']) . "</span>
                        <span>
                            <input type='hidden' name='id' value='" . $user['user_id'] . "' />
                            <button name='delete' class='btn-small red'>Usuń</button>
                            <button type='button' class='btn-small blue edit-user-button' data-id='" . $user['user_id'] . "' data-firstname='" . htmlspecialchars($user['firstname']) . "' data-surname='" . htmlspecialchars($user['surname']) . "' data-username='" . htmlspecialchars($user['username']) . "' data-email='" . htmlspecialchars($user['mail_address']) . "'>Edytuj</button>
                        </span>
                    </div>
                    </form>";
            }
        } else {
            echo "Brak wyników.";
        }
        ?>
        <br /><br />
        <button type="button" id="new-user-button">Nowy użytkownik</button>
        </div>
        <div id="registration-form-container"></div>
    </main>
</div>
<script src="/assets/js/admin.js"></script>
<?php require '../src/templates/footer.php'; ?>
