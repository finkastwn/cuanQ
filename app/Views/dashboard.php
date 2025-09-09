<?php $activeMenu = 'dashboard'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuanQ - Dashboard</title>
    <link href="/css/global-font.css" rel="stylesheet">
</head>
<body>
<?php include(APPPATH . 'Views/partials/sidebar.php'); ?>
    <div class="main-content">
        <div class="welcome-text">
            <h1>Welcome, <?= session()->get('name') ?>!</h1>
            <p>This is your dashboard.</p>
        </div>
    </div>
</body>
</html>