<?php
session_start();

// Admin Panel data
$admin_password = "850"; // set your password
if (!isset($_SESSION['logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['logged_in'] = true;
        } else {
            $error = "ভল পাসওয়ার্ড!";
        }
    }
    if (!isset($_SESSION['logged_in'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Enter Your Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
<?php
        exit();
    }
}

// JSON File Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $data = [
        "fajr" => $_POST['fajr'],
        "dhuhr" => $_POST['dhuhr'],
        "asr" => $_POST['asr'],
        "maghrib" => $_POST['maghrib'],
        "isha" => $_POST['isha'],
        "parking_status" => $_POST['parking_status'],
       "masjid_status" => $_POST['masjid_status']
    ];
    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
    $success = "Data Update successfully!";
}

// Current data load
$json = file_get_contents('data.json');
$prayerTimes = json_decode($json, true);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="admin-container">
        <h2>Prayer Timing and mosque/parking status</h2>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <form method="POST">
            <label>Fajr:</label>
            <input type="time" name="fajr" value="<?= $prayerTimes['fajr'] ?>">

            <label>Dhuhr:</label>
            <input type="time" name="dhuhr" value="<?= $prayerTimes['dhuhr'] ?>">

            <label>Asr:</label>
            <input type="time" name="asr" value="<?= $prayerTimes['asr'] ?>">

            <label>Maghrib:</label>
            <input type="time" name="maghrib" value="<?= $prayerTimes['maghrib'] ?>">

            <label>Isha:</label>
            <input type="time" name="isha" value="<?= $prayerTimes['isha'] ?>">

            <label>Parking Status:</label>
            <select name="parking_status">
                <option value="Open" <?= $prayerTimes['parking_status'] == "Open" ? "selected" : "" ?>>Open</option>
                <option value="Close" <?= $prayerTimes['parking_status'] == "Close" ? "selected" : "" ?>>Close</option>
            </select>
           
          <label>Masjid Status:</label>
            <select name="masjid_status">
                <option value="Open" <?= $prayerTimes['masjid_status'] == "Open" ? "selected" : "" ?>>Open</option>
                <option value="Close" <?= $prayerTimes['masjid_status'] == "Close" ? "selected" : "" ?>>Close</option>
            </select>

            <button type="submit" name="update">SUBMIT</button>
        </form>
    </div>
</body>
</html>
