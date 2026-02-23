<?php include 'views/header.php'; ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-truck text-danger"></i> Rejestracja nowego wyjazdu</h1>
    <a href="index.php?action=incidents" class="btn btn-secondary">Wróć do listy</a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-danger">
            <div class="card-body p-4">
                
                <?php if (isset($blad)): ?>
                    <div class="alert alert-danger"><?= $blad ?></div>
                <?php endif; ?>

                <form action="index.php?action=addIncident" method="POST">
                    
                    <h5 class="text-danger border-bottom pb-2 mb-3">Informacje ogólne</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Data wyjazdu</label>
                            <input type="date" name="incident_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Godzina wyjazdu (alarmu)</label>
                            <input type="time" name="time_departure" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Godzina powrotu</label>
                            <input type="time" name="time_return" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Rodzaj zdarzenia</label>
                            <select name="incident_type" class="form-select border-danger" required>
                                <option value="Pożar">Pożar</option>
                                <option value="Miejscowe Zagrożenie">Miejscowe Zagrożenie</option>
                                <option value="Fałszywy alarm">Fałszywy alarm</option>
                                <option value="Zabezpieczenie rejonu">Zabezpieczenie rejonu</option>
                                <option value="Ćwiczenia / Manewry">Ćwiczenia / Manewry</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Miejsce zdarzenia (miejscowość / adres)</label>
                            <input type="text" name="location" class="form-control" required placeholder="np. Las przy ul. Polnej">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Krótki opis akcji / Uwagi (opcjonalnie)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="np. Pożar suchej trawy, zużyto 2 prądy wody..."></textarea>
                    </div>

                    <h5 class="text-danger border-bottom pb-2 mb-3">Załoga (Wybierz uczestników)</h5>
                    
                    <div class="row mb-4">
                        <?php foreach ($strazacy as $druh): ?>
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="participants[]" value="<?= $druh['id'] ?>" id="druh_<?= $druh['id'] ?>">
                                    <label class="form-check-label" for="druh_<?= $druh['id'] ?>">
                                        <?= htmlspecialchars($druh['first_name'] . ' ' . $druh['last_name']) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-danger btn-lg"><i class="bi bi-save"></i> Zapisz wyjazd w ewidencji</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>