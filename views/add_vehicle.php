<?php include 'views/header.php'; ?>

<div class="card shadow-sm border-0" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header <?= isset($pojazd) ? 'bg-primary' : 'bg-success' ?> text-white">
        <h5 class="mb-0"><i class="bi bi-truck"></i> <?= isset($pojazd) ? 'Edytuj dane pojazdu' : 'Dodaj nowy pojazd' ?></h5>
    </div>
    <div class="card-body">
        <?php if (isset($blad)): ?><div class="alert alert-danger"><?= $blad ?></div><?php endif; ?>

        <form action="index.php?action=<?= isset($pojazd) ? 'edit_vehicle&id='.$pojazd['id'] : 'add_vehicle' ?>" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Numer operacyjny</label>
                    <input type="text" name="numer_operacyjny" class="form-control" placeholder="np. 339[S]21" value="<?= htmlspecialchars($pojazd['numer_operacyjny'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Rodzaj pojazdu</label>
                    <input type="text" name="rodzaj" class="form-control" placeholder="np. GBA 2,5/16" value="<?= htmlspecialchars($pojazd['rodzaj'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Marka i model</label>
                    <input type="text" name="marka_model" class="form-control" placeholder="np. Volvo FL280" value="<?= htmlspecialchars($pojazd['marka_model'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Numer rejestracyjny</label>
                    <input type="text" name="nr_rejestracyjny" class="form-control" value="<?= htmlspecialchars($pojazd['nr_rejestracyjny'] ?? '') ?>" required>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-danger fw-bold">Ważność Przeglądu</label>
                    <input type="date" name="przeglad_data" class="form-control border-danger" value="<?= $pojazd['przeglad_data'] ?? '' ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-primary fw-bold">Ważność polisy OC</label>
                    <input type="date" name="ubezpieczenie_data" class="form-control border-primary" value="<?= $pojazd['ubezpieczenie_data'] ?? '' ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-success fw-bold">Ważność polisy AC</label>
                    <input type="date" name="ubezpieczenie_ac_data" class="form-control border-success" value="<?= $pojazd['ubezpieczenie_ac_data'] ?? '' ?>">
                    <small class="text-muted d-block mt-1">Opcjonalnie</small>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold"><i class="bi bi-wrench"></i> Dodatkowe uwagi (usterki, info dla mechanika)</label>
                <textarea name="uwagi" class="form-control bg-light" rows="3" placeholder="np. Wymiana oleju przy 120 000 km..."><?= htmlspecialchars($pojazd['uwagi'] ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php?action=vehicles" class="btn btn-secondary">Anuluj</a>
                <button type="submit" class="btn <?= isset($pojazd) ? 'btn-primary' : 'btn-success' ?>"><i class="bi bi-save"></i> Zapisz pojazd</button>
            </div>
        </form>
    </div>
</div>
<?php include 'views/footer.php'; ?>