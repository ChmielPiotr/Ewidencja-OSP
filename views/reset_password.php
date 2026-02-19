<?php include 'views/header.php'; ?>

<div class="card shadow-sm border-0 border-warning border-5" style="max-width: 500px; margin: 0 auto;">
    <div class="card-header bg-white">
        <h5 class="mb-0 text-warning"><i class="bi bi-key-fill"></i> Resetuj hasło strażaka</h5>
    </div>
    <div class="card-body">
        <p>Nadajesz nowe hasło dla: <strong><?= htmlspecialchars($this->userModel->first_name . ' ' . $this->userModel->last_name) ?></strong></p>
        
        <?php if (isset($blad)): ?>
            <div class="alert alert-danger"><?= $blad ?></div>
        <?php endif; ?>

        <?php if (isset($sukces)): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> <strong>Sukces!</strong> <?= $sukces ?>
            </div>
            <div class="d-grid mt-4">
                <a href="index.php?action=index" class="btn btn-secondary">Wróć do ewidencji</a>
            </div>
        <?php else: ?>
            <form action="index.php?action=<?= htmlspecialchars($_GET['action']) ?>&id=<?= htmlspecialchars($_GET['id']) ?>" method="POST">
                <div class="mb-4">
                    <label class="form-label">Wpisz nowe, tymczasowe hasło</label>
                    <input type="text" name="new_password" class="form-control" required placeholder="np. Start123!">
                    <div class="form-text">Podaj to hasło druhowi. Będzie mógł je zmienić w swoim profilu.</div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="index.php?action=index" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Zmień hasło</button>
                </div>
            </form>
        <?php endif; ?>

    </div>
</div>

<?php include 'views/footer.php'; ?>