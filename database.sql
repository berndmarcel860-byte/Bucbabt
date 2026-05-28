-- ============================================================
-- Finanzforensik – MySQL Schema
-- German Financial Expert Website
-- ============================================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET collation_connection = 'utf8mb4_unicode_ci';
SET foreign_key_checks = 0;

-- ============================================================
-- Table: leads
-- ============================================================
CREATE TABLE IF NOT EXISTS `leads` (
    `id`              INT            NOT NULL AUTO_INCREMENT,
    `vorname`         VARCHAR(100)   NOT NULL,
    `nachname`        VARCHAR(100)   NOT NULL,
    `telefon`         VARCHAR(50)    NOT NULL,
    `email`           VARCHAR(255)   NOT NULL,
    `verlustbetrag`   VARCHAR(50)    NOT NULL,
    `plattform`       VARCHAR(255)   NOT NULL,
    `zahlungsmethode` VARCHAR(100)   NOT NULL,
    `land`            VARCHAR(100)   NOT NULL,
    `nachricht`       TEXT           DEFAULT NULL,
    `wunschtermin`    DATE           DEFAULT NULL,
    `ip_address`      VARCHAR(45)    NOT NULL,
    `user_agent`      TEXT           DEFAULT NULL,
    `status`          ENUM('neu','in_bearbeitung','abgeschlossen') NOT NULL DEFAULT 'neu',
    `created_at`      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX `idx_leads_email`      ON `leads` (`email`);
CREATE INDEX `idx_leads_status`     ON `leads` (`status`);
CREATE INDEX `idx_leads_created_at` ON `leads` (`created_at`);
CREATE INDEX `idx_leads_ip_address` ON `leads` (`ip_address`);

-- ============================================================
-- Table: termine
-- ============================================================
CREATE TABLE IF NOT EXISTS `termine` (
    `id`       INT   NOT NULL AUTO_INCREMENT,
    `lead_id`  INT   NOT NULL,
    `datum`    DATE  NOT NULL,
    `uhrzeit`  TIME  NOT NULL,
    `art`      ENUM('telefonisch','video','persoenlich') NOT NULL DEFAULT 'telefonisch',
    `notizen`  TEXT  DEFAULT NULL,
    `status`   ENUM('geplant','bestaetigt','abgesagt','abgeschlossen') NOT NULL DEFAULT 'geplant',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_termine_lead`
        FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX `idx_termine_lead_id`   ON `termine` (`lead_id`);
CREATE INDEX `idx_termine_datum`     ON `termine` (`datum`);
CREATE INDEX `idx_termine_status`    ON `termine` (`status`);

-- ============================================================
-- Table: nachrichten
-- ============================================================
CREATE TABLE IF NOT EXISTS `nachrichten` (
    `id`        INT  NOT NULL AUTO_INCREMENT,
    `lead_id`   INT  NOT NULL,
    `absender`  ENUM('admin','mandant') NOT NULL,
    `inhalt`    TEXT NOT NULL,
    `gelesen`   TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_nachrichten_lead`
        FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX `idx_nachrichten_lead_id`    ON `nachrichten` (`lead_id`);
CREATE INDEX `idx_nachrichten_gelesen`    ON `nachrichten` (`gelesen`);
CREATE INDEX `idx_nachrichten_created_at` ON `nachrichten` (`created_at`);

-- ============================================================
-- Table: admins
-- ============================================================
CREATE TABLE IF NOT EXISTS `admins` (
    `id`            INT           NOT NULL AUTO_INCREMENT,
    `username`      VARCHAR(50)   NOT NULL,
    `password_hash` VARCHAR(255)  NOT NULL,
    `email`         VARCHAR(255)  NOT NULL,
    `name`          VARCHAR(100)  NOT NULL,
    `rolle`         ENUM('super_admin','admin') NOT NULL DEFAULT 'admin',
    `last_login`    TIMESTAMP     NULL DEFAULT NULL,
    `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_admins_username` (`username`),
    UNIQUE KEY `uq_admins_email`    (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default super-admin  (change this password immediately after first deployment)
INSERT INTO `admins` (`username`, `password_hash`, `email`, `name`, `rolle`) VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin@finanzforensik.de',
    'Administrator',
    'super_admin'
);

-- ============================================================
-- Table: rate_limits
-- ============================================================
CREATE TABLE IF NOT EXISTS `rate_limits` (
    `id`           INT         NOT NULL AUTO_INCREMENT,
    `ip_address`   VARCHAR(45) NOT NULL,
    `action`       VARCHAR(50) NOT NULL,
    `attempts`     INT         NOT NULL DEFAULT 1,
    `window_start` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at`   TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX `idx_rate_limits_ip_action` ON `rate_limits` (`ip_address`, `action`);
CREATE INDEX `idx_rate_limits_window`    ON `rate_limits` (`window_start`);

-- ============================================================
-- Table: site_settings
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_settings` (
    `id`                    TINYINT      NOT NULL DEFAULT 1,
    `logo_url`              VARCHAR(500) DEFAULT NULL,
    `phone`                 VARCHAR(50)  NOT NULL,
    `accountant_name`       VARCHAR(255) NOT NULL,
    `whatsapp_number`       VARCHAR(30)  NOT NULL,
    `navbar_background_color` VARCHAR(20) NOT NULL DEFAULT '#0a1628',
    `created_at`            TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`            TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `chk_site_settings_single_row` CHECK (`id` = 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`id`, `logo_url`, `phone`, `accountant_name`, `whatsapp_number`, `navbar_background_color`) VALUES
(1, NULL, '+49 800 000 0000', 'Johannes Kiehl', '4989123456789', '#0a1628')
ON DUPLICATE KEY UPDATE
    `phone` = VALUES(`phone`),
    `accountant_name` = VALUES(`accountant_name`),
    `whatsapp_number` = VALUES(`whatsapp_number`),
    `navbar_background_color` = VALUES(`navbar_background_color`);

-- ============================================================
-- Table: site_content
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_content` (
    `id`             TINYINT     NOT NULL DEFAULT 1,
    `hero_title`     TEXT        NOT NULL,
    `hero_subtitle`  TEXT        NOT NULL,
    `footer_tagline` TEXT        NOT NULL,
    `created_at`     TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `chk_site_content_single_row` CHECK (`id` = 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_content` (`id`, `hero_title`, `hero_subtitle`, `footer_tagline`) VALUES
(1,
 'Johannes Kiehl –\nIhr persönlicher Experte für internationale Betrugsfälle',
 'Als erfolgreicher Accounting-Berater für Betrugsplattformen begleite ich Sie bei der vollständigen Aufarbeitung Ihres Falls: präzise Finanzflussanalyse, professionelle Dokumentation und persönliche Betreuung bis zur Einreichung bei Behörden und Anwälten.',
 'Persönlicher Accounting-Berater für internationale Betrugsfälle – professionell, diskret und vertrauenswürdig.'
)
ON DUPLICATE KEY UPDATE
    `hero_title` = VALUES(`hero_title`),
    `hero_subtitle` = VALUES(`hero_subtitle`),
    `footer_tagline` = VALUES(`footer_tagline`);

SET foreign_key_checks = 1;
