-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: localhost:3306
-- Χρόνος δημιουργίας: 12 Σεπ 2023 στις 19:45:47
-- Έκδοση διακομιστή: 10.3.39-MariaDB-cll-lve
-- Έκδοση PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `koyinta588443_projects`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `biblionetScript`
--

CREATE TABLE IF NOT EXISTS `biblionetScript` (
  `id` int(11) NOT NULL,
  `lastDate` datetime NOT NULL,
  `InsertedMonth` int(11) NOT NULL,
  `InsertedYear` int(11) NOT NULL,
  `InsertedPage` int(11) NOT NULL,
  `InsertedAuthors` int(11) NOT NULL,
  `InsertedCategories` int(11) NOT NULL,
  `InsertedPublishers` int(11) NOT NULL,
  `InsertedBooks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `user`
--

INSERT INTO `user` (`id`, `fullname`, `username`, `password`) VALUES
(2, 'Datatex.gr', 'nickpsal', '$2y$10$eFdfMVa4LD7RB61TYNU/ee/zEQwEdIo/ylZ4FOPlAodJPx9lBaLcu');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `biblionetScript`
--
ALTER TABLE `biblionetScript`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `biblionetScript`
--
ALTER TABLE `biblionetScript`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT για πίνακα `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
