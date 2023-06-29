CREATE TABLE logs (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(16),
    `user_agent` VARCHAR(255),
    `image_mark` INT UNSIGNED,
    `views_count` INT UNSIGNED,
    `view_date` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `visit` (ip_address, user_agent, image_mark)
);