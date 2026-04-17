<!DOCTYPE html>
<html lang="de">
<head>
    <base href="<?= BASE_URL ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/trongate.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/solar-style.css">
    <?= $additional_includes_top ?? '' ?>
    <title>BKW Manager - Dein Balkonkraftwerk im Blick</title>
</head>
<body>
    <main>
        <?= display($data) ?>
    </main>
    <footer>
        <div class="container text-center" style="padding: 40px 0; border-top: 1px solid var(--border-color); margin-top: 60px;">
            <p>&copy; <?= date('Y') ?> BKW-App - Powered by Trongate</p>
        </div>
    </footer>
<?= $additional_includes_btm ?? '' ?>
</body>
</html>