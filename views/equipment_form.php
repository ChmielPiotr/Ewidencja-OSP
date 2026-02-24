<?php include 'views/header.php'; ?>
<div class="card shadow-sm border-0" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header <?= isset($sprzet) ? 'bg-primary' : 'bg-warning text-dark' ?>">
        <h5 class="mb-0 fw-bold"><i class="bi bi-tools"></i> <?= isset($sprzet) ? 'Edytuj sprzęt' : 'Dodaj nowy sprzęt' ?></h5>
    </div>
    <div class="card-body">
        <form action="index.php?action=<?= isset($sprzet) ? 'edit_equipment&id='.$sprzet['id'] : 'add_equipment' ?>" method="POST">
            <div class="mb-3">
                <label class="form-label">Nazwa sprzętu</label>
                <input type="text" name="nazwa" class="form-control" value="<?= htmlspecialchars($sprzet['nazwa'] ?? '') ?>" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label">Ilość (szt.)</label>
                    <input type="number" name="ilosc" class="form-control" min="1" value="<?= $sprzet['ilosc'] ?? '1' ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Stan techniczny</label>
                    <select name="stan" class="form-select">
                        <option value="Sprawny" <?= ($sprzet['stan']??'') == 'Sprawny' ? 'selected' : '' ?>>Sprawny</option>
                        <option value="W naprawie" <?= ($sprzet['stan']??'') == 'W naprawie' ? 'selected' : '' ?>>W naprawie</option>
                        <option value="Wycofany" <?= ($sprzet['stan']??'') == 'Wycofany' ? 'selected' : '' ?>>Wycofany</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 border border-danger rounded p-3 bg-light">
                <label class="form-label text-danger fw-bold"><i class="bi bi-calendar-check"></i> Data następnego przeglądu (legalizacji)</label>
                <input type="date" name="data_przegladu" class="form-control border-danger" value="<?= $sprzet['data_przegladu'] ?? '' ?>">
                <div class="form-text">Wypełnij tylko dla sprzętu wymagającego okresowych testów (aparaty ODO, butle, gaśnice itp.).</div>
            </div>

            <div class="mb-3 border border-warning rounded p-3 bg-light">
                <label class="form-label fw-bold"><i class="bi bi-truck"></i> Przydział do wozu</label>
                <select name="vehicle_id" class="form-select border-warning">
                    <option value="">-- Brak (Leży w magazynie / remizie) --</option>
                    <?php foreach($pojazdy_lista as $v): ?>
                        <option value="<?= $v['id'] ?>" <?= (($sprzet['vehicle_id']??'') == $v['id']) ? 'selected' : '' ?>><?= $v['numer_operacyjny'] ?> (<?= $v['rodzaj'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Uwagi dodatkowe</label>
                <textarea name="uwagi" class="form-control" rows="2"><?= htmlspecialchars($sprzet['uwagi'] ?? '') ?></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="index.php?action=vehicles" class="btn btn-secondary">Anuluj</a>
                <button type="submit" class="btn <?= isset($sprzet) ? 'btn-primary' : 'btn-warning fw-bold' ?>"><i class="bi bi-save"></i> Zapisz sprzęt</button>
            </div>
        </form>
    </div>
</div>
<?php include 'views/footer.php'; ?>