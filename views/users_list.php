<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark"><i class="bi bi-people-fill text-primary"></i> Ewidencja Druhów</h3>
    <div>
        <a href="index.php?action=export_users_pdf" class="btn btn-danger shadow-sm me-2"><i class="bi bi-file-earmark-pdf"></i> Eksportuj PDF</a>
        <a href="index.php?action=add" class="btn btn-success shadow-sm"><i class="bi bi-person-plus"></i> Dodaj strażaka</a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3">Imię i Nazwisko</th>
                        <th>Badania lekarskie</th>
                        <th>Komora dymowa</th>
                        <th class="text-end pe-3">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($strazacy) > 0): ?>
                        <?php foreach ($strazacy as $druh): ?>
                            <tr>
                                <td class="ps-3">
                                    <strong><?= htmlspecialchars($druh['first_name'] . ' ' . $druh['last_name']) ?></strong><br>
                                    
                                    <span class="badge <?= $druh['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?> mt-1">
                                        <?= $druh['role'] === 'admin' ? 'Admin' : 'Strażak' ?>
                                    </span>

                                    <?php if (!empty($druh['funkcja'])): ?>
                                        <span class="badge bg-warning text-dark mt-1 ms-1 border border-warning">
                                            <i class="bi bi-star-fill text-dark" style="font-size: 0.7rem;"></i> <?= htmlspecialchars($druh['funkcja']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= formatujDate($druh['medical_exam_date']) ?></td>
                                <td><?= formatujDate($druh['smoke_chamber_date']) ?></td>
                                <td class="text-end pe-3">
                                    <a href="index.php?action=edit&id=<?= $druh['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="index.php?action=delete&id=<?= $druh['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Na pewno usunąć?');"><i class="bi bi-trash"></i></a>
                                    <a href="index.php?action=reset_password&id=<?= $druh['id'] ?>" class="btn btn-sm btn-outline-warning" title="Zresetuj hasło"><i class="bi bi-key"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted">Brak strażaków w bazie.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>