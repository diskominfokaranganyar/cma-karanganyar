-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Feb 2024 pada 08.39
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cmanews`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `posting_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `posting_date`, `updated_date`) VALUES
(1, 'Pendidikan', 'Berita tentang pendidikan', '2024-02-11 08:34:46', NULL),
(2, 'Pariwisata', 'Berita tentang pariwisata', '2024-02-11 08:35:11', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `offline_posts`
--

CREATE TABLE `offline_posts` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `analyze_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `view_counter` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `posted_by` varchar(255) DEFAULT NULL,
  `last_updated_by` varchar(255) DEFAULT NULL,
  `posting_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `offline_posts`
--

INSERT INTO `offline_posts` (`id`, `category_id`, `analyze_id`, `title`, `slug`, `source`, `description`, `view_counter`, `active`, `posted_by`, `last_updated_by`, `posting_date`, `updated_date`) VALUES
(27, 1, 87, 'bupati karanganyar diduga melakukan pelecehan seksual di dalam masjid', 'bupati_karanganyar_diduga_melakukan_pelecehan_seksual_di_dalam_masjid', 'Kompas.com', '<p>parah sekali</p>', NULL, NULL, NULL, NULL, '2024-02-12 13:48:16', NULL),
(28, 2, 88, 'kepala desa melakukan korupsi dengan baik', 'kepala_desa_melakukan_korupsi_dengan_baik', 'kompas', 'alvin baik', NULL, NULL, NULL, NULL, '2024-02-12 13:53:33', NULL),
(29, 1, 89, 'selasa 2', 'selasa_2', 'jawapos', 'mutiah baik hati', NULL, NULL, NULL, NULL, '2024-02-13 00:44:22', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `offline_post_analyze`
--

CREATE TABLE `offline_post_analyze` (
  `id` int(11) NOT NULL,
  `positive` int(11) NOT NULL,
  `negative` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `result` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `offline_post_analyze`
--

INSERT INTO `offline_post_analyze` (`id`, `positive`, `negative`, `total`, `result`, `timestamp`) VALUES
(78, 0, 1, 1, 'Negatif', '2024-02-11 12:14:30'),
(79, 1, 2, 2, 'Negatif', '2024-02-11 12:19:32'),
(80, 1, 7, 7, 'Negatif', '2024-02-11 16:14:42'),
(81, 3, 2, 3, 'Positif', '2024-02-11 16:16:21'),
(85, 3, 5, 2, '', '2024-02-12 12:21:22'),
(86, 4, 4, 3, '', '2024-02-12 12:22:11'),
(87, 0, 2, 0, 'Positif', '2024-02-12 13:48:16'),
(88, 2, 2, 3, '', '2024-02-12 13:53:33'),
(89, 2, 0, 5, '', '2024-02-13 00:44:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `offline_post_images`
--

CREATE TABLE `offline_post_images` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `serial_number` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `posting_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `offline_post_images`
--

INSERT INTO `offline_post_images` (`id`, `post_id`, `name`, `serial_number`, `url`, `posting_date`, `updated_date`) VALUES
(37, 27, 'arpit-bansal-bird-4k_01.png', 1, 'postimages/arpit-bansal-bird-4k_01.png', '2024-02-12 13:48:16', NULL),
(38, 27, 'arpit-bansal-bird-4k_03.png', 2, 'postimages/arpit-bansal-bird-4k_03.png', '2024-02-12 13:48:16', NULL),
(39, 27, 'arpit-bansal-bird-4k_05.png', 3, 'postimages/arpit-bansal-bird-4k_05.png', '2024-02-12 13:48:16', NULL),
(40, 27, 'arpit-bansal-bird-4k_07.png', 4, 'postimages/arpit-bansal-bird-4k_07.png', '2024-02-12 13:48:16', NULL),
(41, 28, 'arpit-bansal-bird-4k_14.png', 1, 'postimages/arpit-bansal-bird-4k_14.png', '2024-02-12 13:53:33', NULL),
(42, 29, 'arpit-bansal-bird-4k_01.png', 1, 'postimages/arpit-bansal-bird-4k_01.png', '2024-02-13 00:44:22', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbladmin`
--

CREATE TABLE `tbladmin` (
  `id` int(11) NOT NULL,
  `AdminUserName` varchar(255) DEFAULT NULL,
  `AdminPassword` varchar(255) DEFAULT NULL,
  `AdminEmailId` varchar(255) DEFAULT NULL,
  `userType` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbladmin`
--

INSERT INTO `tbladmin` (`id`, `AdminUserName`, `AdminPassword`, `AdminEmailId`, `userType`) VALUES
(1, 'admin', 'f925916e2754e5e03f75dd58a5733251', 'phpgurukulofficial@gmail.com', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tblcategory`
--

CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL,
  `CategoryName` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Description` mediumtext CHARACTER SET latin1 DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `Is_Active` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `CategoryName`, `Description`, `PostingDate`, `UpdationDate`, `Is_Active`) VALUES
(1, 'Pendidikan', 'Berita tentang pendidikan', '2024-02-01 02:51:31', '2024-02-01 03:51:44', 1),
(2, 'Pariwisata', 'Berita tentang pariwisata', '2024-02-01 02:52:09', NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tblposts_offline`
--

CREATE TABLE `tblposts_offline` (
  `id` int(11) NOT NULL,
  `id_analyze` int(11) NOT NULL,
  `PostTitle` longtext DEFAULT NULL,
  `CategoryId` int(11) DEFAULT NULL,
  `PostDetails` longtext CHARACTER SET utf8 DEFAULT NULL,
  `PostAnalyze` varchar(255) NOT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `Is_Active` int(1) DEFAULT NULL,
  `PostUrl` mediumtext DEFAULT NULL,
  `PostImage` varchar(255) DEFAULT NULL,
  `viewCounter` int(11) DEFAULT NULL,
  `postedBy` varchar(255) DEFAULT NULL,
  `lastUpdatedBy` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `offline_posts`
--
ALTER TABLE `offline_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`,`analyze_id`),
  ADD KEY `analyze_id` (`analyze_id`);

--
-- Indeks untuk tabel `offline_post_analyze`
--
ALTER TABLE `offline_post_analyze`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indeks untuk tabel `offline_post_images`
--
ALTER TABLE `offline_post_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indeks untuk tabel `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `AdminUserName` (`AdminUserName`);

--
-- Indeks untuk tabel `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tblposts_offline`
--
ALTER TABLE `tblposts_offline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `postcatid` (`CategoryId`),
  ADD KEY `subadmin` (`postedBy`),
  ADD KEY `id_analyze` (`id_analyze`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `offline_posts`
--
ALTER TABLE `offline_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `offline_post_analyze`
--
ALTER TABLE `offline_post_analyze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT untuk tabel `offline_post_images`
--
ALTER TABLE `offline_post_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tblposts_offline`
--
ALTER TABLE `tblposts_offline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `offline_posts`
--
ALTER TABLE `offline_posts`
  ADD CONSTRAINT `offline_posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offline_posts_ibfk_2` FOREIGN KEY (`analyze_id`) REFERENCES `offline_post_analyze` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `offline_post_images`
--
ALTER TABLE `offline_post_images`
  ADD CONSTRAINT `offline_post_images_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `offline_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tblposts_offline`
--
ALTER TABLE `tblposts_offline`
  ADD CONSTRAINT `postcatid` FOREIGN KEY (`CategoryId`) REFERENCES `tblcategory` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `subadmin` FOREIGN KEY (`postedBy`) REFERENCES `tbladmin` (`AdminUserName`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tblposts_offline_ibfk_1` FOREIGN KEY (`id_analyze`) REFERENCES `offline_post_analyze` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
