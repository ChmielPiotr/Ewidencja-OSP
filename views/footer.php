</div> </div> </div> <footer class="bg-dark text-white text-center py-3 shadow-lg">
        <div class="container">
            <small class="text-white-50">
                &copy; <?= date('Y') ?> System Ewidencji OSP. Wszelkie prawa zastrzeżone.<br>
                <span class="badge bg-secondary mt-1">Wersja 1.0.2</span>
            </small>
        </div>
    </footer>

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Minimum 100% wysokości okna przeglądarki */
            background-color: #f8f9fa; /* Jasnoszare tło pod treścią (opcjonalnie) */
        }
        .container-fluid {
            flex: 1; /* Rozciąga główny kontener, spychając stopkę na sam dół */
        }
        footer {
            margin-top: auto; /* Dopycha stopkę do końca */
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>