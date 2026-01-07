-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql112.infinityfree.com
-- Generation Time: Jan 07, 2026 at 12:51 PM
-- Server version: 11.4.9-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40673779_ewu_event_notice_man_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `clubs`
--

CREATE TABLE `clubs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clubs`
--

INSERT INTO `clubs` (`id`, `name`, `created_at`) VALUES
(1, 'Programming club', '2026-01-07 06:36:31'),
(2, 'Dance club', '2026-01-07 06:36:35'),
(3, 'Debate club', '2026-01-07 06:36:39'),
(4, 'Research Innovators Club', '2026-01-07 06:39:16'),
(5, 'Math &amp; Logic Society', '2026-01-07 06:39:24'),
(6, 'Culture Connect Society', '2026-01-07 06:39:44'),
(7, 'Robotics &amp; AI Society', '2026-01-07 06:39:56'),
(8, 'Campus Fitness Club', '2026-01-07 06:40:06'),
(9, 'Chess &amp; Mind Games Society', '2026-01-07 06:40:13'),
(10, 'Leadership Lab', '2026-01-07 06:40:20'),
(11, 'Photography &amp; Design Society', '2026-01-07 06:40:25'),
(12, 'Cyber Security Club', '2026-01-07 06:40:34'),
(13, 'Green Campus Initiative', '2026-01-07 06:41:12'),
(14, 'Science Explorers Guild', '2026-01-07 06:41:31'),
(15, 'Sports Club', '2026-01-07 15:36:51');

-- --------------------------------------------------------

--
-- Table structure for table `club_admins`
--

CREATE TABLE `club_admins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `club_admins`
--

INSERT INTO `club_admins` (`id`, `user_id`, `club_id`) VALUES
(1, 29, 6),
(2, 26, 15),
(3, 23, 12),
(4, 14, 1),
(5, 15, 1),
(6, 20, 8),
(7, 28, 14),
(8, 27, 13),
(9, 24, 11);

-- --------------------------------------------------------

--
-- Table structure for table `club_members`
--

CREATE TABLE `club_members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `club_members`
--

INSERT INTO `club_members` (`id`, `user_id`, `club_id`) VALUES
(1, 25, 15),
(2, 30, 15),
(4, 32, 15);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `date` date DEFAULT NULL,
  `place` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `image`, `created_at`, `date`, `place`) VALUES
