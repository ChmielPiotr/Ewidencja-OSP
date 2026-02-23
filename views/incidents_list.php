<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-fire text-danger"></i> Udział OSP w akcjach ratowniczych</h1>
    
    <div class="d-flex align-items-center">
        <form action="index.php" method="GET" class="d-flex me-3">
            <input type="hidden" name="action" value="incidents">
            <select name="year" class="form-select form-select-sm border-danger bg-light fw-bold text-danger" onchange="this.form.submit()">
                <?php foreach ($dostepne_lata as $rok): ?>
                    <option value="<?= $rok ?>" <?= ($rok == $wybrany_rok) ? 'selected' : '' ?>>Rocznik <?= $rok ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        
        <a href="index.php?action=exportIncidentsPdf&year=<?= $wybrany_rok ?>" class="btn btn-outline-dark me-2" target="_blank">
            <i class="bi bi-file-pdf text-danger"></i> Pobierz Rejestr <?= $wybrany_rok ?> (PDF)
        </a>
        <a href="index.php?action=addIncident" class="btn btn-danger"><i class="bi bi-plus-circle"></i> Zarejestruj wyjazd</a>
    </div>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] == 'incident_added'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> Pomyślnie dodano nowy wyjazd do ewidencji!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Data</th>
                        <th>Godz. wyjazdu</th>
                        <th>Rodzaj zdarzenia</th>
                        <th>Miejsce</th>
                        <th class="text-end">Opcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($akcje)): ?>
                        <?php foreach ($akcje as $akcja): ?>
                            <tr>
                                <td><strong><?= date('d.m.Y', strtotime($akcja['incident_date'])) ?></strong></td>
                                <td><?= date('H:i', strtotime($akcja['time_departure'])) ?></td>
                                <td>
                                    <?php if ($akcja['incident_type'] == 'Pożar'): ?>
                                        <span class="badge bg-danger">Pożar</span>
                                    <?php elseif ($akcja['incident_type'] == 'Miejscowe Zagrożenie'): ?>
                                        <span class="badge bg-warning text-dark">Miejscowe Zagrożenie</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($akcja['incident_type']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($akcja['location']) ?></td>
                                <td class="text-end">
                                    <a href="index.php?action=viewIncident&id=<?= $akcja['id'] ?>" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-eye"></i> Szczegóły
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Brak zarejestrowanych wyjazdów w ewidencji.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>