<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-clipboard-data"></i> Raport z wyjazdu</h1>
    <div>
        <a href="index.php?action=exportSingleIncidentPdf&id=<?= $akcja['id'] ?>" class="btn btn-outline-danger me-2" target="_blank"><i class="bi bi-printer"></i> Drukuj Kartę (PDF)</a>
        <a href="index.php?action=incidents" class="btn btn-secondary">Wróć do listy</a>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Szczegóły zdarzenia</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 35%;">Data:</td>
                        <td class="fw-bold fs-5"><?= date('d.m.Y', strtotime($akcja['incident_date'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Godziny akcji:</td>
                        <td>
                            <span class="badge bg-danger">Wyjazd: <?= date('H:i', strtotime($akcja['time_departure'])) ?></span>
                            <i class="bi bi-arrow-right mx-1"></i>
                            <span class="badge bg-secondary">Powrót: <?= date('H:i', strtotime($akcja['time_return'])) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Rodzaj zdarzenia:</td>
                        <td class="fw-bold"><?= htmlspecialchars($akcja['incident_type']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Miejsce:</td>
                        <td><?= htmlspecialchars($akcja['location']) ?></td>
                    </tr>
                    <?php if (!empty($akcja['notes'])): ?>
                        <tr>
                            <td class="text-muted">Opis / Uwagi:</td>
                            <td class="fst-italic border-start border-warning border-3 ps-2"><?= nl2br(htmlspecialchars($akcja['notes'])) ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow-sm border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-people-fill"></i> Uczestnicy akcji</h5>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (!empty($uczestnicy)): ?>
                    <?php foreach ($uczestnicy as $u): ?>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-person-check text-success me-2"></i> 
                            <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">Nie wprowadzono uczestników.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>
