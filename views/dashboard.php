<?php include 'views/header.php'; ?>

<?php if (isset($komunikat)): ?>
    <div class="alert alert-success shadow-sm"><i class="bi bi-check-circle"></i> <?= $komunikat ?></div>
<?php endif; ?>
<?php if (isset($blad)): ?>
    <div class="alert alert-danger shadow-sm"><i class="bi bi-exclamation-triangle"></i> <?= $blad ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mt-2 mb-3">
    <h4 class="text-secondary mb-0"><i class="bi bi-heart-pulse"></i> Moje uprawnienia</h4>
    <a href="index.php?action=generate_pdf" class="btn btn-danger shadow-sm"><i class="bi bi-file-earmark-pdf"></i> Pobierz Kartę Strażaka</a>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-0 border-start border-primary border-5 h-100">
            <div class="card-body">
                <h5 class="text-muted">Badania Lekarskie</h5>
                <h3 class="mb-0"><?= formatujDate($druh['medical_exam_date']) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm border-0 border-start border-danger border-5 h-100">
            <div class="card-body">
                <h5 class="text-muted">Komora Dymowa</h5>
                <h3 class="mb-0"><?= formatujDate($druh['smoke_chamber_date']) ?></h3>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-3 mt-2 text-secondary"><i class="bi bi-bar-chart-fill"></i> Moja aktywność w OSP</h4>
<div class="row mb-5">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-danger shadow-sm h-100 border-0">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-white text-danger d-flex justify-content-center align-items-center me-3 shadow" style="width: 60px; height: 60px;">
                    <i class="bi bi-fire fs-2"></i>
                </div>
                <div>
                    <h6 class="card-title mb-0 text-uppercase fw-bold opacity-75">Akcje Ratownicze</h6>
                    <h2 class="mb-0 fw-bold"><?= $druh['stats']['incidents'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-white bg-success shadow-sm h-100 border-0">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-white text-success d-flex justify-content-center align-items-center me-3 shadow" style="width: 60px; height: 60px;">
                    <i class="bi bi-hammer fs-2"></i>
                </div>
                <div>
                    <h6 class="card-title mb-0 text-uppercase fw-bold opacity-75">Prace Gospodarcze</h6>
                    <h2 class="mb-0 fw-bold"><?= $druh['stats']['works'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-white bg-info shadow-sm h-100 border-0">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-white text-info d-flex justify-content-center align-items-center me-3 shadow" style="width: 60px; height: 60px;">
                    <i class="bi bi-journal-medical fs-2"></i>
                </div>
                <div>
                    <h6 class="card-title mb-0 text-uppercase fw-bold opacity-75 text-dark">Ćwiczenia OSP</h6>
                    <h2 class="mb-0 fw-bold text-dark"><?= $druh['stats']['drills'] ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-5" style="max-width: 600px;">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-gear"></i> Ustawienia konta</h5>
    </div>
    <div class="card-body">
        <form action="index.php?action=dashboard" method="POST">
            <input type="hidden" name="update_profile" value="1">
            
            <div class="mb-3">
                <label class="form-label">Adres e-mail (do powiadomień i resetu hasła)</label>
                <input type="email" name="email" class="form-control bg-light" value="<?= htmlspecialchars($druh['email'] ?? '') ?>" placeholder="Brak przypisanego adresu">
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