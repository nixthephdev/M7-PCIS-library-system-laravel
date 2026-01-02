-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2026 at 12:12 PM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `publication_year` year(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `publisher`, `publication_year`, `created_at`, `updated_at`) VALUES
(1, 'Noli Me Tángere', 'José Rizal', '9791195163861', 'Unknown', '2026', '2026-01-01 19:31:39', '2026-01-02 01:45:40');

-- --------------------------------------------------------

--
-- Table structure for table `book_copies`
--

CREATE TABLE `book_copies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `book_id` bigint(20) UNSIGNED NOT NULL,
  `accession_number` varchar(255) NOT NULL,
  `status` enum('available','borrowed','lost') NOT NULL DEFAULT 'available',
  `is_purchased` tinyint(1) NOT NULL DEFAULT 1,
  `purchase_price` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_copies`
--

INSERT INTO `book_copies` (`id`, `book_id`, `accession_number`, `status`, `is_purchased`, `purchase_price`, `created_at`, `updated_at`) VALUES
(1, 1, 'BK-374548', 'available', 1, 0.00, '2026-01-01 19:31:39', '2026-01-02 03:06:40'),
(2, 1, 'BK-999919', 'available', 1, 0.00, '2026-01-01 19:31:39', '2026-01-02 03:03:45'),
(3, 1, 'BK-372783', 'available', 1, 0.00, '2026-01-01 19:31:39', '2026-01-02 03:06:50'),
(4, 1, 'BK-161976', 'borrowed', 1, 0.00, '2026-01-01 19:31:39', '2026-01-02 03:07:30'),
(5, 1, 'BK-694201', 'borrowed', 1, 0.00, '2026-01-01 19:31:39', '2026-01-02 03:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_transactions`
--

CREATE TABLE `borrow_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `book_copy_id` bigint(20) UNSIGNED NOT NULL,
  `borrowed_at` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `returned_at` datetime DEFAULT NULL,
  `fine_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `borrow_transactions`
--

INSERT INTO `borrow_transactions` (`id`, `user_id`, `book_copy_id`, `borrowed_at`, `due_date`, `returned_at`, `fine_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-01-02 03:35:09', '2026-01-09 03:35:09', '2026-01-02 03:36:53', 0.00, '2026-01-01 19:35:09', '2026-01-01 19:36:53'),
(2, 1, 1, '2026-01-02 03:36:56', '2026-01-09 03:36:56', '2026-01-02 07:00:36', 0.00, '2026-01-01 19:36:56', '2026-01-01 23:00:36'),
(3, 1, 2, '2026-01-02 05:33:16', '2026-01-09 05:33:16', '2026-01-02 11:02:14', 0.00, '2026-01-01 21:33:16', '2026-01-02 03:02:14'),
(4, 2, 3, '2026-01-02 05:34:30', '2026-01-09 05:34:30', '2026-01-02 05:34:51', 0.00, '2026-01-01 21:34:30', '2026-01-01 21:34:51'),
(5, 2, 4, '2026-01-02 06:56:22', '2026-01-09 06:56:22', '2026-01-02 11:02:18', 0.00, '2026-01-01 22:56:22', '2026-01-02 03:02:18'),
(6, 2, 5, '2026-01-02 06:56:46', '2026-01-09 06:56:46', '2026-01-02 11:02:22', 0.00, '2026-01-01 22:56:46', '2026-01-02 03:02:22'),
(7, 1, 3, '2026-01-02 06:57:09', '2026-01-09 06:57:09', '2026-01-02 11:02:35', 0.00, '2026-01-01 22:57:09', '2026-01-02 03:02:35'),
(8, 6, 1, '2026-01-02 10:14:54', '2026-01-09 10:14:54', '2026-01-02 11:02:31', 0.00, '2026-01-02 02:14:54', '2026-01-02 03:02:31'),
(9, 8, 3, '2026-01-02 11:03:18', '2026-01-09 11:03:18', '2026-01-02 11:06:50', 0.00, '2026-01-02 03:03:18', '2026-01-02 03:06:50'),
(10, 8, 2, '2026-01-02 11:03:37', '2026-01-09 11:03:37', '2026-01-02 11:03:45', 0.00, '2026-01-02 03:03:37', '2026-01-02 03:03:45'),
(11, 9, 1, '2026-01-02 11:06:13', '2026-01-09 11:06:13', '2026-01-02 11:06:40', 0.00, '2026-01-02 03:06:13', '2026-01-02 03:06:40'),
(12, 9, 5, '2026-01-02 11:07:09', '2026-01-09 11:07:09', NULL, 0.00, '2026-01-02 03:07:09', '2026-01-02 03:07:09'),
(13, 8, 4, '2026-01-02 11:07:30', '2026-01-09 11:07:30', NULL, 0.00, '2026-01-02 03:07:30', '2026-01-02 03:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_01_02_031733_create_books_table', 1),
(6, '2026_01_02_031803_create_book_copies_table', 1),
(7, '2026_01_02_031912_create_borrow_transactions_table', 1),
(8, '2026_01_02_070349_add_role_to_users_table', 2),
(9, '2026_01_02_092952_add_avatar_to_users_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'member',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_id`, `name`, `email`, `avatar`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'PCIS00047', 'PCIS Librarian', 'librarian@pcis.edu.ph', 'avatars/nDFSMTXVWCO7DlV7peDOdpTpvPHcLqvwZoWoj2zH.png', 'admin', '2026-01-01 19:29:44', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hf8Ho6MOWjocf8KUwp33RzI0NHXwI14eMzsaYd0lQ4UBKd3KB0F02Uoi3eLL', '2026-01-01 19:29:44', '2026-01-02 02:45:49'),
(2, 'PCIS00059', 'Nikko Calumpiano', 'nickocalumpiano@gmail.com', 'avatars/x9fpNOboB0Tgs131ys3JPbYUAPbVx73g6hsJf6Zr.jpg', 'admin', NULL, '$2y$10$OCntTPkaY9japWEPAcKrkO.iDSc1TbhcQBkTARoy2l03uLClQSW9a', NULL, '2026-01-01 21:33:43', '2026-01-02 02:46:02'),
(4, 'PCIS00002', 'Maylyn Abalos', 'admissions@pcis.edu.ph', 'avatars/4zEVMxXYdZ9BHPfAIbmlxTWjR2Yqxes6Bicyq3R1.png', 'admin', NULL, '$2y$10$R15ypNlPmi9E3KPAh4UQeuwY6n0QPQDL23e1YemGhjd0o6QXd0wxK', NULL, '2026-01-02 02:10:52', '2026-01-02 02:56:11'),
(6, 'PCIS00001', 'Chery May Galido', 'president@pcis.edu.ph', 'avatars/J0Doz0gOWxpuhWuqC9o7hUTtmyE1chfwxr0LWGyD.png', 'admin', NULL, '$2y$10$SPxkHm18VUW8QYS2gTcPUuFWg5UlEMLXF6pOapgUhiCHYIpGmUvtO', NULL, '2026-01-02 02:13:37', '2026-01-02 02:54:59'),
(7, 'PCIS00006', 'Erick Galido', 'IT@pcis.edu.ph', 'avatars/XiVxgsrYerLYQK3POUqE62IpPmFmizYNoN1mLYLE.png', 'admin', NULL, '$2y$10$.0AUDlq/2RkMKNKP0PIRK.9VIpOhoOi9cpfIAVOWkuMMBx/FEIYjG', NULL, '2026-01-02 02:57:08', '2026-01-02 02:57:08'),
(8, 'IB0001', 'Gabriel Gerard Dela Cruz', 'gabrielgerarddelacruz@pcis.edu.ph', NULL, 'member', NULL, '$2y$10$.EQ1AhIvBk1rvQDoZkyhGOkwo6cBLVlKZD9OZnaLSSPj7pjnxIKQC', NULL, '2026-01-02 03:01:07', '2026-01-02 03:01:07'),
(9, 'IB0004', 'Levi Caden Abalos', 'levicadenabalos@pcis.edu.ph', NULL, 'member', NULL, '$2y$10$rnC8A90f9qTcqsMopQcus.RyAuTcVL2Oky9hGF0JaW9ZS/C1XznPG', NULL, '2026-01-02 03:05:30', '2026-01-02 03:05:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `books_isbn_unique` (`isbn`);

--
-- Indexes for table `book_copies`
--
ALTER TABLE `book_copies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `book_copies_accession_number_unique` (`accession_number`),
  ADD KEY `book_copies_book_id_foreign` (`book_id`);

--
-- Indexes for table `borrow_transactions`
--
ALTER TABLE `borrow_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrow_transactions_user_id_foreign` (`user_id`),
  ADD KEY `borrow_transactions_book_copy_id_foreign` (`book_copy_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `book_copies`
--
ALTER TABLE `book_copies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `borrow_transactions`
--
ALTER TABLE `borrow_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_copies`
--
ALTER TABLE `book_copies`
  ADD CONSTRAINT `book_copies_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `borrow_transactions`
--
ALTER TABLE `borrow_transactions`
  ADD CONSTRAINT `borrow_transactions_book_copy_id_foreign` FOREIGN KEY (`book_copy_id`) REFERENCES `book_copies` (`id`),
  ADD CONSTRAINT `borrow_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