(1, 'Step Into the Spotlight: EWUâ€™s Latest Show', 'Step into a world of creativity and storytelling as the EWU Drama Club presents its latest stage production. This new drama explores themes of friendship, resilience, and the challenges faced by todayâ€™s youth, blending humor with heartfelt emotion.\r\n\r\nHighlights:\r\n\r\n1. Original script written and performed by EWU students\r\n\r\n2. A mix of comedy, suspense, and thought-provoking dialogue\r\n\r\n3. Showcasing the vibrant talent of the universityâ€™s drama enthusiasts\r\n\r\nThis event promises to be an engaging experience for the entire EWU community, offering both entertainment and reflection. Donâ€™t miss the chance to witness the passion and artistry of your fellow students live on stage!', 'EVENT-695e08934253a2.18251200.jpg', '2026-01-07 07:17:39', '2026-01-14', '33/1 Aftab Nagar, dhaka'),
(2, 'Cricket Cup 2024', '\"Cricket Cup 2024\" - EWU Sports Club\r\n\r\nNB: The relevant details will be announced later.', 'EVENT-695e7d84c55c40.11654277.jpg', '2026-01-07 15:36:36', '2026-01-07', 'East West University'),
(3, 'EWU Job Fair 2026', 'Dear Students and Graduates,\r\n\r\nEast West University is pleased to invite you to our flagship career event â€” EWU Job Fair 2025: â€œGraduates Navigating Career Paths.â€\r\n\r\nThis exciting event offers a unique opportunity to connect with top employers, explore diverse career options, and gain valuable insights into the professional world. Whether youâ€™re a student, a recent graduate, or a job seeker, this fair is designed to help you take the next step in your career journey.\r\n\r\nDate: 15 November 2025\r\nTime: 10:00 a.m. â€“ 6:00 p.m.\r\nVenue: EWU Courtyard, A/2 Aftabnagar, Dhaka\r\n\r\nWhat to Bring: Printed copies of your CV or rÃ©sumÃ©\r\n\r\nRegistration: Please complete your registration before the event (Click Here to Register)\r\n\r\nDonâ€™t miss this opportunity to meet industry leaders, discover new career paths, and shape your professional future.\r\n\r\nWe look forward to seeing you at the EWU Job Fair 2025', 'EVENT-695e7dd73271c0.13427122.jpg', '2026-01-07 15:37:59', '2026-01-31', 'EWU Courtyard, A/2 Aftabnagar, Dhaka'),
(4, 'EWU Intra-Model United Nations General Assembly', '\"EWU Intra-Model United Nations General Assembly\" - Organized by EWU Model United Nations Club.\r\n\r\nNB: The relevant details will be announced later.', 'EVENT-695e7e11023cc4.59286010.jpg', '2026-01-07 15:38:57', '2026-01-07', 'East West University'),
(5, 'Photo Fest Asia 2026', 'Photo Fest Asia 2026 is a premier international photography festival celebrating creativity, culture, and innovation through the lens. Organized by the EWU Photography Club, this yearâ€™s edition brings together professional photographers, students, and enthusiasts from across Asia to showcase their work, exchange ideas, and explore the evolving art of visual storytelling.\r\n\r\n\r\nTheme for 2026\r\n\"Frames of Tomorrow\" â€“ exploring how photography captures the intersection of tradition and modernity, technology and humanity, and the stories that define Asiaâ€™s future.', 'EVENT-695e7ed8f3e481.36050034.jpg', '2026-01-07 15:42:16', '2026-04-03', 'East West University'),
(6, 'National Science Carnival 2026', 'The National Science Carnival 2026, organized by the EWU Science Club, is Bangladeshâ€™s largest celebration of innovation, discovery, and scientific creativity. This annual festival brings together students, researchers, and enthusiasts from across the nation to showcase groundbreaking projects, exchange knowledge, and inspire the next generation of thinkers.\r\n\r\nTheme for 2026:  \r\n\"Science for a Sustainable Future\" â€“ exploring how innovation can address global challenges and build a better tomorrow.', 'EVENT-695e7f9fca9315.68406647.jpg', '2026-01-07 15:45:35', '2026-05-08', 'East West University'),
(7, '3rd International Conference on Information and Knowledge Management (i-IKM)', 'Department of Information Studies, East West University is going to organize the 3rd International Conference on Information and Knowledge Management (i-IKM 2023) which is scheduled to be held on 03-05 August, 2023 at East West University, Dhaka. The theme of the conference is â€œInclusive and Equitable Access to Information for Smart Societyâ€. This conference will offer a pathway of quality information and knowledge management and dissemination updates from the key experts and will provide an opportunity to understand the role of information and knowledge management, innovation and literacy for sustainable library and information institutions.\r\n\r\nIt gives us immense pleasure to invite you to this conference.\r\n\r\nPlease visit the Conference Website for Details: http://iikm.ewubd.edu/', 'EVENT-695e7fe8d28e65.33319383.jpg', '2026-01-07 15:46:48', '2026-02-03', 'East West University');

-- --------------------------------------------------------

--
-- Table structure for table `event_organizers`
--

CREATE TABLE `event_organizers` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `organizer_type` enum('club','department','authority') NOT NULL,
  `club_id` int(11) DEFAULT NULL,
  `department_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_organizers`
--

