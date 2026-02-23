<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-clipboard-data text-primary"></i> Karta Ćwiczeń</h1>
    <div>
        <a href="index.php?action=exportSingleDrillPdf&id=<?= $cwiczenie['id'] ?>" class="btn btn-outline-primary me-2" target="_blank"><i class="bi bi-printer"></i> Drukuj Kartę (PDF)</a>
        <a href="index.php?action=drills" class="btn btn-secondary">Wróć do ewidencji</a>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Szczegóły zajęć</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 35%;">Data ćwiczeń:</td>
                        <td class="fw-bold fs-5"><?= date('d.m.Y', strtotime($cwiczenie['drill_date'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Temat zajęć:</td>
                        <td class="fw-bold"><?= nl2br(htmlspecialchars($cwiczenie['topic'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Czas trwania:</td>
                        <td><span class="badge bg-primary fs-6"><?= $cwiczenie['duration'] ?> godz.</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Prowadzący:</td>
                        <td><?= htmlspecialchars($cwiczenie['conductor'] ?? 'Nie podano') ?></td>
                    </tr>
                    <?php if (!empty($cwiczenie['notes'])): ?>
                        <tr>
                            <td class="text-muted">Uwagi:</td>
                            <td class="fst-italic border-start border-warning border-3 ps-2"><?= nl2br(htmlspecialchars($cwiczenie['notes'])) ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people-fill"></i> Lista obecności</h5>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (!empty($uczestnicy)): ?>
                    <?php foreach ($uczestnicy as $u): ?>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-person-check-fill text-primary me-2"></i> 
                            <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">Brak wprowadzonych uczestników.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>