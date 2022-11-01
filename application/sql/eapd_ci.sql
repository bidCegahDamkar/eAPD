-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 26, 2022 at 07:54 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eapd_ci`
--

-- --------------------------------------------------------

--
-- Table structure for table `apd`
--

CREATE TABLE `apd` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mj_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `mapd_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'refer ke tabel master_apd',
  `mkp_id` int(11) NOT NULL COMMENT 'Refer ke tabel master_keberadaan',
  `petugas_id` int(11) NOT NULL COMMENT 'refer ke table users',
  `kondisi_id` tinyint(3) DEFAULT NULL COMMENT 'refer ke table master kondisi dan master jenis kondisi',
  `ukuran` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_apd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_urut` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `periode_input` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `progress` tinyint(2) NOT NULL COMMENT 'refer ke master progres status',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `verified_by` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `id_pemverifikasi` int(10) UNSIGNED DEFAULT NULL COMMENT 'refer ke table users.id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `subjudul` varchar(255) NOT NULL,
  `isi` longtext NOT NULL,
  `picture` varchar(255) NOT NULL,
  `create_date` date NOT NULL,
  `edited_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lapor_sewaktu`
--

CREATE TABLE `lapor_sewaktu` (
  `id` int(10) UNSIGNED NOT NULL,
  `petugas_id` int(11) NOT NULL,
  `jenis_laporan` tinyint(1) NOT NULL COMMENT '1. rusak\r\n2. hilang',
  `apd_id` int(11) NOT NULL COMMENT 'refer ke tabel apd',
  `tgl_kej` date DEFAULT NULL,
  `deskripsi_laporan` text DEFAULT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`history`)),
  `progress` int(11) DEFAULT NULL,
  `admin_respon` varchar(255) DEFAULT NULL,
  `verified_by` varchar(150) DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `master_apd`
--

CREATE TABLE `master_apd` (
  `id_ma` int(11) UNSIGNED NOT NULL,
  `mj_id` int(11) NOT NULL COMMENT 'refer ke tabel master jenis APD',
  `mm_id` int(11) DEFAULT NULL COMMENT 'refer ke tabel master_merk',
  `tahun` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tahun pengadaan',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_mapd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_seri` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_controller`
--

CREATE TABLE `master_controller` (
  `id` int(10) UNSIGNED NOT NULL,
  `deskripsi` varchar(150) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL,
  `config` varchar(150) DEFAULT NULL,
  `level` tinyint(3) UNSIGNED DEFAULT NULL,
  `role_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_dinas`
--

CREATE TABLE `master_dinas` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode` tinyint(1) DEFAULT NULL,
  `dinas` varchar(150) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `jml_pns` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_pjlp` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_input` mediumint(8) UNSIGNED DEFAULT NULL,
  `jml_verif` mediumint(8) UNSIGNED DEFAULT NULL,
  `jml_ditolak` mediumint(8) UNSIGNED DEFAULT NULL,
  `chart_input_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chart_input_APD`)),
  `chart_verif_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chart_verif_APD`)),
  `KIB_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`KIB_APD`)),
  `tgl_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_eselon`
--

CREATE TABLE `master_eselon` (
  `id` int(10) UNSIGNED NOT NULL,
  `NIP` varchar(150) DEFAULT NULL,
  `NRK` varchar(150) DEFAULT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `kode_panggil` varchar(50) DEFAULT NULL,
  `mc_id` tinyint(5) NOT NULL COMMENT 'refer ke master controller',
  `eselon` varchar(50) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `macth` tinyint(1) DEFAULT NULL,
  `is_taken` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_group_piket`
--

CREATE TABLE `master_group_piket` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_piket` varchar(2) NOT NULL,
  `deskripsi_group` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_jabatan`
--

