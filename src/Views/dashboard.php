<?php
namespace Views;

require_once __DIR__ . '/../templates/header.php';

if (isset($_SESSION['function']) && $_SESSION['function'] === -9) {
    $adminButton = "<li><a href='index.php?action=admin'>Admin</a></li>";
} else {
    $adminButton = "";
}

require_once __DIR__ . '/../Controllers/BusinessTripController.php';
$businessTripController = new \Controllers\BusinessTripController();
if (isset($_POST['trip-purpose']) && isset($_POST['transportation-mode'])) {
    $businessTripController->createBusinessTripHandler($_SESSION['user_id'], $_POST, $_FILES ?? []);
    if (isset($_POST['business_trip_id']) && $_POST['business_trip_id'] != "") {
        $businessTripController->deleteBusinessTripHandler($_POST['business_trip_id']);
    }
}

if (isset($_POST['delete_trip_id'])) {
    $businessTripController->deleteBusinessTripHandler($_POST['delete_trip_id']);
}

$businessTrips = $businessTripController->getBusinessTrips($_SESSION['user_id']);
?>

<div class="container">
    <header>
        <div class="user-info">
            <?php echo('Witaj, ' . htmlspecialchars($_SESSION['username']) . "."); ?>
        </div>
        <div class="logout">
            <a href="index.php?action=logout">Wyloguj</a>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="index.php?action=dashboard">Moje wydatki</a></li>
            <li><a href="index.php?action=manager">Manager</a></li>
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
                            <span>";
                    
                    $style = $trip['status'] !== 3 ? "" : "style='display:none'";
                    echo "<button type='button' class='btn-small blue edit-trip-button' data-trip='" . htmlspecialchars(json_encode($trip, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') . "' " . $style . ">Edytuj</button>";
                    echo "<button type='submit' class='btn-small red'>Usuń</button>
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
<?php require __DIR__ . '/../templates/footer.php'; ?>
