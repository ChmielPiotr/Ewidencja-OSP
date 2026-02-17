<?php include 'views/header.php'; ?>

<?php if (isset($komunikat)): ?>
    <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= $komunikat ?></div>
<?php endif; ?>
<?php if (isset($blad)): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> <?= $blad ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-0 border-start border-primary border-5">
            <div class="card-body">
                <h5 class="text-muted">Twoje Badania Lekarskie</h5>
                <h3><?= formatujDate($druh['medical_exam_date']) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-0 border-start border-danger border-5">
            <div class="card-body">
                <h5 class="text-muted">Twoja Komora Dymowa</h5>
                <h3><?= formatujDate($druh['smoke_chamber_date']) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="mt-2 mb-4">
    <a href="index.php?action=generate_pdf" class="btn btn-danger shadow"><i class="bi bi-file-earmark-pdf"></i> Pobierz Kartę Strażaka (PDF)</a>
</div>

<div class="card shadow-sm border-0 mt-5" style="max-width: 600px;">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-gear"></i> Ustawienia konta</h5>
    </div>
    <div class="card-body">
        <form action="index.php?action=dashboard" method="POST">
            <input type="hidden" name="update_profile" value="1">
            
            <div class="mb-3">
                <label class="form-label">Adres e-mail (do powiadomień i resetu hasła)</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($druh['email'] ?? '') ?>" placeholder="Brak przypisanego adresu">
            </div>

            <div class="mb-3 border-top pt-3">
                <label class="form-label text-danger fw-bold">Zmień hasło</label>
                <input type="password" name="new_password" class="form-control" placeholder="Wpisz nowe hasło (zostaw puste, by nie zmieniać)">
                <div class="form-text">Jeśli nie chcesz zmieniać hasła, po prostu zostaw to pole puste.</div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Zapisz zmiany</button>
        </form>
    </div>
</div>

<?php include 'views/footer.php'; ?>