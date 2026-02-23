<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-journal-plus text-primary"></i> Rejestracja nowych ćwiczeń</h1>
    <a href="index.php?action=drills" class="btn btn-secondary">Wróć do ewidencji</a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-primary">
            <div class="card-body p-4">
                
                <?php if (isset($blad)): ?><div class="alert alert-danger"><?= $blad ?></div><?php endif; ?>

                <form action="index.php?action=addDrill" method="POST">
                    <h5 class="text-primary border-bottom pb-2 mb-3">Szczegóły zajęć</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Data ćwiczeń</label>
                            <input type="date" name="drill_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Czas trwania (godz.)</label>
                            <input type="number" step="0.5" name="duration" class="form-control" placeholder="np. 2.5" value="1.0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Prowadzący zajęcia</label>
                            <input type="text" name="conductor" class="form-control" placeholder="np. d-ca plut. J. Kowalski">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Temat ćwiczeń / szkolenia</label>
                        <textarea name="topic" class="form-control" rows="2" placeholder="np. Rozwijanie linii gaśniczych w trudnym terenie" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Uwagi dodatkowe (opcjonalnie)</label>
                        <input type="text" name="notes" class="form-control" placeholder="np. Zużyto 20 litrów środka pianotwórczego">
                    </div>

                    <h5 class="text-primary border-bottom pb-2 mb-3">Lista obecności (Zaznacz druhów)</h5>
                    
                    <div class="row mb-4">
                        <?php foreach ($strazacy as $druh): ?>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="participants[]" value="<?= $druh['id'] ?>" id="druh_<?= $druh['id'] ?>">
                                    <label class="form-check-label" for="druh_<?= $druh['id'] ?>">
                                        <?= htmlspecialchars($druh['first_name'] . ' ' . $druh['last_name']) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Zapisz ćwiczenia w ewidencji</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>