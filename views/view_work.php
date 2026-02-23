<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-clipboard-check text-success"></i> Podsumowanie prac</h1>
    <div>
        <a href="index.php?action=exportSingleWorkPdf&id=<?= $praca['id'] ?>" class="btn btn-outline-success me-2" target="_blank"><i class="bi bi-printer"></i> Drukuj Kartę (PDF)</a>
        <a href="index.php?action=works" class="btn btn-secondary">Wróć do ewidencji</a>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Szczegóły wpisu</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 35%;">Data wykonania:</td>
                        <td class="fw-bold fs-5"><?= date('d.m.Y', strtotime($praca['work_date'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Zakres prac:</td>
                        <td class="fw-bold"><?= nl2br(htmlspecialchars($praca['description'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Szacowana wartość:</td>
                        <td>
                            <?php if ($praca['estimated_value'] > 0): ?>
                                <span class="badge bg-success fs-5"><?= number_format($praca['estimated_value'], 2, ',', ' ') ?> zł</span>
                            <?php else: ?>
                                <span class="text-muted">Nie wyceniono</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if (!empty($praca['notes'])): ?>
                        <tr>
                            <td class="text-muted">Uwagi:</td>
                            <td class="fst-italic border-start border-warning border-3 ps-2"><?= nl2br(htmlspecialchars($praca['notes'])) ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow-sm border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-people-fill"></i> Osoby zaangażowane</h5>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (!empty($uczestnicy)): ?>
                    <?php foreach ($uczestnicy as $u): ?>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-wrench-adjustable-circle text-success me-2"></i> 
                            <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">Brak oznaczonych uczestników prac.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>