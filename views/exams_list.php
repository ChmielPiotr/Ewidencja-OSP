<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-heart-pulse text-danger"></i> Ewidencja Badań i Szkoleń</h1>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Imię i Nazwisko</th>
                    <th>Ważność badań lekarskich</th>
                    <th>Ważność komory dymowej</th>
                    <th class="text-end">Opcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($strazacy as $d): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($d['first_name'] . ' ' . $d['last_name']) ?></td>
                        <td>
                            <?php if ($d['medical_exam_date']): ?>
                                <?= date('d.m.Y', strtotime($d['medical_exam_date'])) ?>
                            <?php else: ?>
                                <span class="badge bg-danger">Brak</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($d['smoke_chamber_date']): ?>
                                <?= date('d.m.Y', strtotime($d['smoke_chamber_date'])) ?>
                            <?php else: ?>
                                <span class="badge bg-danger">Brak</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="index.php?action=userExams&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-clock-history"></i> Historia i Aktualizacja
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/footer.php'; ?>