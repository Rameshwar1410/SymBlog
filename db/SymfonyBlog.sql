-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 12, 2019 at 06:02 PM
-- Server version: 5.7.27-0ubuntu0.18.04.1
-- PHP Version: 7.1.33-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `SymfonyBlog`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `post_id`, `user_id`, `comment`, `created`, `updated`) VALUES
(6, 1, 1, 'aaa', '2019-06-12 14:45:55', NULL),
(7, 1, 1, 'aa', '2019-06-12 14:54:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190514083405', '2019-05-14 08:34:13'),
('20190514104333', '2019-05-14 10:44:42'),
('20190606060530', '2019-06-06 06:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `posted_by_id` int(11) NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `posted_by_id`, `comment`, `title`, `created`, `updated`) VALUES
(1, 1, '<div><h3 name=\"2e9d\" id=\"2e9d\" class=\"graf graf--h3 graf-after--p\" style=\"margin: 56px 0px 0px; font-weight: 600; line-height: 1.15; font-size: 34px; font-family: medium-content-sans-serif-font, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Geneva, Arial, sans-serif; letter-spacing: -0.015em; color: rgba(0, 0, 0, 0.84); --x-height-multiplier:0.342; --baseline-multiplier:0.22;\"><span class=\"markup--strong markup--h3-strong\" style=\"font-weight: inherit;\">The Setup</span></h3><div>I created a 2GB DigitalOcean PHP 7.1 server using Laravel Forge. I installed Symfony, optimized the Composer autoloader, and configured Nginx to serve Symfony through the production (app.php) front-controller. I also followed the steps in the Symfony production tuning guide. I followed the same basic procedure for Zend..</div></div>', 'Symfony', '2019-05-14 14:26:41', '2019-05-22 16:16:13'),
(2, 2, '<div><div>A variety of benchmarks comparing PHP frameworks float around the web. However, they are often comparing “apples” to “oranges”. In particular, I want to focus on Laravel, Symfony, and Zend and why these three frameworks are often benchmarked incorrectly against each other.</div><div><span class=\"markup--strong markup--p-strong\" style=\"font-weight: 700;\">You don’t have to take my word for it.</span>&nbsp;After reading this post you can spin up your own 2GB DigitalOcean server and test these results for yourself in about 5 minutes.</div><div><span class=\"markup--strong markup--p-strong\" style=\"font-weight: 700;\"><em class=\"markup--em markup--p-em\" style=\"font-feature-settings: &quot;liga&quot;, &quot;salt&quot;;\">Before beginning, know that all of these frameworks are fast enough to handle any application you are ever likely to build. I hesitated to even write this post because I think PHP’s unique obsession with benchmarking on this level is really, really silly. My only goal is to show how to perform a fair comparison between the three.</em></span></div><h3 name=\"ef21\" id=\"ef21\" class=\"graf graf--h3 graf-after--p\" style=\"font-family: medium-content-sans-serif-font, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Geneva, Arial, sans-serif; letter-spacing: -0.015em; font-weight: 600; margin: 56px 0px 0px; color: rgba(0, 0, 0, 0.84); --x-height-multiplier:0.342; --baseline-multiplier:0.22; font-size: 34px; line-height: 1.15;\"><span class=\"markup--strong markup--h3-strong\" style=\"font-weight: inherit;\">The Problem</span></h3><div>When you first configure a Symfony or Zend project on a fresh DigitalOcean server, you will see that there is no session information being returned.&nbsp;<span class=\"markup--strong markup--p-strong\" style=\"font-weight: 700;\">Note how no cookies are present on the site:</span></div><figure name=\"ea4b\" id=\"ea4b\" class=\"graf graf--figure graf-after--p\" style=\"margin-top: 43px; margin-bottom: 0px; position: relative; clear: both; outline: 0px; user-select: auto; z-index: 100; color: rgba(0, 0, 0, 0.84); font-family: medium-content-sans-serif-font, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px;\"><div class=\"aspectRatioPlaceholder is-locked\" style=\"position: relative; width: 700px; margin: 0px auto; max-width: 700px; max-height: 233px;\"><div class=\"aspectRatioPlaceholder-fill\" style=\"padding-bottom: 232.391px;\"></div><div class=\"progressiveMedia js-progressiveMedia graf-image is-canvasLoaded is-imageLoaded\" data-image-id=\"1*-PoyQN7erYpwY-CCIMw4LA.png\" data-width=\"3050\" data-height=\"1014\" data-action=\"zoom\" data-action-value=\"1*-PoyQN7erYpwY-CCIMw4LA.png\" data-scroll=\"native\" style=\"position: absolute; top: 0px; left: 0px; width: 700px; height: 232.391px; margin: auto; background: rgba(0, 0, 0, 0); transition: background 0.2s ease 0s; cursor: zoom-in; max-width: 100%;\"><canvas class=\"progressiveMedia-canvas js-progressiveMedia-canvas\" width=\"75\" height=\"22\" style=\"display: block; vertical-align: baseline; position: absolute; top: 0px; left: 0px; width: 700px; height: 232.391px; margin: auto; visibility: hidden; opacity: 0; backface-visibility: hidden; transition: visibility 0s linear 0.5s, opacity 0.1s ease 0.4s;\"></canvas><img class=\"progressiveMedia-image js-progressiveMedia-image\" data-src=\"https://cdn-images-1.medium.com/max/800/1*-PoyQN7erYpwY-CCIMw4LA.png\" src=\"https://cdn-images-1.medium.com/max/800/1*-PoyQN7erYpwY-CCIMw4LA.png\" style=\"border: 0px; display: block; position: absolute; top: 0px; left: 0px; width: 700px; height: 232.391px; margin: auto; z-index: 100; visibility: visible; opacity: 1; backface-visibility: hidden; transition: visibility 0s linear 0s, opacity 0.4s ease 0s;\"></div></div></figure><div>However, Laravel ships with sessions (and other middleware) enabled out of the box on the default landing page. This is convenient because most web applications built using these frameworks use sessions to persist user state.&nbsp;<span class=\"markup--strong markup--p-strong\" style=\"font-weight: 700;\">I leave this as the default for user convenience even though it hurts the framework in naive benchmark comparisons.</span></div><figure name=\"edd2\" id=\"edd2\" class=\"graf graf--figure graf-after--p\" style=\"margin-top: 43px; margin-bottom: 0px; position: relative; clear: both; outline: 0px; user-select: auto; z-index: 100; color: rgba(0, 0, 0, 0.84); font-family: medium-content-sans-serif-font, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px;\"><div class=\"aspectRatioPlaceholder is-locked\" style=\"position: relative; width: 700px; margin: 0px auto; max-width: 700px; max-height: 517px;\"><div class=\"aspectRatioPlaceholder-fill\" style=\"padding-bottom: 517.297px;\"></div><div class=\"progressiveMedia js-progressiveMedia graf-image is-canvasLoaded is-imageLoaded\" data-image-id=\"1*zN7lUV5ls44n2zQ2xvN9MQ.png\" data-width=\"1334\" data-height=\"986\" data-action=\"zoom\" data-action-value=\"1*zN7lUV5ls44n2zQ2xvN9MQ.png\" data-scroll=\"native\" style=\"position: absolute; top: 0px; left: 0px; width: 700px; height: 517px; margin: auto; background: rgba(0, 0, 0, 0); transition: background 0.2s ease 0s; cursor: zoom-in; max-width: 100%;\"><canvas class=\"progressiveMedia-canvas js-progressiveMedia-canvas\" width=\"75\" height=\"55\" style=\"display: block; vertical-align: baseline; position: absolute; top: 0px; left: 0px; width: 700px; height: 517px; margin: auto; visibility: hidden; opacity: 0; backface-visibility: hidden; transition: visibility 0s linear 0.5s, opacity 0.1s ease 0.4s;\"></canvas><img class=\"progressiveMedia-image js-progressiveMedia-image\" data-src=\"https://cdn-images-1.medium.com/max/800/1*zN7lUV5ls44n2zQ2xvN9MQ.png\" src=\"https://cdn-images-1.medium.com/max/800/1*zN7lUV5ls44n2zQ2xvN9MQ.png\" style=\"border: 0px; display: block; position: absolute; top: 0px; left: 0px; width: 700px; height: 517px; margin: auto; z-index: 100; visibility: visible; opacity: 1; backface-visibility: hidden; transition: visibility 0s linear 0s, opacity 0.4s ease 0s;\"></div></div></figure><div><span class=\"markup--quote markup--p-quote is-other\" name=\"anon_c927f97256bc\" data-creator-ids=\"anon\" style=\"transition: background-color 0.2s ease 0s; cursor: pointer; background-color: transparent; background-image: linear-gradient(rgba(12, 242, 143, 0.2), rgba(12, 242, 143, 0.2));\">Another common mistake is either not dumping an optimized Composer autoloader or not caching the Laravel configuration using the&nbsp;<code class=\"markup--code markup--p-code\" style=\"font-family: Menlo, Monaco, &quot;Courier New&quot;, Courier, monospace; font-size: 16px; background: rgba(0, 0, 0, 0.05); padding: 3px 4px; margin: 0px 2px;\">php artisan config:cache</code>&nbsp;command, which saves a significant amount of bootstrap time.</span></div><h3 name=\"2e9d\" id=\"2e9d\" class=\"graf graf--h3 graf-after--p\" style=\"font-family: medium-content-sans-serif-font, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Geneva, Arial, sans-serif; letter-spacing: -0.015em; font-weight: 600; margin: 56px 0px 0px; color: rgba(0, 0, 0, 0.84); --x-height-multiplier:0.342; --baseline-multiplier:0.22; font-size: 34px; line-height: 1.15;\"><span class=\"markup--strong markup--h3-strong\" style=\"font-weight: inherit;\">The Setup</span></h3><div>I created a 2GB DigitalOcean PHP 7.1 server using Laravel Forge. I installed Symfony, optimized the Composer autoloader, and configured Nginx to serve Symfony through the production (app.php) front-controller. I also followed the steps in the Symfony production tuning guide. I followed the same basic procedure for Zend.</div><div>Next, I configured PHP 7.1’s opcache for production with the following settings:</div><div>opcache.enable=1<br>opcache.memory_consumption=512<br>opcache.interned_strings_buffer=64<br>opcache.max_accelerated_files=20000<br>opcache.validate_timestamps=0<br>opcache.save_comments=1<br>opcache.fast_shutdown=1</div><div>When installing Laravel, I ran the&nbsp;<code class=\"markup--code markup--p-code\" style=\"font-family: Menlo, Monaco, &quot;Courier New&quot;, Courier, monospace; font-size: 16px; background: rgba(0, 0, 0, 0.05); padding: 3px 4px; margin: 0px 2px;\">config:cache</code>&nbsp;Artisan command and commented out the middleware in the&nbsp;<code class=\"markup--code markup--p-code\" style=\"font-family: Menlo, Monaco, &quot;Courier New&quot;, Courier, monospace; font-size: 16px; background: rgba(0, 0, 0, 0.05); padding: 3px 4px; margin: 0px 2px;\">web</code>&nbsp;middleware group of the&nbsp;<code class=\"markup--code markup--p-code\" style=\"font-family: Menlo, Monaco, &quot;Courier New&quot;, Courier, monospace; font-size: 16px; background: rgba(0, 0, 0, 0.05); padding: 3px 4px; margin: 0px 2px;\">app/Http/Kernel.php</code>&nbsp;file. These are the middleware responsible for enabling sessions. This change allows me to test all three frameworks without session handling.</div><h3 name=\"f34a\" id=\"f34a\" class=\"graf graf--h3 graf-after--p\" style=\"font-family: medium-content-sans-serif-font, &quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, &quot;Lucida Sans&quot;, Geneva, Arial, sans-serif; letter-spacing: -0.015em; font-weight: 600; margin: 56px 0px 0px; color: rgba(0, 0, 0, 0.84); --x-height-multiplier:0.342; --baseline-multiplier:0.22; font-size: 34px; line-height: 1.15;\">The Results</h3><div>After configuring the projects, I ran a simple test using Apache benchmark, which anyone can recreate:</div><div><code class=\"markup--code markup--p-code u-paddingRight0 u-marginRight0\" style=\"font-family: Menlo, Monaco, &quot;Courier New&quot;, Courier, monospace; font-size: 16px; margin: 0px 2px; padding: 3px 4px; background: rgba(0, 0, 0, 0.05);\">ab -t 10 -c 10&nbsp;<a href=\"http://domain.here/\" data-href=\"http://domain.here/\" class=\"markup--anchor markup--p-anchor\" rel=\"nofollow noopener\" target=\"_blank\" style=\"background-image: initial; background-position: 0px 0px; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; color: inherit; text-decoration-line: underline; -webkit-tap-highlight-color: rgba(0, 0, 0, 0.54);\">http://server.address/</a></code></div><div>Here are the results:</div><div><span class=\"markup--strong markup--p-strong\" style=\"font-weight: 700;\"><em class=\"markup--em markup--p-em\" style=\"font-feature-settings: &quot;liga&quot;, &quot;salt&quot;;\">Without Sessions:</em></span></div><ul class=\"postList\" style=\"margin: 29px 0px 0px; padding: 0px; list-style: none none; counter-reset: post 0; color: rgba(0, 0, 0, 0.84); font-family: medium-content-sans-serif-font, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px;\"><li name=\"6361\" id=\"6361\" class=\"graf graf--li graf-after--p\" style=\"margin-left: 30px; margin-bottom: 14px; --x-height-multiplier:0.375; --baseline-multiplier:0.17; font-family: medium-content-serif-font, Georgia, Cambria, &quot;Times New Roman&quot;, Times, serif; font-size: 21px; line-height: 1.58; letter-spacing: -0.003em;\"><span class=\"markup--strong markup--li-strong\" style=\"font-weight: 700;\">Laravel</span>: 609.03 requests per second (mean)</li><li name=\"f6d3\" id=\"f6d3\" class=\"graf graf--li graf-after--li\" style=\"margin-left: 30px; margin-bottom: 14px; --x-height-multiplier:0.375; --baseline-multiplier:0.17; font-family: medium-content-serif-font, Georgia, Cambria, &quot;Times New Roman&quot;, Times, serif; font-size: 21px; line-height: 1.58; letter-spacing: -0.003em;\"><span class=\"markup--strong markup--li-strong\" style=\"font-weight: 700;\">Zend</span>: 559.91 requests per second (mean)</li><li name=\"06aa\" id=\"06aa\" class=\"graf graf--li graf-after--li\" style=\"margin-left: 30px; margin-bottom: 0px; --x-height-multiplier:0.375; --baseline-multiplier:0.17; font-family: medium-content-serif-font, Georgia, Cambria, &quot;Times New Roman&quot;, Times, serif; font-size: 21px; line-height: 1.58; letter-spacing: -0.003em;\"><span class=\"markup--strong markup--li-strong\" style=\"font-weight: 700;\">Symfony</span>: 532.97 requests per second (mean)</li></ul><div><span class=\"markup--strong markup--p-strong\" style=\"font-weight: 700;\"><em class=\"markup--em markup--p-em\" style=\"font-feature-settings: &quot;liga&quot;, &quot;salt&quot;;\">With Sessions:</em></span></div><ul class=\"postList\" style=\"margin: 29px 0px 0px; padding: 0px; list-style: none none; counter-reset: post 0; color: rgba(0, 0, 0, 0.84); font-family: medium-content-sans-serif-font, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px;\"><li name=\"17bf\" id=\"17bf\" class=\"graf graf--li graf-after--p\" style=\"margin-left: 30px; margin-bottom: 14px; --x-height-multiplier:0.375; --baseline-multiplier:0.17; font-family: medium-content-serif-font, Georgia, Cambria, &quot;Times New Roman&quot;, Times, serif; font-size: 21px; line-height: 1.58; letter-spacing: -0.003em;\"><span class=\"markup--strong markup--li-strong\" style=\"font-weight: 700;\">Laravel</span>: 521.64 requests per second (mean)</li><li name=\"038e\" id=\"038e\" class=\"graf graf--li graf-after--li\" style=\"margin-left: 30px; margin-bottom: 14px; --x-height-multiplier:0.375; --baseline-multiplier:0.17; font-family: medium-content-serif-font, Georgia, Cambria, &quot;Times New Roman&quot;, Times, serif; font-size: 21px; line-height: 1.58; letter-spacing: -0.003em;\"><span class=\"markup--strong markup--li-strong\" style=\"font-weight: 700;\">Zend</span>: 484.94 requests per second (mean)</li><li name=\"d855\" id=\"d855\" class=\"graf graf--li graf-after--li graf--trailing\" style=\"margin-left: 30px; margin-bottom: 0px; --x-height-multiplier:0.375; --baseline-multiplier:0.17; font-family: medium-content-serif-font, Georgia, Cambria, &quot;Times New Roman&quot;, Times, serif; font-size: 21px; line-height: 1.58; letter-spacing: -0.003em;\"><span class=\"markup--strong markup--li-strong\" style=\"font-weight: 700;\">Symfony</span>: 439.37 requests per second (mean</li></ul></div>', 'Benchmarking Laravel, Symfony, & Zend', '2019-05-15 08:54:11', NULL),
(3, 1, '<div>Symfony Pagination 111<br></div>', 'Symfony Pagination', '2019-05-20 12:09:14', '2019-05-20 12:09:39'),
(4, 2, '<div>sdfdfdfdfdfd<br></div>', 'dss', '2019-08-22 10:48:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL COMMENT '(DC2Type:json_array)',
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `user_name`, `email`, `password`, `image`, `roles`, `created`, `updated`) VALUES
(1, 'Rameshwar', 'Birajdar', 'Rameshwar', 'ram.birajdar55@gmail.com', '$2y$13$u3WYAptWRDrHnkBIMJhDdOWC2GrP51XxHwG/dQ/103GN0PfiwUVT.', '107ef548876545ccfead7de3c90b1580.png', '[\"ROLE_USER\", \"ROLE_ADMIN\"]', '2019-05-14 14:24:50', '2019-08-22 10:58:58'),
(2, 'Rahul', 'Rahul', 'Rahul', 'rahul@gmail.com', '$2y$13$u3WYAptWRDrHnkBIMJhDdOWC2GrP51XxHwG/dQ/103GN0PfiwUVT.', '2a55de1e937f35faa56064787678d47a.png', '[\"ROLE_USER\"]', '2019-05-14 14:27:36', '2019-08-22 10:55:51'),
(11, 'ram', 'Birajdar', 'Ramb', 'rambirajdar1414@gmail.com', '$2y$13$4hNRKN6IrnpzWPDotWlIwunIDQalMAarQIujxZZFy8J4uUtvCAGWS', '4b40b0de21a1c65cff0213be7940c61d.png', '[\"ROLE_USER\"]', '2019-05-22 16:59:57', NULL),
(17, 'ram', 'Birajdar', 'Ramb', 'aaaaaaaaaaa@gmail.com', '$2y$13$57SntoZXZOk1U2ekzcl1DuAoCo.uJeekClEWYSA3lc8OskGLqAVtS', '618446fd05d0d38461fb2b0d96db64a8.png', '[\"ROLE_USER\"]', '2019-06-07 16:08:35', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526C4B89032C` (`post_id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`);

--
-- Indexes for table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5A8A6C8D5A6D2235` (`posted_by_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_5A8A6C8D5A6D2235` FOREIGN KEY (`posted_by_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
