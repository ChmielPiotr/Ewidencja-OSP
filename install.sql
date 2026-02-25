-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 12:42 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `osp_system`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `board_members`
--

CREATE TABLE `board_members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `funkcja` varchar(100) NOT NULL,
  `data_powolania` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `board_members`
--

INSERT INTO `board_members` (`id`, `user_id`, `funkcja`, `data_powolania`) VALUES
(3, 43, 'PREZES', '2025-12-16');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `drills`
--

CREATE TABLE `drills` (
  `id` int(11) NOT NULL,
  `drill_date` date NOT NULL,
  `topic` varchar(255) NOT NULL,
  `duration` decimal(4,1) DEFAULT 1.0,
  `conductor` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drills`
--

INSERT INTO `drills` (`id`, `drill_date`, `topic`, `duration`, `conductor`, `notes`, `created_at`) VALUES
(1, '2026-02-23', 'aaaa', 1.0, 'dddd', 'aaaa', '2026-02-23 20:24:52');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `drill_participants`
--

CREATE TABLE `drill_participants` (
  `id` int(11) NOT NULL,
  `drill_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drill_participants`
--

INSERT INTO `drill_participants` (`id`, `drill_id`, `user_id`) VALUES
(1, 1, 44),
(2, 1, 43);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(150) NOT NULL,
  `ilosc` int(11) NOT NULL DEFAULT 1,
  `stan` varchar(50) NOT NULL DEFAULT 'Sprawny',
  `vehicle_id` int(11) DEFAULT NULL,
  `data_przegladu` date DEFAULT NULL,
  `uwagi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `nazwa`, `ilosc`, `stan`, `vehicle_id`, `data_przegladu`, `uwagi`) VALUES
(1, 'Motopompa Tohatsu', 1, 'Sprawny', NULL, NULL, 'Gotowa do akcji'),
(2, 'Węże W-52', 15, 'Sprawny', NULL, NULL, 'Poukładane w skrytkach'),
(3, 'Piła spalinowa Stihl', 1, 'W naprawie', 3, NULL, 'Tępy łańcuch, czeka na serwis'),
(4, 'aparat ODO', 2, 'Sprawny', 3, '2026-03-27', 'ssss');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `type` enum('wyjazd','trening','zabezpieczenie') NOT NULL,
  `event_date` date NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `event_user`
--

CREATE TABLE `event_user` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incidents`
--

CREATE TABLE `incidents` (
  `id` int(11) NOT NULL,
  `incident_date` date NOT NULL,
  `time_departure` time NOT NULL,
  `time_return` time NOT NULL,
  `incident_type` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`id`, `incident_date`, `time_departure`, `time_return`, `incident_type`, `location`, `notes`, `created_at`) VALUES
(1, '2026-02-23', '10:15:00', '12:00:00', 'Miejscowe Zagrożenie', 'Jeżowa przy wjeździe do lasu', 'Pożar suchej trawy', '2026-02-23 12:19:05'),
(2, '2024-06-05', '11:11:00', '11:23:00', 'Fałszywy alarm', 'Jezowa', 'Falszywy alarm', '2026-02-23 12:59:16');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incident_participants`
--

CREATE TABLE `incident_participants` (
  `id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_participants`
--

INSERT INTO `incident_participants` (`id`, `incident_id`, `user_id`) VALUES
(1, 1, 44),
(2, 1, 43),
(3, 2, 44);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `medical_exams`
--

CREATE TABLE `medical_exams` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_exams`
--

INSERT INTO `medical_exams` (`id`, `user_id`, `date_from`, `date_to`, `notes`, `created_at`) VALUES
(1, 43, '2024-12-20', '2025-12-20', NULL, '2026-02-23 07:15:28'),
(2, 44, '2025-03-26', '2026-03-26', NULL, '2026-02-23 07:15:28'),
(4, 43, '2025-12-21', '2026-07-22', NULL, '2026-02-23 13:37:40');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `smoke_chamber_tests`
--

CREATE TABLE `smoke_chamber_tests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `smoke_chamber_tests`
--

INSERT INTO `smoke_chamber_tests` (`id`, `user_id`, `date_from`, `date_to`, `notes`, `created_at`) VALUES
(1, 43, '2021-03-31', '2026-03-31', NULL, '2026-02-23 07:15:28'),
(2, 44, '2021-04-30', '2026-04-30', NULL, '2026-02-23 07:15:28');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `akcja` varchar(255) NOT NULL,
  `adres_ip` varchar(45) NOT NULL,
  `data_zdarzenia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `akcja`, `adres_ip`, `data_zdarzenia`) VALUES
