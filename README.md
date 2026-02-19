# 🚒 System Ewidencji OSP (Ochotniczej Straży Pożarnej)

Autorski system webowy stworzony z myślą o cyfryzacji i ułatwieniu zarządzania jednostkami Ochotniczej Straży Pożarnej. Aplikacja została napisana w czystym PHP z wykorzystaniem architektury **MVC (Model-View-Controller)** oraz bazy danych MySQL.

## ⚙️ Funkcjonalności

System został podzielony na moduły, które odzwierciedlają codzienne potrzeby jednostki:
* **Ewidencja Druhów:** Zarządzanie kontami strażaków, nadawanie ról (SuperAdmin, Admin, User).
* **Zarząd OSP:** Rozbudowany system relacyjny przypisujący strażakom funkcje w zarządzie (Prezes, Naczelnik, Skarbnik itp.) wraz z datami powołania.
* **Pilnowanie Terminów:** Śledzenie dat ważności badań lekarskich oraz szkoleń w komorze dymowej.
* **Logi Systemowe:** Pełna historia operacji (kto dodał, usunął lub zedytował druha/sprzęt).
* **Eksport do PDF:** Generowanie eleganckich, gotowych do druku raportów ewidencji dzięki bibliotece *Dompdf*.
* **Bezpieczeństwo:** Haszowanie haseł (`password_hash`), ochrona przed atakami SQL Injection (dzięki PDO i bindowaniu parametrów) oraz wbudowany mechanizm twardego resetu haseł przez administratora.

## 🛠️ Technologie

* **Backend:** PHP 8.x (Obiektowo, wzorzec MVC)
* **Baza danych:** MySQL (PDO)
* **Frontend:** HTML5, CSS3, Bootstrap 5 (Responsive Design)
* **Biblioteki:** Dompdf (generowanie dokumentów)
* **Kontrola wersji:** Git

## 🚀 Instalacja (Dla programistów)

Jeśli chcesz uruchomić ten projekt lokalnie na swoim komputerze, postępuj zgodnie z poniższymi krokami:

1. **Sklonuj repozytorium:**
   ```bash
   git clone https://github.com/ChmielPiotr/Ewidencja-OSP


Przygotuj środowisko:
Upewnij się, że posiadasz zainstalowany lokalny serwer (np. XAMPP, Laragon) z obsługą PHP 8+ oraz MySQL.

Baza danych:

Utwórz nową, pustą bazę danych w phpMyAdmin (np. o nazwie osp_system).

Zaimportuj do niej plik install.sql znajdujący się w głównym katalogu projektu. Wgra on całą strukturę tabel i przygotuje czyste środowisko.

Konfiguracja połączenia:
Skonfiguruj połączenie z bazą danych w swoim głównym pliku inicjującym (np. index.php lub pliku konfiguracyjnym), podając odpowiednie dane (host, nazwa bazy, użytkownik, hasło).

🔐 Pierwsze logowanie
Baza danych z pliku install.sql zawiera domyślne konto Głównego Administratora, niezbędne do pierwszego uruchomienia systemu:

Login: admin (lub Twój domyślny login)

Hasło: admin

Ważne: Pamiętaj, aby po pierwszym logowaniu natychmiast zmienić hasło SuperAdmina ze względów bezpieczeństwa!

📂 Struktura projektu (Architektura MVC)
/controllers - Mózg aplikacji, logika poszczególnych modułów.

/models - Reprezentacja danych i bezpośrednia komunikacja z bazą MySQL (PDO).

/views - Pliki interfejsu użytkownika (HTML/PHP).

/libs - Zewnętrzne biblioteki (np. Dompdf).

Projekt stworzony w celach edukacyjnych oraz dla wsparcia jednostek OSP.
