-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2025 at 07:21 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogpost`
--

CREATE TABLE `blogpost` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogpost`
--

INSERT INTO `blogpost` (`id`, `user_id`, `title`, `content`, `created_at`, `updated_at`) VALUES
(1, 4, 'Why I Started Blogging with Lilium', '![](uploads/1762280258_MV5BMjQ5ODM5OGUtNzZmNi00MWVlLWFkOTctYzljYzYxY2QwYzU3XkEyXkFqcGdeQXVyNTAzMTY4MDA@._V1_.jpg)\r\n\r\nWriting has always been my favorite way to organize thoughts and share ideas.\r\n\r\nWhen I discovered Lilium, I realized how simple and elegant blogging could be — clean layout, focused design, and no distractions.\r\n\r\nMy plan is to post weekly about my experiences with web development, design, and digital minimalism.\r\n\r\n**This is just the beginning — here’s to building something meaningful, one post at a time 🌸**', '2025-11-01 23:40:04', '2025-11-06 21:47:10'),
(7, 6, 'The Beauty of Getting Lost While Traveling ✈️', 'One of the best parts of traveling isn’t always the famous landmarks or the picture-perfect views, it’s getting a little lost along the way.\r\n\r\nWhen we wander off the main route, we find hidden cafes, quiet streets, and unexpected kindness from locals. We stumble upon tiny shops selling handmade goods or a view that no guidebook ever mentioned. Those moments of surprise often become the stories we tell long after the trip ends.\r\n\r\nTravel isn’t just about ticking places off a list, it’s about experiencing life in a new rhythm. Slowing down. Smelling unfamiliar spices in the market. Listening to a different language drift through the air.\r\n\r\n***So next time you travel, don’t be afraid to take a wrong turn. Sometimes, that’s exactly where the adventure begins. ***🌍', '2025-11-04 15:14:31', '2025-11-04 15:15:22'),
(8, 4, 'Learning to Be Okay With Where You Are 🌙', '![](uploads/1762266154_IMG_20240120_175335_149.jpg)\r\n\r\nLately, I’ve been thinking a lot about time. How it slips by quietly while we’re busy planning for what’s next. How easy it is to measure ourselves against invisible timelines — the ones that say we should’ve achieved this by a certain age, or figured out that by now.\r\n\r\nThere’s a strange pressure in always wanting to be somewhere else — in our careers, relationships, or even our own minds. We chase milestones, thinking happiness lives on the other side of achievement. But what if it’s already here, quietly waiting in the present?\r\n\r\nI’ve spent years believing I had to earn my peace. That I needed to become “better” before I could feel content. But life keeps reminding me that growth isn’t a straight line. Some days you’re inspired and productive; other days, you’re just trying to make it through. Both matter. Both count.\r\n\r\nI’ve learned that healing doesn’t always look graceful. Sometimes it’s just showing up — brushing your teeth when you don’t feel like it, replying to a text even when you’d rather disappear, or forgiving yourself for being human.\r\n\r\nAnd maybe being “okay” isn’t about feeling happy all the time. Maybe it’s about accepting the quiet middle — the space between joy and sadness, success and confusion, where most of life actually happens.\r\n\r\nThere’s a kind of peace that comes when you stop running from the moment you’re in. When you stop comparing your story to everyone else’s. You start to notice small things again — the way sunlight lands on your floor in the morning, the sound of rain against the window, the laughter that sneaks up on you when you least expect it.\r\n\r\nYou realize life doesn’t need to be perfect to be beautiful. You just have to be present for it.\r\n\r\nSo if you’re reading this and feeling behind, I hope you remember this: you’re not late. You’re just living. And that’s enough. 🌿\r\n\r\n', '2025-11-04 18:04:19', '2025-11-04 19:52:36'),
(10, 4, 'Embracing Small Moments of Joy', '![](uploads/1762445933_IMG_20230514_160001_440.jpg)\r\n\r\nLife often feels busy, overwhelming, and fast-paced. We chase big goals and dreams, yet sometimes forget to appreciate the small moments that make life meaningful.\r\n\r\nA warm cup of coffee in the morning, a smile from a stranger, or the sound of birds singing outside your window—these little moments are reminders that happiness doesn’t always come from grand achievements.\r\n\r\nTaking time to notice them can improve your mood, reduce stress, and help you feel more connected to the world around you. Try keeping a small journal of daily joys or simply pause for a few seconds each day to observe something beautiful.\r\n\r\nIn the end, life is a collection of tiny moments. When we embrace them, even the ordinary becomes extraordinary.', '2025-11-06 21:48:57', '2025-11-06 21:48:57'),
(11, 9, 'Small Steps to a Healthier You', '![](uploads/1762449857_360_F_626839295_CSJOBGkH7LM6qhke2FkPzFGUCt8ggiBw.jpg)\r\n\r\nWhen it comes to wellness, many people think it means a total lifestyle overhaul — strict diets, intense workouts, or complicated routines. But the truth is, lasting wellness is built through small, consistent steps that fit naturally into your daily life.\r\n\r\nStart with the basics. Drink more water throughout the day — it boosts your energy, supports digestion, and even improves your mood. Add a short walk after lunch or dinner; it’s great for your mind and body. And don’t underestimate the power of good sleep. Prioritize rest, and your body will thank you with more focus, energy, and balance.\r\n\r\nWellness isn’t about perfection — it’s about progress. Some days will feel easier than others, and that’s okay. What matters is showing up for yourself, even in small ways.\r\n\r\n**Take a deep breath, stretch your body, and remind yourself: every healthy choice counts.**', '2025-11-06 22:54:24', '2025-11-06 22:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `bio` text DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`, `bio`, `remember_token`) VALUES
(4, 'Ruhini', 'ru@gmail.com', '$2y$10$Br9LfS7bPCcKHiet.YVvz.Ob7VQcgxCtfNtUfp7Aw3cXFNHwDChrG', 'user', 'Just sharing ideas and stories...', NULL),
(5, 'Root', 'ryleraya7@gmail.com', '$2y$10$VKSwAEk92s2H3/O5xMs92OxmtMxxY/QimKGVjC8R14bBhY.DBRjyK', 'user', NULL, NULL),
(6, 'Chehansa', 'che7@gmail.com', '$2y$10$H2ep0pIWQtFSB/XhtZ1jAuwoWbGBPjIpJEYYQCAJLWbJ5Nw4Yg.Zq', 'user', 'creating blogs...', '6218441e4da966b9abd02b18a7a86b18'),
(7, 'Thisarani', 'thiz@gmail.com', '$2y$10$G14y2dFuVapdWKGFvosYoOaN14xJ/kvELOGRGFtq9jJAYGoCQSNeS', 'user', NULL, '59706a093798c04bfbd66989cae13259'),
(8, 'Ridmi', 'ridmi2@gmail.com', '$2y$10$rmKDbAbph40mlWhTfJo6B.Rsprv6a9Y2atHMuYsf9IWNzucOdDlUq', 'user', NULL, '845b7041102bc9dd049143b3412ffd01'),
(9, 'Chethana', 'che2@gmail.com', '$2y$10$xvSL/TUGyH7aRZjjwjyDveUZHWjYmKwgAFNsXbHtR44ITFsIZjwJm', 'user', 'Sharing simple tips for a healthier, happier life, one mindful step at a time 🌸✨', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogpost`
--
ALTER TABLE `blogpost`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogpost`
--
ALTER TABLE `blogpost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogpost`
--
ALTER TABLE `blogpost`
  ADD CONSTRAINT `blogpost_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
