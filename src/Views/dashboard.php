<?php
require '../src/templates/header.php';
if (isset($_SESSION['function']) && $_SESSION['function'] === -9) {
    $adminButton = "<li><a href='admin.php'>Admin</a></li>";
} else {
    $adminButton = "";
}

require_once __DIR__ . "/../Controllers/BusinessTripController.php";
$businessTripController = new BusinessTripController;
if (isset($_POST['trip-purpose']) && isset($_POST['transportation-mode'])) {
    $businessTripController->createBusinessTripHandler($_SESSION['user_id'], $_POST, $_FILES ?? []);
}

if (isset($_POST['delete_trip_id'])) {
    $businessTripController->deleteBusinessTripHandler($_POST['delete_trip_id']);
}

$businessTrips = $businessTripController->getBusinessTrips($_SESSION['user_id']);
?>
<div class="container">
    <header>
        <div class="user-info">
            <?php echo('Witaj, ' . htmlspecialchars($userInfo['username']) . ".");  ?>
        </div>
        <div class="logout">
            <a href="index.php?action=logout">Wyloguj</a>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="index.php?action=dashboard">Moje wydatki</a></li>
            <li><a href="manager.php">Manager</a></li>
            <?= $adminButton ?>
        </ul>
    </nav>
    <div class="main-content">
        <main id="main-content">
            <?php          
            if (!empty($businessTrips)) {
                ?>
                    <div class="trip-header">
                        <span>User ID</span>
                        <span>Data utworzenia:</span>
                        <span>Acceptance Date</span>
                        <span>Status</span>
                        <span>Akcje</span>
                    </div>
                <?php
                foreach ($businessTrips as $trip) {
                    $statusArr = [
                        1 => "Wersja robocza",
                        2 => "Wysłana do akceptacji",
                        3 => "Zaakceptowana",
                    ];
                    echo "
                        <form method='POST' action='' class='trip-row'>
                            <input type='hidden' name='delete_trip_id' value='" . htmlspecialchars($trip['business_trip_id']) . "' />
                            <input type='hidden' name='action' value='dashboard'/>
                            <span>" . htmlspecialchars($trip['user_id']) . "</span>
                            <span>" . htmlspecialchars($trip['intrudaction_date']) . "</span>
                            <span>" . htmlspecialchars($trip['acceptance_date'] ?? 'N/A') . "</span>
                            <span>" . $statusArr[$trip['status']] . "</span>
                            <span>
                                <button type='button' class='btn-small blue edit-trip-button'>Edytuj</button>
                                <button type='submit' class='btn-small red'>Usuń</button>
                            </span>
                        </form>";
                }    
            } else {
                echo "Nie znaleziono delegacji tego użytkownika.";
            }
            ?>
            <br/><br/>
            <button id="new-business-trip-button" class="new_business_trip_button">Utwórz nową delegację</button>
        </main>
        <div id="new-business-trip-form-container" style="display:none;"></div>
    </div>
</div>
<script src="/assets/js/new_business_trip.js"></script>
<?php require '../src/templates/footer.php'; ?>
