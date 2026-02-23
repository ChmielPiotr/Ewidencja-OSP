<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-hammer text-success"></i> Ewidencja prac na rzecz OSP</h1>
    
    <div class="d-flex align-items-center">
        <form action="index.php" method="GET" class="d-flex me-3">
            <input type="hidden" name="action" value="works">
            <select name="year" class="form-select form-select-sm border-success bg-light fw-bold text-success" onchange="this.form.submit()">
                <?php foreach ($dostepne_lata as $rok): ?>
                    <option value="<?= $rok ?>" <?= ($rok == $wybrany_rok) ? 'selected' : '' ?>>Rocznik <?= $rok ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        
        <a href="index.php?action=exportWorksPdf&year=<?= $wybrany_rok ?>" class="btn btn-outline-dark me-2" target="_blank">
            <i class="bi bi-file-pdf text-danger"></i> Pobierz Ewidencję <?= $wybrany_rok ?>
        </a>
        <a href="index.php?action=addWork" class="btn btn-success"><i class="bi bi-plus-circle"></i> Zarejestruj nową pracę</a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> Operacja zakończona sukcesem!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 15%;">Data</th>
                        <th style="width: 40%;">Zakres prac</th>
                        <th style="width: 15%;">Szac. wartość</th>
                        <th class="text-end" style="width: 30%;">Opcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($prace)): ?>
                        <?php foreach ($prace as $praca): ?>
                            <tr>
                                <td><strong><?= date('d.m.Y', strtotime($praca['work_date'])) ?></strong></td>
                                <td><?= htmlspecialchars(mb_substr($praca['description'], 0, 50)) ?><?= strlen($praca['description']) > 50 ? '...' : '' ?></td>
                                <td>
                                    <?php if ($praca['estimated_value'] > 0): ?>
                                        <span class="badge bg-success fs-6"><?= number_format($praca['estimated_value'], 2, ',', ' ') ?> zł</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="index.php?action=viewWork&id=<?= $praca['id'] ?>" class="btn btn-sm btn-outline-success" title="Szczegóły">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="index.php?action=editWork&id=<?= $praca['id'] ?>" class="btn btn-sm btn-outline-primary mx-1" title="Edytuj">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="index.php?action=deleteWork&id=<?= $praca['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Czy na pewno chcesz usunąć ten wpis z ewidencji?');" title="Usuń">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Brak prac w wybranym roku.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>