-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 04:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meetings_reminder`
--

-- --------------------------------------------------------

--
-- Table structure for table `schedule_list`
--

CREATE TABLE `schedule_list` (
  `id` int(30) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_list`
--

INSERT INTO `schedule_list` (`id`, `title`, `description`, `start_datetime`, `end_datetime`, `team_id`, `user_id`, `manager_id`) VALUES
(1, 'Sample 101', 'This is a sample schedule only.', '2022-01-10 10:30:00', '2022-01-11 18:00:00', 0, 0, 0),
(2, 'Sample 102', 'Sample Description 102', '2022-01-08 09:30:00', '2022-01-08 11:30:00', 0, 0, 0),
(4, 'Sample 102', 'Sample Description', '2022-01-12 14:00:00', '2022-01-12 17:00:00', 0, 0, 0),
(20, 'sdada', 'asdada', '2024-12-20 06:17:00', '2024-12-21 06:17:00', 0, 0, 0),
(21, 'pbl', 'mini', '2024-12-17 10:53:00', '2024-12-19 10:53:00', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `name`, `created_by`, `created_at`) VALUES
(5, 'Kelo 6', 18, '2024-12-18 19:35:20'),
(6, 'Kell 6', 38, '2024-12-19 03:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('member','manager') NOT NULL DEFAULT 'member',
  `status` enum('pending','active') NOT NULL DEFAULT 'pending',
  `invited_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `team_id`, `user_id`, `role`, `status`, `invited_at`) VALUES
(23, 5, 35, 'member', 'active', '2024-12-18 19:49:55'),
(48, 5, 36, 'member', 'active', '2024-12-18 21:08:04'),
(50, 5, 37, 'member', 'active', '2024-12-18 21:49:50'),
(53, 6, 37, 'member', 'active', '2024-12-19 03:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Member','Manager') NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `display_username` varchar(255) DEFAULT NULL,
  `is_first_login` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `profile_image`, `display_username`, `is_first_login`) VALUES
(1, 'alam', '1234', 'Member', 'uploads/4342201041_BryanAdityaDachi.png', NULL, 1),
(2, 'DWIKY', '$2y$10$JKneW1Or027foYuZVVlNyO0ClxycTMgIrQp3k78WnD16zqzOwMeCa', 'Member', 'uploads/Screenshot 2024-12-06 091022.png', 'admin', 0),
(4, 'dwiky21', '$2y$10$NWHfFd0fYAcPra67VqIxr.9IejCqreD4qBvP162ASN7mi70fQfuay', 'Member', NULL, NULL, 1),
(5, 'ALAMSAH', '$2y$10$oAontXGqb1QCK0PGqi0bcOwivi/GBmVrTgII5P6N/eJfG5P/87p2.', 'Member', 'uploads/Screenshot 2024-09-27 145404.png', NULL, 1),
(6, 'kiki12', '$2y$10$nAZfNbG1kcsLDmjCWS7vxeovIY0ZqGot16AFnf0k/SP5ASS9XB7Hi', 'Member', NULL, 'kiki12', 1),
(8, 'kiki', '$2y$10$eFRHOwZ2g7jjb3Flpwj4xu2pB0oQdPfcvTrWyaCGLcL1seKANc5OK', 'Member', NULL, 'kiki', 1),
(9, 'remon', '$2y$10$wgXV88lb0AVBuwRWgp5g0Olmy/.qC0J1qbzHdoWhm78lPuenwkIey', 'Member', 'uploads/Screenshot (101).png', 'Raymond', 1),
(10, 'Putra', '$2y$10$w6jbCD8g3mcvLYyPIAKAieUrFPc5015BAHg/01hQqoff/k2F0VCF6', 'Member', 'uploads/Screenshot (100).png', 'Dwiky Putra', 1),
(11, 'Rio', '$2y$10$hvt7iYA/HgHbinaBzkgj7.6UBi6qN./G0YHrWKAtGqumBMQveMK72', 'Member', 'uploads/Screenshot (101).png', 'RIO', 0),
(12, 'bastian', '$2y$10$KD9xZ5.RjzaiB/WeOAc0me93RAn1WZOpN8V6JvS8R.przqN.UfiXC', 'Member', 'uploads/Screenshot 2024-12-03 234923.png', 'BASSTIAN', 0),
(13, 'rafif', '$2y$10$N5SIhIPpttiLGfMnbGc0l.k7OzbVnGoEpBAXjPpDqWnQFGwPEXjYq', 'Member', 'uploads/Screenshot (101).png', 'RAPIP', 0),
(14, 'dwikyputra', '$2y$10$k.j3PQKKw.RSvVwVH262I.j7u0xvpnL.pts0cVVP23GJO/Znyta0i', 'Member', NULL, 'dwikyputra', 1),
(15, 'dwikyy', '$2y$10$ECL0XTtjlEiZFfnzq0xeOu8FnM8BACS53tkwFj8rSxtQRF9tpaV9q', 'Manager', 'uploads/Screenshot (502).png', 'DWIKYPUTRA', 0),
(16, 'alif', '$2y$10$ilgQiBP7cRnWFUeFPzZ1hOo.BV.fJEypTg3uv2S/a.NU623PJUiDq', 'Manager', 'uploads/Screenshot (100).png', 'ALIFF', 0),
(17, 'marwan', '$2y$10$WzDbCSmf/TebCI5VuXjY6ecrrJ11qbeH01YrsBaZ7HBVv5pKIacXa', 'Member', 'uploads/Screenshot (101).png', 'MARWANNNN', 0),
(18, 'nopal', '$2y$10$Uo5qzBN.nLMNoTlVb6VhHuN7/QWbqywZOuj61H4tk.sfHeLSxIfzW', 'Manager', 'uploads/WIN_20241204_22_25_11_Pro.jpg', 'NOPALL', 0),
(19, 'sahrul', '$2y$10$IBXTJQkbpBMIhao4BMyEdeO6zYozJ2hiM9B9Xw0fPm1ZFldDkYnmS', 'Member', 'uploads/Screenshot (100).png', 'SYAHRUL', 0),
(20, 'rioputra', '$2y$10$//O.pkcWfa5HHHWMGS2vV.YNuVTr6Lin/DjYlrT1Jh9BNwLabeNBe', 'Manager', 'uploads/Screenshot (108).png', 'RIOOO', 0),
(21, 'rian', '$2y$10$WM8OeHQWVU51xIR3qQ/p6uRHJTJbpkuUl.RaHYFxYLczJe.orkFaK', 'Member', 'uploads/Screenshot (107).png', 'RIAN', 0),
(22, 'bastiann', '$2y$10$mkZC5LB3RnUYx.3h3hQ5Eeh6pibsZfILlVV6hTd0yGLBPuTg7pyGm', 'Member', 'uploads/Screenshot 2024-12-03 234923.png', 'BASIAN', 0),
(23, 'adrian', '$2y$10$lEjObQuDb83WbOUu.rRGQesOuDI3UV9Hj6OqlJvXloZmQ72o54O2i', 'Member', 'uploads/Screenshot (101).png', 'adrian', 0),
(24, 'dafa', '$2y$10$WdhMGglk6IfVL/UsCpahP.9GgdULD8cHECVvoDePiSlVm61NZey1.', 'Member', 'uploads/Screenshot (101).png', 'dafa', 0),
(25, 'Zahri', '$2y$10$MxObJ2kpXXmJzJGEyq8PSe8xVXGAzoOEV9NML5axGLM8Al58Q4Fs.', 'Member', 'uploads/Screenshot 2024-12-03 220334.png', 'Zahri', 0),
(26, 'daren', '$2y$10$ABGWiLB8QxDtJc84rHh36.dc1E7C.ODYGC8seUsaH.e6t46.5Weda', 'Manager', 'uploads/Screenshot 2024-12-03 234923.png', 'daren', 0),
(27, 'admint', '$2y$10$U3MHK8bs//UJgQ3QKDke7.gXp8F4KLohZ4iZ1N.3gx.B8WAL0ar.C', 'Member', NULL, 'admint', 1),
(28, 'admintt', '$2y$10$j3.KgcwyEDAeRvEKx7sRS.8F.2BWyUGX/ecoNGShBPFWmMz9TWTBy', 'Manager', 'uploads/Screenshot (101).png', 'ADMIN', 0),
(29, 'member', '$2y$10$ANsxDkCPMkiCwT2D0rvSQO8lL3De4ySLI6NrzXh5JICiM2mk9ZiuC', 'Member', 'uploads/Screenshot 2024-12-09 201244.png', 'DWIKY', 0),
(30, 'adminttt', '$2y$10$YTn5jQahZI3.i8guc93pKObC2HJHYRsfLruC1w3oWR7Y.FTyKixpi', 'Manager', 'uploads/Screenshot (569).png', 'IHIRR', 0),
(31, 'Moezza', '$2y$10$klotmcmVE07/hJi0HaRIhOw9BA5LOAriVuOCmlA8nZzKU7BNMnSy.', 'Manager', 'uploads/Fayza Jannatul Tasya.png', 'KOCHENG', 0),
(32, 'manager1', 'password123', 'Manager', NULL, 'Manager 1', 0),
(33, 'member1', 'password123', 'Member', NULL, 'Member 1', 0),
(34, 'member2', 'password123', 'Member', NULL, 'Member 2', 0),
(35, 'asw', '$2y$10$ETDGWhISLIdZvzWKSXYkhuAhbQO3v1n4U7LY/kLotWD2wkmJPd2Oa', 'Member', 'uploads/4342201041_BryanAdityaDachi.png', 'asw', 0),
(36, 'last', '$2y$10$GwaAvUO3AO2UsQuARO8YsuvZMAiNoXHBMsTtRsAXhaTwtgOy6Ia/e', 'Member', 'uploads/Screenshot (107).png', 'last', 0),
(37, 'lasya', '$2y$10$fwqMWIAIbx/Jie9AsO7wIOOjlsvybtcp2QqCcWYJkVcmroa5ue7le', 'Member', 'uploads/Screenshot (101).png', 'lasya', 0),
(38, 'terakhir', '$2y$10$dDIDtRXOsdEqd6W05byXy.ALhTD2Gd9R/ADgoNdkxLja4OF00Qlya', 'Manager', 'uploads/Screenshot (101).png', 'terakhir', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `schedule_list`
--
ALTER TABLE `schedule_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_id` (`team_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `schedule_list`
--
ALTER TABLE `schedule_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