INSERT INTO `event_organizers` (`id`, `event_id`, `organizer_type`, `club_id`, `department_name`) VALUES
(2, 1, 'club', 6, NULL),
(4, 2, 'club', 15, NULL),
(5, 3, 'authority', NULL, NULL),
(6, 4, 'authority', NULL, NULL),
(7, 5, 'club', 11, NULL),
(8, 6, 'club', 14, NULL),
(9, 7, 'authority', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `description`, `image`, `created_at`) VALUES
(1, 'Date Extended Notice for Online Application of Graduation for 25th Convocation 2026', 'The Students who have completed all the requirements for their respective degrees\r\nÂ­and\r\n\r\nThe Students who are going to complete all the requirements for their respective degrees by Fall 2025 are requested to apply for graduation THROUGH ONLINE from EWU Student Portal https://portal.ewubd.edu) \r\n\r\nThe last date for application has been extended up to 14th October, 2025.\r\n\r\nPLEASE FOLLOW THE USER MANUAL GIVEN AT THE PORTAL BEFORE APPLY.', '', '2026-01-07 15:48:58'),
(2, 'EWUCRT 118th SEMINAR', 'East West University Center for Research and Training (EWUCRT) will organize a research seminar titled â€œMenâ€™s Experiences of Female Perpetrated Intimate Partner Violence in Bangladeshâ€ on 30 October 2025 at 9.00 am in EWUCRT Seminar Room (Room no. 560), 4th floor, Block-C of East West University. The presenters of the seminar are Dr. Anisur Rahman Khan, Associate Professor, and Dr. Rasel Hussain, Assistant Professor, Department of Sociology, East West University (EWU). A program schedule, short curriculum vitae of the presenters and an abstract of the seminar are attached herewith for your information.\r\n\r\nYour participation in the seminar will be deeply appreciated.\r\n\r\n1. Abstract\r\n2. Short Biography\r\n3. Invitation to All Faculty Members\r\n4. Seminar Schedule\r\n\r\nBest regards', '', '2026-01-07 15:49:44'),
(3, 'Online Add/Drop of Courses for Fall Semester 2025', 'Add/Drop of courses for Fall Semester 2025 of EWU will be held using the Online Add/Drop System. Students of both Undergraduate and Graduate Programs (Except BBA, B.Pharm and LL.B) will Add/Drop of courses through online using the link: https://portal.ewubd.edu as per the schedule mentioned below:', '', '2026-01-07 15:50:09'),
(4, 'Our Heartfelt Condolences', 'Inna Lillahi wa Inna Ilayhi Rajiâ€™un (Indeed, to Allah we belong, and indeed, to Him we shall return)\r\n\r\nWith profound sorrow and deep respect, we extend our heartfelt condolences on the passing of Begum Khaleda Zia, the esteemed former Prime Minister of Bangladesh.  Her dedicated service and significant contributions to the nation will be remembered with great honour.  \r\n\r\nMay Allah (SWT) grant her Jannah and give patience and strength to her family in this difficult time.\r\n\r\nOur sincere condolences to the bereaved family.', 'IMG-695e80de2947b9.86421255.jpg', '2026-01-07 15:50:54'),
(5, 'Undergraduate Admission Test: Spring 2026', 'East West University (EWU) announces the Undergraduate Admission Test for Spring 2026. The revised last date for submission of admission forms is 05 December 2025 at 2:00 PM.\r\n\r\nThis test opens the gateway for students to join EWUâ€™s diverse undergraduate programs and pursue academic excellence in a vibrant learning environment. Applicants are encouraged to complete their forms within the deadline and prepare for the upcoming test.\r\n\r\nRevised date (last date) and time for submission of Undergraduate Admission Forms:\r\n\r\nPrograms	                                               Date	                          Time\r\nUndergraduate Programs             05 December 2025                  02:00 pm.\r\n\r\n\r\nThis is for the information of and necessary action by all concerned.', '', '2026-01-07 15:53:03'),
(6, 'Departmental Orientation for the Newly Admitted Students of Undergraduate Programs of Spring Semeste', 'Departmental Orientation for the newly admitted students of Undergraduate Programs of East West University for Spring Semester 2026 will be held on Thursday, 08 January 2026.  Attendance in the Orientation Program is Mandatory for all the newly admitted students. Respective students are requested to kindly take seats at the venue according to the time schedule mentioned below against each program:', '', '2026-01-07 15:53:32'),
(7, 'Upcoming Workshop & Coding Challenge Date change', 'Please be informed that the Workshop on Competitive Programming & Coding Challenge, originally scheduled for January 20, 2026, has been rescheduled to January 27, 2026 (2:00 PM â€“ 5:00 PM, EWU Computer Lab â€“ Block C). All other details of the program remain unchanged. We apologize for any inconvenience and look forward to your active participation.', '', '2026-01-07 16:17:14');