CREATE TABLE `master_jabatan` (
  `id_mj` int(10) UNSIGNED NOT NULL,
  `nama_jabatan` varchar(255) DEFAULT NULL,
  `mc_id` tinyint(5) NOT NULL COMMENT 'refer ke master controller',
  `kode_panggil` varchar(50) DEFAULT NULL,
  `eselon` varchar(50) DEFAULT NULL,
  `is_eselon` tinyint(1) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `macth` tinyint(1) DEFAULT NULL,
  `is_taken` tinyint(1) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_bidang` tinyint(1) NOT NULL DEFAULT 0,
  `is_sektor` tinyint(1) NOT NULL DEFAULT 0,
  `is_plt` tinyint(1) NOT NULL DEFAULT 0,
  `plt_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'refer ke table users.id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_jenis_apd`
--

CREATE TABLE `master_jenis_apd` (
  `id_mj` bigint(20) UNSIGNED NOT NULL,
  `jenis_apd` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_barang` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mtu_id` int(11) NOT NULL COMMENT 'refer ke tabel master_tipe_ukuran',
  `role_id` int(11) DEFAULT NULL COMMENT 'refer ke tabel groups',
  `picture` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akronim` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_jenis_kondisi`
--

CREATE TABLE `master_jenis_kondisi` (
  `id_mjt` bigint(20) UNSIGNED NOT NULL,
  `mj_id` int(11) DEFAULT NULL,
  `mk_id` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_keberadaan`
--

CREATE TABLE `master_keberadaan` (
  `id_mkp` int(10) UNSIGNED NOT NULL,
  `keberadaan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_kondisi`
--

CREATE TABLE `master_kondisi` (
  `id_mk` bigint(20) UNSIGNED NOT NULL,
  `nama_kondisi` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `wearable` tinyint(1) NOT NULL DEFAULT 0,
  `kategori` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_merk`
--

CREATE TABLE `master_merk` (
  `id_mm` int(10) UNSIGNED NOT NULL,
  `merk` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_pos`
--

CREATE TABLE `master_pos` (
  `id_mp` int(11) NOT NULL,
  `kode_pos` varchar(11) NOT NULL,
  `kode_sektor` varchar(11) NOT NULL,
  `kode_wilayah` varchar(11) NOT NULL,
  `nama_pos` varchar(150) NOT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `jml_pns` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_pjlp` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_input` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_verif` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_ditolak` smallint(5) UNSIGNED DEFAULT NULL,
  `chart_input_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chart_input_APD`)),
  `chart_verif_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chart_verif_APD`)),
  `KIB_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`KIB_APD`)),
  `tgl_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_progress_sewaktu`
--

CREATE TABLE `master_progress_sewaktu` (
  `id` int(10) UNSIGNED NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `next_step` varchar(255) DEFAULT NULL,
  `icons` varchar(255) DEFAULT NULL,
  `color` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_progress_status`
--

CREATE TABLE `master_progress_status` (
  `id_mps` int(10) UNSIGNED NOT NULL,
  `deskripsi` varchar(150) NOT NULL,
  `message` varchar(150) NOT NULL,
  `button` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'button prop' CHECK (json_valid(`button`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_sektor`
--

CREATE TABLE `master_sektor` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(150) NOT NULL,
  `sektor` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `jml_pns` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_pjlp` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_input` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_verif` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_ditolak` smallint(5) UNSIGNED DEFAULT NULL,
  `chart_input_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chart_input_APD`)),
  `chart_verif_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chart_verif_APD`)),
  `KIB_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`KIB_APD`)),
  `tgl_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_state`
--

CREATE TABLE `master_state` (
  `id` int(11) NOT NULL,
  `tipe` varchar(100) DEFAULT NULL,
  `is_open` tinyint(1) NOT NULL,
  `periode_input` varchar(100) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `num_update` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'jumlah update data rekap pada saat sistem ditutup, maximal 1kali'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_status`
--

