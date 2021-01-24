<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Social Media</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/1/1.0.6/iconify.min.js"></script>
    <link rel="stylesheet" href="/css/style.css" type="text/css" media="all">
    <link rel="stylesheet" href="/css/header.css" type="text/css" media="all">
    <link rel="stylesheet" href="/css/partials.css" type="text/css" media="all">
</head>

<body>
    <?php require_once __DIR__ . '/partials/header.php' ?>
    <main class="center">
        <?php require_once __DIR__ . '/' . $view ?>
    </main>
    <?php require_once __DIR__ . '/partials/messages.php' ?>
</body>

</html>
