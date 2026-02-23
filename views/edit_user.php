<?php include 'views/header.php'; ?>

<div class="card shadow-sm border-0" style="max-width: 1000px; margin: 0 auto;">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edytuj dane strażaka</h5>
    </div>
    <div class="card-body">
        <?php if (isset($blad)): ?>
            <div class="alert alert-danger"><?= $blad ?></div>
        <?php endif; ?>

        <form action="index.php?action=edit&id=<?= $druh['id'] ?>" method="POST">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Imię</label>
                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($druh['first_name']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nazwisko</label>
                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($druh['last_name']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Login w systemie (wymagany)</label>
                    <input type="text" name="login" class="form-control" value="<?= htmlspecialchars($druh['login'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">E-mail (opcjonalny)</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($druh['email'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3 border border-warning p-3 rounded bg-light">
                <label class="form-label fw-bold">Uprawnienia w systemie</label>
                <select name="role" class="form-select border-warning">
                    <option value="user" <?= $druh['role'] === 'user' ? 'selected' : '' ?>>Zwykły Strażak</option>
                    <option value="admin" <?= $druh['role'] === 'admin' ? 'selected' : '' ?>>Administrator (Naczelnik)</option>
                </select>
            </div>
            
            <hr>
            
            <div class="mb-3 border border-danger p-3 rounded bg-white shadow-sm">
                <label class="form-label fw-bold text-danger"><i class="bi bi-bank"></i> Przynależność do Zarządu OSP</label>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Funkcja w Zarządzie</label>
                        <select name="funkcja_zarzad" class="form-select border-danger">
                            <option value="">-- Brak (Nie jest w zarządzie) --</option>
                            <option value="PREZES" <?= ($druh['funkcja_zarzad'] ?? '') === 'PREZES' ? 'selected' : '' ?>>PREZES</option>
                            <option value="WICEPREZES" <?= ($druh['funkcja_zarzad'] ?? '') === 'WICEPREZES' ? 'selected' : '' ?>>WICEPREZES</option>
                            <option value="NACZELNIK" <?= ($druh['funkcja_zarzad'] ?? '') === 'NACZELNIK' ? 'selected' : '' ?>>NACZELNIK</option>
                            <option value="ZASTĘPCA NACZELNIKA" <?= ($druh['funkcja_zarzad'] ?? '') === 'ZASTĘPCA NACZELNIKA' ? 'selected' : '' ?>>ZASTĘPCA NACZELNIKA</option>
                            <option value="SKARBNIK" <?= ($druh['funkcja_zarzad'] ?? '') === 'SKARBNIK' ? 'selected' : '' ?>>SKARBNIK</option>
                            <option value="SEKRETARZ" <?= ($druh['funkcja_zarzad'] ?? '') === 'SEKRETARZ' ? 'selected' : '' ?>>SEKRETARZ</option>
                            <option value="GOSPODARZ" <?= ($druh['funkcja_zarzad'] ?? '') === 'GOSPODARZ' ? 'selected' : '' ?>>GOSPODARZ</option>
                            <option value="CZŁONEK ZARZĄDU" <?= ($druh['funkcja_zarzad'] ?? '') === 'CZŁONEK ZARZĄDU' ? 'selected' : '' ?>>CZŁONEK ZARZĄDU</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Data powołania</label>
                        <input type="date" name="data_powolania_zarzad" class="form-control" value="<?= $druh['data_powolania_zarzad'] ?? '' ?>">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="index.php?action=index" class="btn btn-secondary">Wróć</a>
                <button type="submit" class="btn btn-primary">Zapisz zmiany w profilu</button>
            </div>
        </form>

        
    </div>
</div>

<?php include 'views/footer.php'; ?>