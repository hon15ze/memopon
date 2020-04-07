-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:8889
-- 生成日時: 2020 年 3 月 13 日 07:08
-- サーバのバージョン： 5.7.26
-- PHP のバージョン: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `memopon`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `favorite`
--

CREATE TABLE `favorite` (
  `memo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `favorite`
--

INSERT INTO `favorite` (`memo_id`, `user_id`, `delete_flg`, `create_date`, `update_date`) VALUES
(6, 1, 0, '2020-01-14 07:29:40', '2020-01-13 22:29:40'),
(12, 1, 0, '2020-01-28 08:06:37', '2020-01-27 23:06:37'),
(9, 1, 0, '2020-01-31 03:27:17', '2020-01-30 18:27:17'),
(14, 1, 0, '2020-02-19 12:23:17', '2020-02-19 03:23:17'),
(30, 1, 0, '2020-03-05 07:28:04', '2020-03-04 22:28:04');

-- --------------------------------------------------------

--
-- テーブルの構造 `memo`
--

CREATE TABLE `memo` (
  `m_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `pic1` varchar(255) DEFAULT NULL,
  `pic2` varchar(255) DEFAULT NULL,
  `pic3` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `memo`
--

INSERT INTO `memo` (`m_id`, `comment`, `pic1`, `pic2`, `pic3`, `user_id`, `delete_flg`, `create_date`, `update_date`) VALUES
(8, 'おいしかったランチ\r\nたまたまカレーセットしかなかった笑', 'uploads/ac6efae4c9f5a010ea382a93220ca4bc0304347d.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-26 10:49:06'),
(9, 'クリスマスに作ったケーキ\r\nいちごクリームで可愛くできて満足', 'uploads/6dd69c4e51b295cf5f82e915f6b03b38f94ed4e5.jpeg', 'uploads/6d1db37961832cacd06ea063c790072a75cc1212.jpeg', 'uploads/fff6cc815f3689aa02147b26537c99c34bec8cbe.jpeg', 1, 0, '2020-01-26 19:50:10', '2020-01-28 00:55:28'),
(10, 'お土産でもらった長崎のチーズケーキでティータイム♫', 'uploads/667804315f48d43483df811c981859a07cf50373.jpeg', '', '', 1, 0, '2020-01-26 19:50:56', '2020-01-26 10:50:56'),
(11, '仙台へ旅行に行ったときのかわいいバス', 'uploads/aa7887b7faf6465375f1cb17e3bae142875ebd7c.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:26:14'),
(12, 'もふもふのねこさん、かわいいね', 'uploads/fac512f030367da6dd410ea57360915dd3aeb27c.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:29:00'),
(13, 'お土産にもらったパンダバウムでティータイム', 'uploads/ba6128ef655dde43610cf9b7f32977c2944e7005.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:31:07'),
(14, 'カフェで出会った美人さん', 'uploads/9792dd43fdd3ad07dc211bf471cbb600c814736e.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:33:04'),
(15, '香嵐渓の帰りに行ったおしゃれなカフェ', 'uploads/200834a757d6f53ade84135a0562457c5615d646.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:34:24'),
(16, '香嵐渓のライトアップ、綺麗だったなあ', 'uploads/862d5090cc4bf3aca162f6914be33a37568b856d.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:36:40'),
(17, '大好きなオムライスやさん。この日はホワイトソース！', 'uploads/e91ff4804850940a81193cf5f826195f437e869e.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:38:01'),
(18, 'かわいいメッセージカード。たまには手書きもいいよね。', 'uploads/5d9d9b47bae3971f8ed707a0c2fccca26cdc3f6f.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:39:22'),
(19, '可愛く焼けたリラックマのバタークッキー', 'uploads/6067e9fb912911372b89af55c97fbd58ad6f20de.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:40:50'),
(20, 'なばなの里のプロジェクションマッピング。綺麗。', 'uploads/175545138221b875a7930017dbe086f09e3bdbb7.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:42:01'),
(21, 'なばなの里のチューリップ。かわいい！', 'uploads/1690dcfdb1a030f19f80c1adeaf9bd4e36a30c27.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:43:14'),
(22, 'ライトアップされたチューリップ。幻想的！', 'uploads/4ffd71182a52ce167f8eb817834343c969674c39.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:44:33'),
(23, '大好きなパンケーキやさんの抹茶スフレパンケーキ', 'uploads/615ba34302a2985e2885fd2eb0547949043a2cc3.jpeg', '', '', 1, 0, '2020-01-26 19:49:06', '2020-01-27 15:45:37'),
(28, 'お誕生日に作ったサンドイッチかわいくできた！', 'uploads/c01fd4e6d0c3f3503ab2daa418ba2102bd0f7ce6.jpeg', '', '', 1, 0, '2020-01-26 19:50:10', '2020-01-27 15:46:42'),
(29, '響きがかわいい声に出して読みたい日本語、その名も「ねこねこ食パン」', 'uploads/e0e0fc655fe5581059948fb6b6459df804e81826.jpeg', '', '', 1, 0, '2020-01-26 19:50:10', '2020-01-27 15:48:01'),
(30, 'ハリネズミカフェで出会ったハリネズミのきなこちゃん', 'uploads/f1a52ba53b4a5f9a11843219034bfd454972039a.jpeg', '', '', 1, 0, '2020-01-26 19:50:10', '2020-01-27 15:48:59'),
(31, '雑誌に載ってて気になってたおしゃれなカフェ。ランチタルトおいしかったなあ。', 'uploads/09aa297e436de5a6b42bd8a838604ff22826e738.jpeg', '', '', 1, 0, '2020-01-26 19:50:10', '2020-01-27 15:50:28'),
(36, 'ローストビーフ作った', 'uploads/e799d290fca8f39898a41eae7264f36fb5743fa6.jpeg', '', '', 1, 0, '2020-01-27 22:55:36', '2020-01-27 13:55:36');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pic` varchar(255) DEFAULT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `login_time`, `pic`, `delete_flg`, `create_date`, `update_date`) VALUES
(1, 'hon', 'lache2525@gmail.com', '$2y$10$5hNAd1Qe2ElfP1lY7SqGc.mO6zi7K8H2K2m8is1KOJt4BQXHFrZtC', '2020-01-29 21:35:21', 'uploads/53b2b73aba5265ced26210705aaec63c855dda09.jpeg', 0, '2019-11-26 21:56:41', '2020-01-29 21:35:21'),
(2, 'ほん', 'hon1111cl@gmail.com', '$2y$10$qYDFJyKkswLyogfvEwpTTeweX8UtmtxCte7PKeqwMq9WZ42H2M43a', '2020-01-30 12:44:51', NULL, 0, '2020-01-30 21:44:51', '2020-01-30 12:44:51');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `memo`
--
ALTER TABLE `memo`
  ADD PRIMARY KEY (`m_id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `memo`
--
ALTER TABLE `memo`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
