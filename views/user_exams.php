<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person-badge"></i> Badania: <?= htmlspecialchars($druh['first_name'] . ' ' . $druh['last_name']) ?></h1>
    <a href="index.php?action=exams" class="btn btn-secondary">Wróć do listy</a>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] == 'exam_added'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> Pomyślnie dodano nowy wpis do historii!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-7">
        <h5><i class="bi bi-file-medical"></i> Historia Badań Lekarskich</h5>
        <div class="table-responsive mb-4">
            <table class="table table-sm table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Ważne Od</th><th>Ważne Do</th><th>Uwagi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($historia_badan)): ?>
                        <?php foreach ($historia_badan as $badanie): ?>
                            <tr>
                                <td><?= date('d.m.Y', strtotime($badanie['date_from'])) ?></td>
                                <td><strong><?= date('d.m.Y', strtotime($badanie['date_to'])) ?></strong></td>
                                <td><?= htmlspecialchars($badanie['notes'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center text-muted">Brak wpisów</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h5><i class="bi bi-fire"></i> Historia Komory Dymowej</h5>
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Ważne Od</th><th>Ważne Do</th><th>Uwagi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($historia_komory)): ?>
                        <?php foreach ($historia_komory as $komora): ?>
                            <tr>
                                <td><?= date('d.m.Y', strtotime($komora['date_from'])) ?></td>
                                <td><strong><?= date('d.m.Y', strtotime($komora['date_to'])) ?></strong></td>
                                <td><?= htmlspecialchars($komora['notes'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center text-muted">Brak wpisów</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-danger shadow-sm mb-4">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="bi bi-plus-circle"></i> Dodaj nowe badanie / szkolenie</h6>
            </div>
            <div class="card-body">
                <form action="index.php?action=addExam" method="POST">
                    <input type="hidden" name="user_id" value="<?= $druh['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Rodzaj wpisu</label>
                        <select name="exam_type" class="form-select" required>
                            <option value="medical">Badanie Lekarskie</option>
                            <option value="smoke">Test w Komorze Dymowej</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Ważne od</label>
                            <input type="date" name="date_from" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Ważne do</label>
                            <input type="date" name="date_to" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Uwagi (opcjonalnie)</label>
                        <input type="text" name="notes" class="form-control" placeholder="np. Brak przeciwwskazań">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger">Zapisz w ewidencji</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>