-- --------------------------------------------------------

--
-- Table structure for table `notice_sources`
--

CREATE TABLE `notice_sources` (
  `id` int(11) NOT NULL,
  `notice_id` int(11) NOT NULL,
  `source_type` enum('authority','club','department','custom') NOT NULL,
  `club_id` int(11) DEFAULT NULL,
  `department_name` varchar(100) DEFAULT NULL,
  `custom_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice_sources`
--

INSERT INTO `notice_sources` (`id`, `notice_id`, `source_type`, `club_id`, `department_name`, `custom_name`) VALUES
(1, 1, 'authority', NULL, NULL, NULL),
(2, 2, 'authority', NULL, NULL, NULL),
(3, 3, 'authority', NULL, NULL, NULL),
(4, 4, 'authority', NULL, NULL, NULL),
(5, 5, 'authority', NULL, NULL, NULL),
(6, 6, 'authority', NULL, NULL, NULL),
(7, 7, 'club', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `recipient` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `recipient`, `type`, `date`, `is_read`) VALUES
(1, '\'Do Work bro\' has been assigned to you. Please review and start working on it', 25, 'New Task Assigned', '2026-01-07', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `club_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `due_date`, `status`, `created_at`, `club_id`) VALUES
(1, 'Do Work bro', 'Just do it', '2026-01-10', 'pending', '2026-01-07 16:14:44', 15);

-- --------------------------------------------------------

--
-- Table structure for table `task_assignments`
--

CREATE TABLE `task_assignments` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_assignments`
--

INSERT INTO `task_assignments` (`id`, `task_id`, `user_id`) VALUES
(1, 1, 25);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','club_member','authority','club_admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role`, `created_at`) VALUES
(12, 'Authority Admin', 'authority', '$2y$10$QHbgQTHz3kC0KgvJTFlg6ONdwhS3YlVcMHtEqUeSmQIIocUkae3ru', 'authority', '2025-12-11 21:16:31'),
(13, 'shakur', 'shakur', '$2y$10$1EW964X8Iq.eREyoGmIhZO/9FIyQ/HpmKahYTcrrj6fEes8/j5aUe', 'club_member', '2026-01-07 06:42:36'),
(14, 'shaik', 'shaik', '$2y$10$CHRVkKKS9qSB2Dg3kZOAhOzVngvqX24ZmrdOiL1GhsiH4wdRhq47.', 'club_admin', '2026-01-07 07:01:00'),
(15, 'Admin', 'admin', '$2y$10$93O9JjjXy6p12O.mMqjQ3uZrZuUtdosVxtwSf9dJhA/x9ur//ry/a', 'club_admin', '2026-01-07 07:01:08'),
(16, 'md', 'md', '$2y$10$NzMFs2iNjiSooR1Qr/e4Ae/6ZiperRw0ww0A9xmGPcTb2WOCNIYUK', 'club_member', '2026-01-07 07:01:21'),
(17, 'joy', 'joy', '$2y$10$x.maohKYbPhAOsg/j1oVvu24JmMP8xrBv1mvqBWcUc7oI2mnsuO/q', 'club_member', '2026-01-07 07:01:39'),
(18, 'samsu', 'samsu', '$2y$10$h7AMdLkrOqO4dq.znjdz9uGE1rhlHHUA5cAVeNU1yGhwUWSGAPNkW', 'club_member', '2026-01-07 07:08:32'),
(19, 'sinha', 'sinha', '$2y$10$Bk1/eoSmvUnsm7KfXWNJouBpfs6dMYBlusRir4jFE4zag14gTsWHK', 'club_member', '2026-01-07 07:08:39'),
(20, 'eshad', 'eshad', '$2y$10$phxFatay8/sTilIz2ikgD.eBgNibTd2kAlCO/l6n7V2Fc49yMb7yu', 'club_admin', '2026-01-07 07:08:50'),
(21, 'podder', 'podder', '$2y$10$uNZVe7obB4Q7BmvlkjIV9.sMFFnArcZzbEMqhy8vjjUDox.c3yX.C', 'club_member', '2026-01-07 07:08:58'),
(22, 'siyam', 'siyam', '$2y$10$6Cnug.Vis5Fgo8GxJq1zqODbrX7TlVLmvoVe45KGMJydijcTdTFCW', 'club_member', '2026-01-07 07:09:07'),
(23, 'rakib', 'rakib', '$2y$10$/xCxLt76fz5l7AGmXgZfqeyh6Tf5tpbjjcmzxKdaSRvBrPvFbRhxa', 'club_admin', '2026-01-07 07:09:50'),
(24, 'shamim', 'shamim', '$2y$10$oU/cAxizFxoafoY50w8QqeZd3UjA6vpIpqmKuDxyXzSdbVFOhY4b6', 'club_admin', '2026-01-07 07:09:58'),
(25, 'messi', 'messi', '$2y$10$XV6JjyVhj9G83qKAQkCxOu4RqylE1a8Ar.xYII9wiLjtU0TQeoQj2', 'club_member', '2026-01-07 07:10:13'),
(26, 'cr7', 'cr7', '$2y$10$mUvRklzdxBrXCq2yZIKtZul9tro2/foVvRx8R1G/aRVW8V7OgzYmC', 'club_admin', '2026-01-07 07:10:21'),
(27, 'mbappe', 'mbappe', '$2y$10$GsSbv6BUfa3WwvX/9CfUY.tYdw7MXxhcuhx9u01zWgSRJ5gKJXMva', 'club_admin', '2026-01-07 07:10:30'),
(28, 'jonny', 'jonny', '$2y$10$5CUHrxZmNLBMyBTemlyVS.BU4seqhHTz7eqRUuvtTa/q9tr842AO2', 'club_admin', '2026-01-07 07:11:07'),
(29, 'Christian bale', 'cbale', '$2y$10$p70zwpl7Kou3lg5UqC379ezqGC/.Y7gCOGDSThMxbPQxBDYnla70O', 'club_admin', '2026-01-07 07:11:21'),
(30, 'Rudiger', 'rudiger', '$2y$10$EKVHOE5EJXsaugtiUjXCme.0Q8Rfgrkwc0FBuNX5DcJzWAAeja3n.', 'club_member', '2026-01-07 15:59:21'),
(32, 'Pedri', 'pedri', '$2y$10$M7rSB8KZeMuq3FBg.UJUVOxuFNMUXoftP267Kb3fFomEJUxD2icY.', 'club_member', '2026-01-07 16:14:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clubs`
--
ALTER TABLE `clubs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `club_admins`
--
ALTER TABLE `club_admins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `club_id` (`club_id`);

--
-- Indexes for table `club_members`
--
ALTER TABLE `club_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `club_id` (`club_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_organizers`
--
ALTER TABLE `event_organizers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id_key` (`event_id`),
  ADD KEY `club_id_key` (`club_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_sources`
--
ALTER TABLE `notice_sources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notice_id` (`notice_id`),
  ADD KEY `club_id` (`club_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient` (`recipient`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tasks_club` (`club_id`);

--
-- Indexes for table `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
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
-- AUTO_INCREMENT for table `clubs`
--
ALTER TABLE `clubs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `club_admins`
--
ALTER TABLE `club_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `club_members`
--
ALTER TABLE `club_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `event_organizers`
--
ALTER TABLE `event_organizers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notice_sources`
--
ALTER TABLE `notice_sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `task_assignments`
--
ALTER TABLE `task_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `club_admins`
--
ALTER TABLE `club_admins`
  ADD CONSTRAINT `club_admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `club_admins_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `club_members`
--
ALTER TABLE `club_members`
  ADD CONSTRAINT `club_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `club_members_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_organizers`
--
ALTER TABLE `event_organizers`
  ADD CONSTRAINT `fk_event_organizers_club` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_event_organizers_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notice_sources`
--
ALTER TABLE `notice_sources`
  ADD CONSTRAINT `fk_notice_sources_club` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notice_sources_notice` FOREIGN KEY (`notice_id`) REFERENCES `notices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`recipient`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_tasks_club` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD CONSTRAINT `task_assignments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_assignments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
