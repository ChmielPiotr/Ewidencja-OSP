-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2026 at 11:03 AM
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
-- Struktura tabeli dla tabeli `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(150) NOT NULL,
  `ilosc` int(11) NOT NULL DEFAULT 1,
  `stan` varchar(50) NOT NULL DEFAULT 'Sprawny',
  `vehicle_id` int(11) DEFAULT NULL,
  `uwagi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `nazwa`, `ilosc`, `stan`, `vehicle_id`, `uwagi`) VALUES
(1, 'Motopompa Tohatsu', 1, 'Sprawny', NULL, 'Gotowa do akcji'),
(2, 'Węże W-52', 15, 'Sprawny', NULL, 'Poukładane w skrytkach'),
(3, 'Piła spalinowa Stihl', 1, 'W naprawie', NULL, 'Tępy łańcuch, czeka na serwis');

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
-- Struktura tabeli dla tabeli `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `akcja` varchar(255) NOT NULL,
  `adres_ip` varchar(45) NOT NULL,
  `data_zdarzenia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `medical_exam_date` date DEFAULT NULL,
  `smoke_chamber_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `login`, `email`, `password`, `role`, `medical_exam_date`, `smoke_chamber_date`, `created_at`) VALUES
(1, 'Super', 'Admin', 'admin', NULL, '$2y$10$Of1bB3XiaFgUDD0nBLPHT.NkttDfZ7RRWenISFAsE0BEOh48lhBW6', 'superadmin', NULL, NULL, '2026-02-16 01:11:14'),
(43, 'Jan', 'Nowak', 'j.nowak', NULL, '$2y$10$lnu6TZt6JViQ1mJKh4xdjOXz/29mZYds2ADIEmLqAtp7Cf9Lnr5E6', 'admin', '2025-12-20', '2026-03-31', '2026-02-19 10:16:03'),
(44, 'Janusz', 'Kowalski', 'j.kowalski', NULL, '$2y$10$czUlqA0JueFV4Ou02WZ5ZuTtCWjVrDWoWg2g2ZGJau9Ri15rfrBdq', 'user', '2026-03-26', '2026-04-30', '2026-02-19 10:48:54');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `board_members`
--
ALTER TABLE `board_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for dumped tables
--

--
-- Constraints for table `board_members`
--
ALTER TABLE `board_members`
  ADD CONSTRAINT `board_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
