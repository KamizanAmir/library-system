-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2025 at 08:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kvsp_library`
--
DROP DATABASE IF EXISTS `kvsp_library`;

CREATE DATABASE IF NOT EXISTS `kvsp_library`;

USE `kvsp_library`;

-- --------------------------------------------------------
--
-- Table structure for table `books`
--
CREATE TABLE
  `books` (
    `book_id` varchar(20) NOT NULL,
    `title` varchar(255) NOT NULL,
    `category_code` varchar(5) NOT NULL,
    `shelf_location` varchar(50) DEFAULT NULL,
    `is_available` tinyint (1) DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `books`
--
INSERT INTO
  `books` (
    `book_id`,
    `title`,
    `category_code`,
    `shelf_location`,
    `is_available`,
    `created_at`
  )
VALUES
  (
    'ART-001',
    'Asas Seni Lukis',
    'ART',
    'Rak G-01',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'ART-002',
    'Seni Batik Malaysia',
    'ART',
    'Rak G-02',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'ART-003',
    'Teori Muzik Asas',
    'ART',
    'Rak G-03',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'ART-004',
    'Fotografi Digital',
    'ART',
    'Rak G-04',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'ART-005',
    'Sejarah Seni Bina Islam',
    'ART',
    'Rak G-05',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'ART-006',
    'Kaligrafi Khat',
    'ART',
    'Rak G-06',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'ART-007',
    'Design Thinking',
    'ART',
    'Rak G-07',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'ART-008',
    'Seni Arca Moden',
    'ART',
    'Rak G-08',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'ART-009',
    'Graphic Design School',
    'ART',
    'Rak G-09',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'ART-010',
    'Watercolor Painting',
    'ART',
    'Rak G-10',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-001',
    'Salina',
    'FIC',
    'Rak A-01',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-002',
    'Harry Potter dan Batu Hikmat',
    'FIC',
    'Rak A-02',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-003',
    'Tenggelamnya Kapal Van Der Wijck',
    'FIC',
    'Rak A-03',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-004',
    'Ombak Rindu',
    'FIC',
    'Rak A-04',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-005',
    'Ayat-Ayat Cinta',
    'FIC',
    'Rak A-05',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-006',
    'Laskar Pelangi',
    'FIC',
    'Rak A-06',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-007',
    'The Lord of the Rings',
    'FIC',
    'Rak A-07',
    0,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-008',
    'Hunger Games',
    'FIC',
    'Rak A-08',
    0,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-009',
    'Hujan Pagi',
    'FIC',
    'Rak A-09',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'FIC-010',
    'Hikayat Hang Tuah',
    'FIC',
    'Rak A-10',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-001',
    'Sejarah Melayu',
    'HIS',
    'Rak F-01',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-002',
    'Kuala Lumpur Dulu dan Sekarang',
    'HIS',
    'Rak F-02',
    0,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-003',
    'Perang Dunia Kedua',
    'HIS',
    'Rak F-03',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-004',
    'Tamadun Islam dan Asia',
    'HIS',
    'Rak F-04',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-005',
    'Merdeka! Merdeka!',
    'HIS',
    'Rak F-05',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-006',
    'Biografi Tunku Abdul Rahman',
    'HIS',
    'Rak F-06',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-007',
    'Kesultanan Melayu Melaka',
    'HIS',
    'Rak F-07',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-008',
    'Guns, Germs, and Steel',
    'HIS',
    'Rak F-08',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-009',
    'Sapiens: A Brief History',
    'HIS',
    'Rak F-09',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'HIS-010',
    'Sejarah Kedah',
    'HIS',
    'Rak F-10',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-001',
    'Dewan Siswa Edisi Januari 2024',
    'MAJ',
    'Rak B-01',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-002',
    'Dewan Bahasa Februari 2024',
    'MAJ',
    'Rak B-02',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-003',
    'National Geographic Asia',
    'MAJ',
    'Rak B-03',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-004',
    'Reader\'s Digest Malaysia',
    'MAJ',
    'Rak B-04',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-005',
    'Majalah PC',
    'MAJ',
    'Rak B-05',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-006',
    'Roda Pusing',
    'MAJ',
    'Rak B-06',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-007',
    'Impiana',
    'MAJ',
    'Rak B-07',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-008',
    'Dapur & Seleera',
    'MAJ',
    'Rak B-08',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-009',
    'Mastika',
    'MAJ',
    'Rak B-09',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'MAJ-010',
    'Gila-Gila',
    'MAJ',
    'Rak B-10',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-001',
    'Kamus Dewan Edisi Keempat',
    'REF',
    'Rak C-01',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-002',
    'Oxford English Dictionary',
    'REF',
    'Rak C-02',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-003',
    'Ensiklopedia Malaysiana Vol 1',
    'REF',
    'Rak C-03',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-004',
    'Ensiklopedia Malaysiana Vol 2',
    'REF',
    'Rak C-04',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-005',
    'Kamus Peribahasa Melayu',
    'REF',
    'Rak C-05',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-006',
    'Atlas Dunia Terkini',
    'REF',
    'Rak C-06',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-007',
    'Guinness World Records 2024',
    'REF',
    'Rak C-07',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-008',
    'Kamus Istilah Komputer',
    'REF',
    'Rak C-08',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-009',
    'Panduan Penulisan Tesis',
    'REF',
    'Rak C-09',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'REF-010',
    'Kamus Arab-Melayu',
    'REF',
    'Rak C-10',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-001',
    'A Brief History of Time',
    'SCI',
    'Rak E-01',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-002',
    'Cosmos by Carl Sagan',
    'SCI',
    'Rak E-02',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-003',
    'Biology of Humans',
    'SCI',
    'Rak E-03',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-004',
    'Physics for Dummies',
    'SCI',
    'Rak E-04',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-005',
    'Introduction to AI',
    'SCI',
    'Rak E-05',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-006',
    'Kimia Organik Asas',
    'SCI',
    'Rak E-06',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-007',
    'Dunia Dinosaur',
    'SCI',
    'Rak E-07',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-008',
    'The Selfish Gene',
    'SCI',
    'Rak E-08',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-009',
    'Sains Angkasa Lepas',
    'SCI',
    'Rak E-09',
    0,
    '2025-12-27 07:03:51'
  ),
  (
    'SCI-010',
    'Mikrob dan Manusia',
    'SCI',
    'Rak E-10',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-001',
    'Teknik Bola Sepak Asas',
    'SPT',
    'Rak H-01',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-002',
    'Badminton: Winning Strategies',
    'SPT',
    'Rak H-02',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-003',
    'Anatomi Senaman',
    'SPT',
    'Rak H-03',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-004',
    'Psikologi Sukan',
    'SPT',
    'Rak H-04',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-005',
    'Diet dan Nutrisi Atlet',
    'SPT',
    'Rak H-05',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-006',
    'Sejarah Olimpik',
    'SPT',
    'Rak H-06',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-007',
    'Yoga for Beginners',
    'SPT',
    'Rak H-07',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-008',
    'Latihan Beban',
    'SPT',
    'Rak H-08',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-009',
    'Peraturan Bola Jaring',
    'SPT',
    'Rak H-09',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'SPT-010',
    'Renang Gaya Bebas',
    'SPT',
    'Rak H-10',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-001',
    'Matematik SVM Tahun 1',
    'TXT',
    'Rak D-01',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-002',
    'Sains SVM Tahun 1',
    'TXT',
    'Rak D-02',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-003',
    'Bahasa Melayu SVM Tahun 2',
    'TXT',
    'Rak D-03',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-004',
    'Automotif Kenderaan Ringan',
    'TXT',
    'Rak D-04',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-005',
    'Asas Kimpalan Arka',
    'TXT',
    'Rak D-05',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-006',
    'Pemesinan Industri',
    'TXT',
    'Rak D-06',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-007',
    'Teknologi Elektrik 1',
    'TXT',
    'Rak D-07',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-008',
    'Pengajian Perniagaan',
    'TXT',
    'Rak D-08',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-009',
    'English for Communication',
    'TXT',
    'Rak D-09',
    1,
    '2025-12-27 07:03:51'
  ),
  (
    'TXT-010',
    'Sejarah Malaysia KSSM',
    'TXT',
    'Rak D-10',
    1,
    '2025-12-27 07:03:51'
  );

-- --------------------------------------------------------
--
-- Table structure for table `categories`
--
CREATE TABLE
  `categories` (
    `category_code` varchar(5) NOT NULL,
    `category_name` varchar(50) NOT NULL,
    `sub_text` varchar(100) DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--
INSERT INTO
  `categories` (`category_code`, `category_name`, `sub_text`)
VALUES
  ('ART', 'Seni', 'Seni & muzik'),
  ('FIC', 'Fiksyen', 'Novel, cerpen, drama'),
  ('HIS', 'Sejarah', 'Sejarah & warisan'),
  ('MAJ', 'Majalah', 'Majalah & terbitan berkala'),
  ('REF', 'Rujukan', 'Kamus, ensiklopedia'),
  ('SCI', 'Sains', 'Sains & teknologi'),
  ('SPT', 'Sukan', 'Sukan & kecergasan'),
  ('TXT', 'Buku Teks', 'Buku akademik');

-- --------------------------------------------------------
--
-- Table structure for table `readers`
--
CREATE TABLE
  `readers` (
    `reader_id` int (11) NOT NULL,
    `student_id` varchar(20) NOT NULL,
    `full_name` varchar(100) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `readers`
--
INSERT INTO
  `readers` (
    `reader_id`,
    `student_id`,
    `full_name`,
    `created_at`
  )
VALUES
  (
    1,
    'KVS-2024-001',
    'Ahmad Albab',
    '2025-12-27 07:03:51'
  ),
  (
    2,
    'KVS-2024-002',
    'Siti Nurhaliza',
    '2025-12-27 07:03:51'
  ),
  (
    3,
    'KVS-2024-003',
    'Muthu Sami',
    '2025-12-27 07:03:51'
  ),
  (
    4,
    'KVS-2024-004',
    'Ah Chong',
    '2025-12-27 07:03:51'
  ),
  (
    5,
    'KVS-2024-005',
    'Jessica Wong',
    '2025-12-27 07:03:51'
  ),
  (
    6,
    'KVS-2024-006',
    'Kevin Hartono',
    '2025-12-27 07:03:51'
  ),
  (
    7,
    'KVS-2024-007',
    'Nurul Izzah',
    '2025-12-27 07:03:51'
  ),
  (
    8,
    'KVS-2024-008',
    'Syed Saddiq',
    '2025-12-27 07:03:51'
  ),
  (
    9,
    'KVS-2024-009',
    'Lee Chong Wei',
    '2025-12-27 07:03:51'
  ),
  (
    10,
    'KVS-2024-010',
    'Nicol David',
    '2025-12-27 07:03:51'
  ),
  (
    11,
    'KVS-2024-011',
    'Pandelela Rinong',
    '2025-12-27 07:03:51'
  ),
  (
    12,
    'KVS-2024-012',
    'Azizulhasni Awang',
    '2025-12-27 07:03:51'
  ),
  (
    13,
    'KVS-2024-013',
    'Michelle Yeoh',
    '2025-12-27 07:03:51'
  ),
  (
    14,
    'KVS-2024-014',
    'P. Ramlee',
    '2025-12-27 07:03:51'
  ),
  (
    15,
    'KVS-2024-015',
    'Upin Ipin',
    '2025-12-27 07:03:51'
  );

-- --------------------------------------------------------
--
-- Table structure for table `transactions`
--
CREATE TABLE
  `transactions` (
    `trans_id` int (11) NOT NULL,
    `book_id` varchar(20) NOT NULL,
    `reader_id` int (11) NOT NULL,
    `borrow_date` datetime DEFAULT current_timestamp(),
    `due_date` date NOT NULL,
    `return_date` datetime DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--
INSERT INTO
  `transactions` (
    `trans_id`,
    `book_id`,
    `reader_id`,
    `borrow_date`,
    `due_date`,
    `return_date`
  )
VALUES
  (
    1,
    'FIC-001',
    1,
    '2024-10-05 10:00:00',
    '2024-10-19',
    '2024-10-15 10:00:00'
  ),
  (
    2,
    'FIC-002',
    2,
    '2024-10-06 11:30:00',
    '2024-10-20',
    '2024-10-18 14:00:00'
  ),
  (
    3,
    'FIC-003',
    3,
    '2024-10-10 09:00:00',
    '2024-10-24',
    '2024-10-22 09:00:00'
  ),
  (
    4,
    'SCI-001',
    4,
    '2024-10-12 14:00:00',
    '2024-10-26',
    '2024-10-25 10:00:00'
  ),
  (
    5,
    'SCI-002',
    5,
    '2024-10-15 16:00:00',
    '2024-10-29',
    '2024-10-28 11:00:00'
  ),
  (
    6,
    'FIC-004',
    6,
    '2024-10-20 12:00:00',
    '2024-11-03',
    '2024-11-01 10:00:00'
  ),
  (
    7,
    'FIC-005',
    7,
    '2024-11-02 09:00:00',
    '2024-11-16',
    '2024-11-15 09:00:00'
  ),
  (
    8,
    'FIC-006',
    8,
    '2024-11-05 10:00:00',
    '2024-11-19',
    '2024-11-18 10:00:00'
  ),
  (
    9,
    'FIC-007',
    9,
    '2024-11-10 11:00:00',
    '2024-11-24',
    '2024-11-20 11:00:00'
  ),
  (
    10,
    'FIC-008',
    10,
    '2024-11-15 14:00:00',
    '2024-11-29',
    '2024-11-28 14:00:00'
  ),
  (
    11,
    'SCI-003',
    1,
    '2024-11-18 15:00:00',
    '2024-12-02',
    '2024-12-01 10:00:00'
  ),
  (
    12,
    'SCI-004',
    2,
    '2024-11-20 09:00:00',
    '2024-12-04',
    '2024-12-03 09:00:00'
  ),
  (
    13,
    'HIS-001',
    3,
    '2024-11-25 10:00:00',
    '2024-12-09',
    '2024-12-08 10:00:00'
  ),
  (
    14,
    'FIC-009',
    4,
    '2024-12-01 08:00:00',
    '2024-12-15',
    '2024-12-14 08:00:00'
  ),
  (
    15,
    'FIC-010',
    5,
    '2024-12-03 10:00:00',
    '2024-12-17',
    '2024-12-16 10:00:00'
  ),
  (
    16,
    'FIC-001',
    6,
    '2024-12-05 12:00:00',
    '2024-12-19',
    '2024-12-18 12:00:00'
  ),
  (
    17,
    'FIC-002',
    7,
    '2024-12-08 14:00:00',
    '2024-12-22',
    '2024-12-20 14:00:00'
  ),
  (
    18,
    'FIC-003',
    8,
    '2024-12-10 16:00:00',
    '2024-12-24',
    '2024-12-23 16:00:00'
  ),
  (
    19,
    'SCI-005',
    9,
    '2024-12-12 09:00:00',
    '2024-12-26',
    '2024-12-24 09:00:00'
  ),
  (
    20,
    'SCI-006',
    10,
    '2024-12-15 10:00:00',
    '2024-12-29',
    '2024-12-28 10:00:00'
  ),
  (
    21,
    'MAJ-001',
    11,
    '2024-12-20 11:00:00',
    '2025-01-03',
    '2025-01-02 11:00:00'
  ),
  (
    22,
    'FIC-004',
    12,
    '2025-01-05 09:00:00',
    '2025-01-19',
    '2025-01-18 09:00:00'
  ),
  (
    23,
    'SCI-007',
    13,
    '2025-01-10 10:00:00',
    '2025-01-24',
    '2025-01-23 10:00:00'
  ),
  (
    24,
    'FIC-005',
    14,
    '2025-01-20 11:00:00',
    '2025-02-03',
    '2025-02-02 11:00:00'
  ),
  (
    25,
    'FIC-006',
    15,
    '2025-02-10 10:00:00',
    '2025-02-24',
    '2025-02-23 10:00:00'
  ),
  (
    26,
    'SCI-008',
    1,
    '2025-03-15 10:00:00',
    '2025-03-29',
    '2025-03-28 10:00:00'
  ),
  (
    27,
    'FIC-007',
    2,
    '2025-12-27 15:25:04',
    '2026-01-10',
    NULL
  ),
  (
    28,
    'SCI-009',
    3,
    '2025-12-27 15:25:04',
    '2026-01-10',
    NULL
  ),
  (
    29,
    'FIC-008',
    4,
    '2025-11-27 15:25:04',
    '2025-12-11',
    NULL
  ),
  (
    30,
    'HIS-002',
    5,
    '2025-12-15 15:25:04',
    '2025-12-29',
    NULL
  );

--
-- Indexes for dumped tables
--
--
-- Indexes for table `books`
--
ALTER TABLE `books` ADD PRIMARY KEY (`book_id`),
ADD KEY `category_code` (`category_code`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories` ADD PRIMARY KEY (`category_code`);

--
-- Indexes for table `readers`
--
ALTER TABLE `readers` ADD PRIMARY KEY (`reader_id`),
ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions` ADD PRIMARY KEY (`trans_id`),
ADD KEY `book_id` (`book_id`),
ADD KEY `reader_id` (`reader_id`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `readers`
--
ALTER TABLE `readers` MODIFY `reader_id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 16;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions` MODIFY `trans_id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 31;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `books`
--
ALTER TABLE `books` ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_code`) REFERENCES `categories` (`category_code`) ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions` ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`reader_id`) REFERENCES `readers` (`reader_id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;