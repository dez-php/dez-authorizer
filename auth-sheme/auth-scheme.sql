CREATE TABLE IF NOT EXISTS `auth_credentials` (
  `id` int(10) unsigned NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `status` enum('active','locked','deleted') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `auth_sessions` (
  `id` int(10) unsigned NOT NULL,
  `auth_id` int(10) unsigned NOT NULL,
  `auth_hash` varchar(36) NOT NULL,
  `unique_hash` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `used_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiry_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `auth_tokens` (
  `id` int(10) unsigned NOT NULL,
  `auth_id` int(10) unsigned NOT NULL,
  `token` varchar(36) NOT NULL,
  `unique_hash` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `used_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiry_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `auth_credentials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `auth_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_hash` (`unique_hash`);

ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_hash` (`unique_hash`);

ALTER TABLE `auth_credentials`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `auth_sessions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `auth_tokens`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

INSERT INTO `auth_credentials` (`id`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'admin@root.local', 'c3d31991-2bae-5240-bafd-52c70c9aedf7', 'active', '2016-01-01 01:00:00', '2016-01-01 01:00:00');
