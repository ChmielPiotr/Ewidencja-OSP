<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-journal-medical text-primary"></i> Ewidencja Ćwiczeń i Szkoleń</h1>
    
    <div class="d-flex align-items-center">
        <form action="index.php" method="GET" class="d-flex me-3">
            <input type="hidden" name="action" value="drills">
            <select name="year" class="form-select form-select-sm border-primary bg-light fw-bold text-primary" onchange="this.form.submit()">
                <?php foreach ($dostepne_lata as $rok): ?>
                    <option value="<?= $rok ?>" <?= ($rok == $wybrany_rok) ? 'selected' : '' ?>>Rocznik <?= $rok ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        
        <a href="index.php?action=exportDrillsPdf&year=<?= $wybrany_rok ?>" class="btn btn-outline-dark me-2" target="_blank">
            <i class="bi bi-file-pdf text-danger"></i> Pobierz Ewidencję <?= $wybrany_rok ?>
        </a>
        <a href="index.php?action=addDrill" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Zarejestruj ćwiczenia</a>
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
                        <th style="width: 35%;">Temat ćwiczeń</th>
                        <th style="width: 10%;">Czas</th>
                        <th style="width: 20%;">Prowadzący</th>
                        <th class="text-end" style="width: 20%;">Opcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cwiczenia)): ?>
                        <?php foreach ($cwiczenia as $cwiczenie): ?>
                            <tr>
                                <td><strong><?= date('d.m.Y', strtotime($cwiczenie['drill_date'])) ?></strong></td>
                                <td><?= htmlspecialchars(mb_substr($cwiczenie['topic'], 0, 50)) ?><?= strlen($cwiczenie['topic']) > 50 ? '...' : '' ?></td>
                                <td><span class="badge bg-secondary"><?= $cwiczenie['duration'] ?> h</span></td>
                                <td><?= htmlspecialchars($cwiczenie['conductor'] ?? '-') ?></td>
                                <td class="text-end">
                                    <a href="index.php?action=viewDrill&id=<?= $cwiczenie['id'] ?>" class="btn btn-sm btn-outline-info" title="Szczegóły">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="index.php?action=editDrill&id=<?= $cwiczenie['id'] ?>" class="btn btn-sm btn-outline-primary mx-1" title="Edytuj">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="index.php?action=deleteDrill&id=<?= $cwiczenie['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Czy na pewno usunąć te ćwiczenia z ewidencji?');" title="Usuń">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Brak wpisów o ćwiczeniach w wybranym roku.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>