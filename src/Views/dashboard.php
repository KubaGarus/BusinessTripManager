<?php
require '../src/templates/header.php';
if (isset($_SESSION['function']) && $_SESSION['function'] === -9) {
    $adminButton = "<div class='admin'>
        <form action='admin.php' method='POST' style='display: inline;'>
            <input type='hidden' name='action' value='admin'>
            <button type='submit'>Admin</button>
        </form>
    </div>";
} else {
    $adminButton = "";
}
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// echo "<pre>";
// print_r($_FILES);
// echo "</pre>";
require_once __DIR__ . "/../Controllers/BusinessTripController.php";
$businessTripController = new BusinessTripController;
if (isset($_POST['trip-purpose']) && isset($_POST['transportation-mode'])) {
    $businessTripController->createBusinessTripHandler($_SESSION['user_id'], $_POST, $_FILES ?? []);
}

$businessTrips = $businessTripController->getBusinessTrips($_SESSION['user_id']);
?>
<div class="container">
    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="index.php?action=dashboard">Moje wydatki</a></li>
                <?= $adminButton ?>
            </ul>
        </nav>
    </aside>
    <div class="main-content">
        <header>
            <div class="user-info">
                <?php echo('Witaj, ' . htmlspecialchars($userInfo['username']) . ".");  ?>
            </div>
            <div class="logout">
                <a href="index.php?action=logout">Wyloguj</a>
            </div>
        </header>
        <main id="main-content">
            <?php          
            if (!empty($businessTrips)) {
                ?>
                    <div class="trip-header">
                        <span>User ID</span>
                        <span>Data utworzenia:</span>
                        <span>Acceptance Date</span>
                        <span>Status</span>
                    </div>
                <?php
                foreach ($businessTrips as $trip) {
                    echo "
                        <div class='trip-row'>
                            <span>" . htmlspecialchars($trip['user_id']) . "</span>
                            <span>" . htmlspecialchars($trip['intrudaction_date']) . "</span>
                            <span>" . htmlspecialchars($trip['acceptance_date'] ?? 'N/A') . "</span>
                            <span>" . htmlspecialchars($trip['status']) . "</span>
                        </div>";
                }    
            } else {
                echo "No business trips found for this user.";
            }
            ?>
            <button id="new-business-trip-button" class="new_business_trip_button">Utwórz nową delegację</button>
        </main>
        <div id="new-business-trip-form-container" style="display:none;"></div>
    </div>
</div>
<script src="/assets/js/new_business_trip.js"></script>
<?php require '../src/templates/footer.php'; ?>
