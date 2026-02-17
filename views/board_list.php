<?php include 'views/header.php'; ?>

<div class="card mb-4 shadow-sm border-0 border-start border-danger border-5">
    <div class="card-body">
        <h4 class="text-danger fw-bold"><i class="bi bi-bank me-2"></i> Zarząd i Organ reprezentacji</h4>
        <p class="mb-0 fs-5 mt-3">
            <strong>Sposób reprezentacji:</strong> Umowy, akty oraz pełnomocnictwa i dokumenty finansowe 
            podpisują w imieniu OSP <u>prezes</u> lub <u>wiceprezes</u> i <u>skarbnik</u>.
        </p>
    </div>
</div>

<div class="row">
    <?php if (count($zarzad) > 0): ?>
        <?php foreach ($zarzad as $czlonek): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 border-0 text-center py-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-person-circle text-secondary" style="font-size: 4rem;"></i>
                        </div>
                        
                        <h5 class="card-title fw-bold fs-4"><?= htmlspecialchars($czlonek['first_name'] . ' ' . $czlonek['last_name']) ?></h5>
                        <p class="text-danger fw-bold mb-1 fs-6"><?= htmlspecialchars($czlonek['funkcja']) ?></p>
                        
                        <hr class="w-50 mx-auto my-2 text-muted">
                        
                        <p class="text-muted small mb-0">Od <?= dataPolska($czlonek['data_powolania']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">Brak członków zarządu w bazie.</h5>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>