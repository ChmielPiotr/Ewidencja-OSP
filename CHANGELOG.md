## [v1.1.0] - Cyfryzacja "Książki Naczelnika"
**Ogromna aktualizacja wprowadzająca kluczowe moduły operacyjne dla Naczelnika OSP.**

### 🚀 Nowe Moduły (Funkcjonalności)
* **Historia Badań i Szkoleń:** Wydzielono badania lekarskie i testy w komorze dymowej do osobnych, szczegółowych rejestrów. Możliwość śledzenia pełnej historii od-do.
* **Akcje Ratownicze:** Nowy moduł ewidencji wyjazdów. Rejestracja daty, godzin, rodzaju zdarzenia oraz imienna lista załogi (druhów biorących udział w akcji).
* **Prace Gospodarcze:** Moduł pozwalający ewidencjonować prace na rzecz jednostki, wraz z wyceną wartości pracy i listą zaangażowanych druhów.
* **Ćwiczenia i Szkolenia:** Ewidencja zbiórek szkoleniowych (temat, czas trwania, prowadzący, lista obecności).

### ✨ Ulepszenia i Zmiany (Enhancements)
* **Raportowanie PDF (Dompdf):** Dodano zaawansowane generowanie dokumentów PDF. Możliwość pobrania zbiorczego rejestru dla każdego modułu oraz wydruku szczegółowej "Karty Zdarzenia/Prac/Ćwiczeń".
* **Filtrowanie Rocznikami:** Listy akcji, prac i ćwiczeń zostały wyposażone w inteligentny filtr, wyświetlający dane z podziałem na lata (zgodnie z fizyczną Książką Naczelnika).
* **Optymalizacja Formularzy:** Oczyszczono formularz dodawania strażaka ze zbędnych pól. Zautomatyzowano zaznaczanie druhów (checkboxy) w modułach operacyjnych.

### 🛠 Zmiany Techniczne (Pod maską)
* **Refaktoryzacja bazy danych:** Usunięto przestarzałe kolumny `medical_exam_date` i `smoke_chamber_date` z tabeli `users`.
* **Nowe tabele relacyjne:** Wdrożono 8 nowych tabel (`medical_exams`, `smoke_chamber_tests`, `incidents`, `incident_participants`, `works`, `work_participants`, `drills`, `drill_participants`) z kaskadowym usuwaniem (ON DELETE CASCADE).
* **Bezpieczeństwo (Transakcje PDO):** Zapisywanie danych do wielu tabel jednocześnie zostało zabezpieczone mechanizmem transakcji (Rollback w przypadku błędu).