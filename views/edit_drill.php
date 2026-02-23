<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-pencil-square text-primary"></i> Edytuj wpis o ćwiczeniach</h1>
    <a href="index.php?action=drills" class="btn btn-secondary">Wróć do ewidencji</a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-primary">
            <div class="card-body p-4">
                <?php if (isset($blad)): ?><div class="alert alert-danger"><?= $blad ?></div><?php endif; ?>

                <form action="index.php?action=editDrill&id=<?= $cwiczenie['id'] ?>" method="POST">
                    <h5 class="text-primary border-bottom pb-2 mb-3">Szczegóły zajęć</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Data ćwiczeń</label>
                            <input type="date" name="drill_date" class="form-control" value="<?= $cwiczenie['drill_date'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Czas trwania (godz.)</label>
                            <input type="number" step="0.5" name="duration" class="form-control" value="<?= $cwiczenie['duration'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Prowadzący zajęcia</label>
                            <input type="text" name="conductor" class="form-control" value="<?= htmlspecialchars($cwiczenie['conductor'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Temat ćwiczeń / szkolenia</label>
                        <textarea name="topic" class="form-control" rows="2" required><?= htmlspecialchars($cwiczenie['topic']) ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Uwagi dodatkowe (opcjonalnie)</label>
                        <input type="text" name="notes" class="form-control" value="<?= htmlspecialchars($cwiczenie['notes'] ?? '') ?>">
                    </div>

                    <h5 class="text-primary border-bottom pb-2 mb-3">Lista obecności (Odznacz lub dodaj druhów)</h5>
                    
                    <div class="row mb-4">
                        <?php foreach ($strazacy as $druh): ?>
                            <?php $is_checked = in_array($druh['id'], $zaznaczeni_id) ? 'checked' : ''; ?>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="participants[]" value="<?= $druh['id'] ?>" id="druh_<?= $druh['id'] ?>" <?= $is_checked ?>>
                                    <label class="form-check-label" for="druh_<?= $druh['id'] ?>">
                                        <?= htmlspecialchars($druh['first_name'] . ' ' . $druh['last_name']) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Zapisz zmiany</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>