CREATE TABLE `master_status` (
  `id_stat` int(11) NOT NULL,
  `status` varchar(150) DEFAULT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_sudin`
--

CREATE TABLE `master_sudin` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(5) NOT NULL,
  `sudin` varchar(150) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `jml_pns` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_pjlp` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_input` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_verif` smallint(5) UNSIGNED DEFAULT NULL,
  `jml_ditolak` smallint(5) UNSIGNED DEFAULT NULL,
  `chart_input_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chart_input_APD`)),
  `chart_verif_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`chart_verif_APD`)),
  `KIB_APD` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`KIB_APD`)),
  `tgl_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_tipe_pegawai`
--

CREATE TABLE `master_tipe_pegawai` (
  `id` int(10) UNSIGNED NOT NULL,
  `deskripsi` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_tipe_ukuran`
--

CREATE TABLE `master_tipe_ukuran` (
  `id_mtu` bigint(20) UNSIGNED NOT NULL,
  `daftar_ukuran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `renkin`
--

CREATE TABLE `renkin` (
  `id` int(11) NOT NULL,
  `mc_id` int(11) NOT NULL COMMENT 'refer to master controller',
  `sasaran` varchar(255) NOT NULL,
  `indikator` varchar(255) NOT NULL,
  `target` int(11) NOT NULL,
  `satuan` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `report_pdf`
--

CREATE TABLE `report_pdf` (
  `id` int(11) NOT NULL,
  `kode_pos` varchar(100) DEFAULT NULL,
  `nama_laporan` varchar(150) NOT NULL,
  `periode` varchar(150) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `create_at` date DEFAULT NULL,
  `update_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pegawai`
--

CREATE TABLE `tbl_pegawai` (
  `NIP` varchar(20) NOT NULL,
  `NRK` varchar(20) NOT NULL,
  `phl` tinyint(1) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `penugasan` varchar(10) NOT NULL,
  `id_radio` varchar(20) NOT NULL,
  `kode_panggil` varchar(20) NOT NULL,
  `kode_wilayah` varchar(10) NOT NULL,
  `kode_sektor` varchar(10) NOT NULL,
  `kode_pos` varchar(10) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `keahlian` text NOT NULL,
  `kendaraan` varchar(20) DEFAULT NULL,
  `group_piket` varchar(1) NOT NULL,
  `penanggung_jawab` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `create_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `NRK` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `NIP` varchar(100) DEFAULT NULL,
  `jabatan_id` tinyint(3) DEFAULT NULL COMMENT 'refer ke tabel master_jabatan',
  `active` tinyint(1) UNSIGNED DEFAULT 1,
  `photo` varchar(255) DEFAULT NULL,
  `kode_pos_id` int(11) DEFAULT NULL COMMENT 'refer ke tabel master pos',
  `no_telepon` varchar(50) DEFAULT NULL,
  `group_piket_id` tinyint(2) DEFAULT NULL COMMENT 'refer ke table master group piket',
  `status_id` tinyint(3) DEFAULT NULL COMMENT 'refer ke tabel master_status',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `is_macth` tinyint(1) NOT NULL DEFAULT 0,
  `json_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`json_data`)),
  `jml_input_APD` tinyint(3) UNSIGNED DEFAULT 0,
  `jml_tobe_verified` tinyint(3) UNSIGNED DEFAULT 0 COMMENT 'jumlah apd yang harus diverifikasi',
  `persen_inputAPD` varchar(6) DEFAULT NULL,
  `persen_APDterverif` varchar(6) DEFAULT NULL,
  `jml_ditolak` tinyint(3) UNSIGNED DEFAULT 0 COMMENT 'Jumlah APD yang ditolak'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_ukuran`
--

CREATE TABLE `users_ukuran` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL COMMENT 'refer ke tabel users',
  `uk_kaos` varchar(5) DEFAULT NULL,
  `uk_baju_dinas` varchar(5) DEFAULT NULL,
  `uk_celana_dinas` varchar(5) DEFAULT NULL,
  `uk_sepatu_dinas` varchar(5) DEFAULT NULL,
  `ukuran_baret` tinyint(3) UNSIGNED DEFAULT NULL,
  `uk_fire_jaket` varchar(5) DEFAULT NULL,
  `uk_sepatu_rescue_boots` tinyint(3) UNSIGNED DEFAULT NULL,
  `ukuran_sepatu_fire_boots` tinyint(3) UNSIGNED DEFAULT NULL,
  `uk_gloves` varchar(5) DEFAULT NULL,
  `uk_jumpsuit` varchar(5) DEFAULT NULL,
  `waktu` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_staf`
--

CREATE TABLE `user_staf` (
  `id` int(10) UNSIGNED NOT NULL,
  `duplicate` tinyint(1) NOT NULL DEFAULT 0,
  `sukses_update` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `exist_active` tinyint(1) NOT NULL DEFAULT 0,
  `exist_inactive` tinyint(1) NOT NULL DEFAULT 0,
  `exist_old` tinyint(1) NOT NULL DEFAULT 0,
  `nama` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `nrk` varchar(10) DEFAULT NULL,
  `pos_id` int(10) UNSIGNED DEFAULT NULL,
  `mj_id` int(10) UNSIGNED DEFAULT NULL,
  `bidang` varchar(150) DEFAULT NULL,
  `bidang_id` varchar(10) DEFAULT NULL,
  `seksi` varchar(150) DEFAULT NULL,
  `seksi_id` varchar(10) DEFAULT NULL,
  `jfu` varchar(200) DEFAULT NULL,
  `jabfung` varchar(200) DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `kategori` varchar(20) DEFAULT NULL,
  `penugasan` varchar(200) DEFAULT NULL,
  `eselon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apd`
--
ALTER TABLE `apd`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mj_id` (`mj_id`,`mapd_id`,`mkp_id`,`petugas_id`,`kondisi_id`,`progress`),
  ADD KEY `periode_input` (`periode_input`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lapor_sewaktu`
--
ALTER TABLE `lapor_sewaktu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_apd`
--
ALTER TABLE `master_apd`
  ADD PRIMARY KEY (`id_ma`),
  ADD KEY `id_ma` (`id_ma`,`mj_id`,`mm_id`);

--
-- Indexes for table `master_controller`
--
ALTER TABLE `master_controller`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `master_dinas`
--
ALTER TABLE `master_dinas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_eselon`
--
ALTER TABLE `master_eselon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_group_piket`
--
ALTER TABLE `master_group_piket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_jabatan`
--
ALTER TABLE `master_jabatan`
  ADD PRIMARY KEY (`id_mj`),
  ADD KEY `id_mj` (`id_mj`,`mc_id`);

--
-- Indexes for table `master_jenis_apd`
--
ALTER TABLE `master_jenis_apd`
  ADD PRIMARY KEY (`id_mj`),
  ADD KEY `id_mj` (`id_mj`,`mtu_id`,`role_id`);

--
-- Indexes for table `master_jenis_kondisi`
--
ALTER TABLE `master_jenis_kondisi`
  ADD PRIMARY KEY (`id_mjt`),
  ADD KEY `id_mjt` (`id_mjt`,`mj_id`,`mk_id`);

--
-- Indexes for table `master_keberadaan`
--
ALTER TABLE `master_keberadaan`
  ADD PRIMARY KEY (`id_mkp`);

--
-- Indexes for table `master_kondisi`
--
ALTER TABLE `master_kondisi`
  ADD PRIMARY KEY (`id_mk`);

--
-- Indexes for table `master_merk`
--
ALTER TABLE `master_merk`
  ADD PRIMARY KEY (`id_mm`);

--
-- Indexes for table `master_pos`
--
ALTER TABLE `master_pos`
  ADD PRIMARY KEY (`id_mp`),
  ADD KEY `Index_pos` (`id_mp`,`kode_pos`) USING BTREE,
  ADD KEY `deleted` (`deleted`);

--
-- Indexes for table `master_progress_sewaktu`
--
ALTER TABLE `master_progress_sewaktu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_progress_status`
--
ALTER TABLE `master_progress_status`
  ADD PRIMARY KEY (`id_mps`);

--
-- Indexes for table `master_sektor`
--
ALTER TABLE `master_sektor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deleted` (`deleted`);

--
-- Indexes for table `master_state`
--
ALTER TABLE `master_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_status`
--
ALTER TABLE `master_status`
  ADD PRIMARY KEY (`id_stat`);

--
-- Indexes for table `master_sudin`
--
ALTER TABLE `master_sudin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_tipe_pegawai`
--
ALTER TABLE `master_tipe_pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_tipe_ukuran`
--
ALTER TABLE `master_tipe_ukuran`
  ADD PRIMARY KEY (`id_mtu`);

--
-- Indexes for table `renkin`
--
ALTER TABLE `renkin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mc_id` (`mc_id`);

--
-- Indexes for table `report_pdf`
--
ALTER TABLE `report_pdf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kode_pos` (`kode_pos`);

--
-- Indexes for table `tbl_pegawai`
--
ALTER TABLE `tbl_pegawai`
  ADD PRIMARY KEY (`NIP`),
  ADD KEY `index_pegawai` (`NIP`,`NRK`,`kode_panggil`,`kode_wilayah`,`kode_sektor`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `NRK` (`NRK`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`),
  ADD KEY `id` (`id`,`jabatan_id`,`status_id`,`active`),
  ADD KEY `kode_pos_id` (`kode_pos_id`),
  ADD KEY `group_piket_id` (`group_piket_id`),
  ADD KEY `deleted` (`deleted`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- Indexes for table `users_ukuran`
--
ALTER TABLE `users_ukuran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`users_id`);

--
-- Indexes for table `user_staf`
--
ALTER TABLE `user_staf`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apd`
--
ALTER TABLE `apd`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lapor_sewaktu`
--
ALTER TABLE `lapor_sewaktu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_apd`
--
ALTER TABLE `master_apd`
  MODIFY `id_ma` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_controller`
--
ALTER TABLE `master_controller`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_dinas`
--
ALTER TABLE `master_dinas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_eselon`
--
ALTER TABLE `master_eselon`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_group_piket`
--
ALTER TABLE `master_group_piket`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_jabatan`
--
ALTER TABLE `master_jabatan`
  MODIFY `id_mj` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_jenis_apd`
--
ALTER TABLE `master_jenis_apd`
  MODIFY `id_mj` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_jenis_kondisi`
--
ALTER TABLE `master_jenis_kondisi`
  MODIFY `id_mjt` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_keberadaan`
--
ALTER TABLE `master_keberadaan`
  MODIFY `id_mkp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_kondisi`
--
ALTER TABLE `master_kondisi`
  MODIFY `id_mk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_merk`
--
ALTER TABLE `master_merk`
  MODIFY `id_mm` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_pos`
--
ALTER TABLE `master_pos`
  MODIFY `id_mp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_progress_sewaktu`
--
ALTER TABLE `master_progress_sewaktu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_sektor`
--
ALTER TABLE `master_sektor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_state`
--
ALTER TABLE `master_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_status`
--
ALTER TABLE `master_status`
  MODIFY `id_stat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_sudin`
--
ALTER TABLE `master_sudin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_tipe_pegawai`
--
ALTER TABLE `master_tipe_pegawai`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_tipe_ukuran`
--
ALTER TABLE `master_tipe_ukuran`
  MODIFY `id_mtu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `renkin`
--
ALTER TABLE `renkin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_pdf`
--
ALTER TABLE `report_pdf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_ukuran`
--
ALTER TABLE `users_ukuran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
