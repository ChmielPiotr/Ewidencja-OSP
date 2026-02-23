<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-cone-striped text-success"></i> Rejestracja nowej pracy</h1>
    <a href="index.php?action=works" class="btn btn-secondary">Wróć do ewidencji</a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-success">
            <div class="card-body p-4">
                
                <?php if (isset($blad)): ?>
                    <div class="alert alert-danger"><?= $blad ?></div>
                <?php endif; ?>

                <form action="index.php?action=addWork" method="POST">
                    <h5 class="text-success border-bottom pb-2 mb-3">Zakres i detale</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Data wykonania pracy</label>
                            <input type="date" name="work_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Wycena pracy (wartość w zł, opcjonalnie)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="estimated_value" class="form-control" placeholder="np. 500.00">
                                <span class="input-group-text">PLN</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Opis (zakres wykonywanych prac, np. malowanie remizy)</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Uwagi dodatkowe (opcjonalnie)</label>
                        <input type="text" name="notes" class="form-control" placeholder="np. Materiały kupione ze środków własnych">
                    </div>

                    <h5 class="text-success border-bottom pb-2 mb-3">Kto pracował? (Zaznacz druhów)</h5>
                    
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
                        <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-save"></i> Zapisz prace w ewidencji</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>