<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Reset Hasła OSP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card bg-light text-dark shadow-lg p-4" style="width: 100%; max-width: 400px;">
    <h4 class="text-center mb-4">Wyszukaj swoje konto</h4>
    
    <?php if (isset($komunikat)): ?>
        <div class="alert alert-success"><?= $komunikat ?></div>
    <?php endif; ?>
    <?php if (isset($blad)): ?>
        <div class="alert alert-danger"><?= $blad ?></div>
    <?php endif; ?>

    <form action="index.php?action=forgot_password" method="POST">
        <div class="mb-4">
            <label class="form-label">Podaj swój login</label>
            <input type="text" name="login" class="form-control" required>
            <div class="form-text">Jeśli login będzie poprawny, wyślemy link na Twój e-mail.</div>
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-3">Zresetuj hasło</button>
        <div class="text-center">
            <a href="index.php?action=login" class="text-decoration-none text-secondary">Wróć do logowania</a>
        </div>
    </form>
</div>

</body>
</html>