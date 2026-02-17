<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-danger"><i class="bi bi-truck"></i> Garaż - Wozy i Sprzęt</h3>
    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin'): ?>
        <a href="index.php?action=add_vehicle" class="btn btn-success shadow-sm"><i class="bi bi-plus-lg"></i> Dodaj pojazd</a>
    <?php endif; ?>
</div>

<div class="row">
    <?php if (count($pojazdy) > 0): ?>
        <?php foreach ($pojazdy as $pojazd): ?>
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100 border-0 border-top border-danger border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="card-title fw-bold mb-1"><?= htmlspecialchars($pojazd['numer_operacyjny']) ?></h4>
                                <h6 class="text-muted"><?= htmlspecialchars($pojazd['rodzaj']) ?> | <?= htmlspecialchars($pojazd['marka_model']) ?></h6>
                            </div>
                            <span class="badge bg-dark fs-6 font-monospace border"><?= htmlspecialchars($pojazd['nr_rejestracyjny']) ?></span>
                        </div>
                        
                        <div class="row text-center mb-3 g-2">
                            <div class="col-4">
                                <div class="p-2 border rounded bg-light">
                                    <small class="text-muted d-block">Przegląd</small>
                                    <strong><?= date('d.m.Y', strtotime($pojazd['przeglad_data'])) ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded bg-light">
                                    <small class="text-muted d-block">OC ważne do</small>
                                    <strong><?= date('d.m.Y', strtotime($pojazd['ubezpieczenie_data'])) ?></strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded bg-light">
                                    <small class="text-muted d-block">AC ważne do</small>
                                    <strong><?= $pojazd['ubezpieczenie_ac_data'] ? date('d.m.Y', strtotime($pojazd['ubezpieczenie_ac_data'])) : '<span class="text-secondary">-</span>' ?></strong>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($pojazd['uwagi'])): ?>
                            <div class="alert alert-warning py-2 mb-0 border-warning small">
                                <i class="bi bi-tools fw-bold text-dark me-1"></i> <strong>Uwagi techniczne:</strong><br>
                                <?= nl2br(htmlspecialchars($pojazd['uwagi'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin'): ?>
                        <div class="card-footer bg-white text-end border-0 pb-3">
                            <a href="index.php?action=edit_vehicle&id=<?= $pojazd['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Edytuj</a>
                            <a href="index.php?action=delete_vehicle&id=<?= $pojazd['id'] ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Czy na pewno chcesz usunąć ten wóz z systemu?');"><i class="bi bi-trash"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">Garaż jest pusty. Brak pojazdów w systemie.</h5>
        </div>
    <?php endif; ?>
</div>
<hr class="my-5 border-danger border-3 opacity-50">

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h3 class="text-dark"><i class="bi bi-tools text-warning"></i> Wyposażenie i Sprzęt</h3>
    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin'): ?>
        <a href="index.php?action=add_equipment" class="btn btn-warning shadow-sm fw-bold"><i class="bi bi-plus-lg"></i> Dodaj sprzęt</a>
    <?php endif; ?>
</div>

<div class="card shadow-sm border-0 border-top border-warning border-4 mb-5">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nazwa sprzętu</th>
                        <th>Ilość</th>
                        <th>Przydział (Gdzie się znajduje)</th>
                        <th>Stan techniczny</th>
                        <th>Uwagi</th>
                        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin'): ?>
                            <th class="text-end pe-3">Akcje</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($sprzety) > 0): ?>
                        <?php foreach ($sprzety as $s): ?>
                            <tr>
                                <td class="ps-3 fw-bold"><?= htmlspecialchars($s['nazwa']) ?></td>
                                <td><span class="badge bg-secondary rounded-pill fs-6"><?= $s['ilosc'] ?> szt.</span></td>
                                <td>
                                    <?php if ($s['numer_operacyjny']): ?>
                                        <span class="badge bg-danger"><i class="bi bi-truck"></i> <?= htmlspecialchars($s['numer_operacyjny']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-dark"><i class="bi bi-house"></i> Magazyn / Remiza</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $kolor = 'success';
                                        if ($s['stan'] == 'W naprawie') $kolor = 'warning text-dark';
                                        if ($s['stan'] == 'Wycofany') $kolor = 'danger';
                                    ?>
                                    <span class="badge bg-<?= $kolor ?>"><?= $s['stan'] ?></span>
                                </td>
                                <td class="text-muted small"><?= htmlspecialchars($s['uwagi'] ?? '') ?></td>
                                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin'): ?>
                                    <td class="text-end pe-3">
                                        <a href="index.php?action=edit_equipment&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="index.php?action=delete_equipment&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Usunąć ten sprzęt?');"><i class="bi bi-trash"></i></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Brak sprzętu w ewidencji.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'views/footer.php'; ?>