<?php
namespace Views;

require_once __DIR__ . '/../templates/header.php';

$managerController = new \Controllers\ManagerController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accept_trip_id'])) {
        $managerController->acceptBusinessTrip($_POST['accept_trip_id']);
    }

    if (isset($_POST['delete_trip_id'])) {
        $managerController->deleteBusinessTrip($_POST['delete_trip_id']);
    }
}

$businessTrips = $managerController->showAllBusinessTrips();
?>

<div class="container">
    <header>
        <div class="user-info">
            Panel Managera
        </div>
        <div class="logout">
            <a href="index.php?action=logout">Wyloguj</a>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="index.php?action=dashboard">Moje wydatki</a></li>
            <li><a href="index.php?action=manager">Manager</a></li>
            <li><a href="index.php?action=admin">Admin</a></li>
        </ul>
    </nav>
    <div class="main-content">
        <main>
            <?php
            if (!empty($businessTrips)) {
                ?>
                    <div class="trip-header">
                        <span>User ID</span>
                        <span>Imię i Nazwisko</span>
                        <span>Data utworzenia</span>
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
                        <div class='trip-row'>
                            <span>" . htmlspecialchars($trip['firstname']) . " " . htmlspecialchars($trip['surname']) . "</span>
                            <span>" . htmlspecialchars($trip['intrudaction_date']) . "</span>
                            <span>" . htmlspecialchars($trip['acceptance_date'] ?? 'N/A') . "</span>
                            <span>" . $statusArr[$trip['status']] . "</span>
                            <span>
                                <div class='action-buttons'>";
                                
                    if ($trip['status'] != 3) {
                        echo "
                                    <form method='POST' action=''>
                                        <input type='hidden' name='accept_trip_id' value='" . htmlspecialchars($trip['business_trip_id']) . "' />
                                        <button type='submit' name='accept' class='btn-small blue'>Akceptuj</button>
                                    </form>";
                    }
                    
                    echo "
                                    <form method='POST' action=''>
                                        <input type='hidden' name='delete_trip_id' value='" . htmlspecialchars($trip['business_trip_id']) . "' />
                                        <button type='submit' name='delete' class='btn-small red'>Usuń</button>
                                    </form>
                                </div>
                            </span>
                        </div>";
                }
            } else {
                echo "Nie znaleziono delegacji.";
            }
            ?>
        </main>
    </div>
</div>
<?php require __DIR__ . '/../templates/footer.php'; ?>
