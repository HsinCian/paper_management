-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-05-12 14:39:26
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `paper_management`
--

-- --------------------------------------------------------

--
-- 資料表結構 `categories`
--

CREATE TABLE `categories` (
  `caid` int(20) NOT NULL,
  `caname` varchar(20) NOT NULL,
  `userid` varchar(20) NOT NULL,
  `note` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `categories`
--

INSERT INTO `categories` (`caid`, `caname`, `userid`, `note`) VALUES
(1, 'data mining', '002', ''),
(2, 'diarization', '001', 'system: pyannote or EEND'),
(3, 'science', '001', '');

-- --------------------------------------------------------

--
-- 資料表結構 `documents`
--

CREATE TABLE `documents` (
  `pid` varchar(20) NOT NULL,
  `caname` varchar(20) NOT NULL,
  `userid` varchar(20) NOT NULL,
  `author` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `source` varchar(100) NOT NULL,
  `pages` varchar(20) NOT NULL,
  `date` varchar(20) NOT NULL,
  `file` text NOT NULL,
  `public` tinyint(1) NOT NULL,
  `vol` varchar(20) NOT NULL,
  `no` varchar(20) NOT NULL,
  `note` varchar(100) NOT NULL,
  `stype` varchar(20) NOT NULL,
  `editor` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `documents`
--

INSERT INTO `documents` (`pid`, `caname`, `userid`, `author`, `title`, `source`, `pages`, `date`, `file`, `public`, `vol`, `no`, `note`, `stype`, `editor`) VALUES
('1', 'data mining', '002', 'M.I. López, J.M Luna, C. Romero, S. Ventura', 'Classification via clustering for predicting final marks  based on student participation in forums', 'Conference on Education Data Mining', '22', 'June 2012', 'conference_paper.pdf', 1, '', '', '', 'conference', '002'),
('2', 'data mining', '002', 'Sellappan Palaniappan, Salman Mahmood, Ali Abbas', 'Predicting Student Performance in Higher Educational Institutions Using Video Learning Analytics and Data Mining Techniques', 'MDPI journal', '39-45', 'June 2020', 'jornal_paper.pdf', 1, '10', '11', 'predict student grades', 'journal', '002'),
('3', 'diarization', '001', 'Herve Bredin', 'pyannote.audio 2.1 speaker diarization pipeline: principle, benchmark, and recipe', 'INTERSPEECH', '82-87', '20-24 August 2023', 'bredin23_interspeech.pdf', 0, '', '', '', 'conference', '001'),
('4', 'science', '001', 'Mirco Ravanelli, Yoshua Bengio', 'SPEAKER RECOGNITION FROM RAW WAVEFORM WITH SINCNE', ' IEEE Spoken Language Technology Workshop', '1-9', '9 AUG 2019', 'SincNet.pdf', 1, '', '', 'speaker embedding', 'conference', '001,002'),
('5', 'diarization', '001', 'Shota Horiguchi,  Yusuke Fujita, Shinji Watanabe', 'Encoder-Decoder Based Attractors for End-to-End Neural Diarization', 'IEEE/ACM TRANSACTIONS ON AUDIO, SPEECH, AND LANGUAGE PROCESSING', '1493-1507', '2022', 'Encoder-Decoder_Based_Attractors_for_End-to-End_Neural_Diarization.pdf', 0, '30', '5', 'EEND', 'journal', '001');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `userid` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`userid`, `password`, `username`) VALUES
('001', '$2y$10$UZBZ9uO4SxLogfcReEDWKutjWWzpWFK4Px5xd5cSyuoDYlh5JVRgy', 'test'),
('002', '$2y$10$UZBZ9uO4SxLogfcReEDWKutjWWzpWFK4Px5xd5cSyuoDYlh5JVRgy', 'user2');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`caid`);

--
-- 資料表索引 `documents`
--
ALTER TABLE `documents`
  ADD KEY `userid` (`userid`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `categories`
--
ALTER TABLE `categories`
  MODIFY `caid` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
