<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie OSP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card bg-light text-dark shadow-lg p-4" style="width: 100%; max-width: 400px;">
    <h3 class="text-center mb-4">🚒 Ewidencja OSP</h3>
    
    <?php if (isset($blad)): ?>
        <div class="alert alert-danger"><?= $blad ?></div>
    <?php endif; ?>

    <form action="index.php?action=login" method="POST">
        <div class="mb-3">
            <label class="form-label">Login w systemie</label>
            <input type="text" name="login" class="form-control" required placeholder="np. naczelnik">
        </div>
        <div class="mb-3">
            <label class="form-label">Hasło</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="index.php?action=forgot_password" class="text-decoration-none small text-danger">Zapomniałeś hasła?</a>
        </div>
        
        <button type="submit" class="btn btn-danger w-100 fs-5">Zaloguj się</button>
    </form>
</div>

</body>
</html>