(1, 1, 'Pomyślne zalogowanie do systemu', '127.0.0.1', '2026-02-19 10:36:05'),
(2, 1, 'Wyeksportowano pełną Ewidencję Druhów do pliku PDF', '127.0.0.1', '2026-02-19 10:41:00'),
(3, 44, 'Pomyślne zalogowanie do systemu', '127.0.0.1', '2026-02-19 10:41:46'),
(4, 44, 'Wygenerowano Kartę Strażaka PDF', '127.0.0.1', '2026-02-19 10:42:06'),
(5, 1, 'Wyeksportowano pełną Ewidencję Druhów do pliku PDF', '127.0.0.1', '2026-02-19 13:22:13'),
(6, 1, 'Pomyślne zalogowanie do systemu', '127.0.0.1', '2026-02-23 07:33:29'),
(7, 1, 'Wyeksportowano pełną Ewidencję Druhów do pliku PDF', '127.0.0.1', '2026-02-23 07:36:35'),
(8, 1, 'Wyeksportowano pełną Ewidencję Druhów do pliku PDF', '127.0.0.1', '2026-02-23 11:14:26'),
(9, 1, 'Zarejestrowano nowy wyjazd: Miejscowe Zagrożenie - Jeżowa przy wjeździe do lasu', '127.0.0.1', '2026-02-23 12:19:05'),
(10, 1, 'Wyeksportowano pełną Ewidencję Druhów do pliku PDF', '127.0.0.1', '2026-02-23 12:33:39'),
(11, 1, 'Zarejestrowano nowy wyjazd: Fałszywy alarm - Jezowa', '127.0.0.1', '2026-02-23 12:59:16'),
(12, 1, 'Wyeksportowano pełną Ewidencję Druhów do pliku PDF', '127.0.0.1', '2026-02-23 13:00:33'),
(13, 1, 'Dodano badanie lekarskie dla druha ID: 43', '127.0.0.1', '2026-02-23 13:37:40'),
(14, 1, 'Pomyślne zalogowanie do systemu', '127.0.0.1', '2026-02-23 16:14:35'),
(15, 1, 'Wyeksportowano pełną Ewidencję Druhów do pliku PDF', '127.0.0.1', '2026-02-23 16:18:26'),
(16, 44, 'Pomyślne zalogowanie do systemu', '127.0.0.1', '2026-02-23 16:20:17'),
(17, 1, 'Dodano wpis o pracach gosp.: test...', '127.0.0.1', '2026-02-23 20:03:17'),
(18, 1, 'Zaktualizowano prace ID: 1', '127.0.0.1', '2026-02-23 20:09:44'),
(19, 1, 'Zarejestrowano ćwiczenia: aaaa', '127.0.0.1', '2026-02-23 20:24:52'),
(20, 1, 'Wygenerowano Kartę Strażaka PDF', '127.0.0.1', '2026-02-23 21:10:49'),
(21, 1, 'Użytkownik samodzielnie zaktualizował swoje dane (e-mail/hasło)', '127.0.0.1', '2026-02-23 21:12:33'),
(22, 1, 'Błędne hasło podczas logowania', '127.0.0.1', '2026-02-23 21:12:57'),
(23, 1, 'Pomyślne zalogowanie do systemu', '127.0.0.1', '2026-02-23 21:13:06'),
(24, 44, 'Pomyślne zalogowanie do systemu', '127.0.0.1', '2026-02-23 21:13:34'),
(25, 1, 'Błędne hasło podczas logowania', '127.0.0.1', '2026-02-24 07:27:41'),
(26, 1, 'Pomyślne zalogowanie do systemu', '127.0.0.1', '2026-02-24 07:27:46'),
(27, 44, 'Pomyślne zalogowanie do systemu', '127.0.0.1', '2026-02-24 07:32:53'),
(28, 1, 'Zaktualizowano dane druha: Janusz Kowalski', '127.0.0.1', '2026-02-24 08:01:40'),
(29, 1, 'Zaktualizowano dane druha: Janusz Kowalski', '127.0.0.1', '2026-02-24 08:02:03'),
(30, 1, 'Edytowano sprzęt: Piła spalinowa Stihl', '127.0.0.1', '2026-02-24 08:12:36'),
(31, 1, 'Dodano sprzęt: aparat ODO', '127.0.0.1', '2026-02-24 11:19:59'),
(32, 1, 'Edytowano sprzęt: aparat ODO', '127.0.0.1', '2026-02-24 11:20:11'),
(33, 1, 'Edytowano sprzęt: aparat ODO', '127.0.0.1', '2026-02-24 11:23:33'),
(34, 1, 'Edytowano sprzęt: aparat ODO', '127.0.0.1', '2026-02-24 11:27:10');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','user') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `login`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Super', 'Admin', 'admin', NULL, '$2y$10$FDXc2Ct8MmG1bJ7bI2avIeL/Vf8I5ObyZrAQNSFPtzwPgTJ0c0IgS', 'superadmin', '2026-02-16 01:11:14'),
(43, 'Jan', 'Nowak', 'j.nowak', NULL, '$2y$10$lnu6TZt6JViQ1mJKh4xdjOXz/29mZYds2ADIEmLqAtp7Cf9Lnr5E6', 'admin', '2026-02-19 10:16:03'),
(44, 'Janusz', 'Kowalski', 'j.kowalski', NULL, '$2y$10$czUlqA0JueFV4Ou02WZ5ZuTtCWjVrDWoWg2g2ZGJau9Ri15rfrBdq', 'user', '2026-02-19 10:48:54');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `rodzaj` varchar(50) NOT NULL,
  `marka_model` varchar(100) NOT NULL,
  `numer_operacyjny` varchar(50) NOT NULL,
  `nr_rejestracyjny` varchar(50) NOT NULL,
  `przeglad_data` date NOT NULL,
  `ubezpieczenie_data` date NOT NULL,
  `ubezpieczenie_ac_data` date DEFAULT NULL,
  `uwagi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `rodzaj`, `marka_model`, `numer_operacyjny`, `nr_rejestracyjny`, `przeglad_data`, `ubezpieczenie_data`, `ubezpieczenie_ac_data`, `uwagi`) VALUES
(3, 'GBA 2,5/16', 'Volvo FL280', '339[S]23', 'SY 25675K', '2026-05-21', '2026-05-15', '2026-06-18', 'Do analizy Pompa wody');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `works`
--

CREATE TABLE `works` (
  `id` int(11) NOT NULL,
  `work_date` date NOT NULL,
  `description` text NOT NULL,
  `estimated_value` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `works`
--

INSERT INTO `works` (`id`, `work_date`, `description`, `estimated_value`, `notes`, `created_at`) VALUES
(1, '2026-02-23', 'test', 100.00, 'aa', '2026-02-23 20:03:17');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `work_participants`
--

CREATE TABLE `work_participants` (
  `id` int(11) NOT NULL,
  `work_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_participants`
--

INSERT INTO `work_participants` (`id`, `work_id`, `user_id`) VALUES
(2, 1, 44);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `board_members`
--
ALTER TABLE `board_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `drills`
--
ALTER TABLE `drills`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `drill_participants`
--
ALTER TABLE `drill_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drill_id` (`drill_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indeksy dla tabeli `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `event_user`
--
ALTER TABLE `event_user`
  ADD PRIMARY KEY (`event_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `incident_participants`
--
ALTER TABLE `incident_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_id` (`incident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `medical_exams`
--
ALTER TABLE `medical_exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `smoke_chamber_tests`
--
ALTER TABLE `smoke_chamber_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Indeksy dla tabeli `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `work_participants`
--
ALTER TABLE `work_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_id` (`work_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `board_members`
--
ALTER TABLE `board_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `drills`
--
ALTER TABLE `drills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drill_participants`
--
ALTER TABLE `drill_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `incident_participants`
--
ALTER TABLE `incident_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `medical_exams`
--
ALTER TABLE `medical_exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `smoke_chamber_tests`
--
ALTER TABLE `smoke_chamber_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `works`
--
ALTER TABLE `works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `work_participants`
--
ALTER TABLE `work_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `board_members`
--
ALTER TABLE `board_members`
  ADD CONSTRAINT `board_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `drill_participants`
--
ALTER TABLE `drill_participants`
  ADD CONSTRAINT `drill_participants_ibfk_1` FOREIGN KEY (`drill_id`) REFERENCES `drills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `drill_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `event_user`
--
ALTER TABLE `event_user`
  ADD CONSTRAINT `event_user_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_participants`
--
ALTER TABLE `incident_participants`
  ADD CONSTRAINT `incident_participants_ibfk_1` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incident_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_exams`
--
ALTER TABLE `medical_exams`
  ADD CONSTRAINT `medical_exams_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `smoke_chamber_tests`
--
ALTER TABLE `smoke_chamber_tests`
  ADD CONSTRAINT `smoke_chamber_tests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `work_participants`
--
ALTER TABLE `work_participants`
  ADD CONSTRAINT `work_participants_ibfk_1` FOREIGN KEY (`work_id`) REFERENCES `works` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
