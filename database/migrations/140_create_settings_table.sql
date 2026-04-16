-- Create settings table if it doesn't exist
CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default theme colors (blue/orange instead of red)
INSERT INTO settings (`key`, `value`) VALUES 
('theme_color_primary', '#2196F3'),
('theme_color_secondary', '#FF9800'),
('theme_color_accent', '#4CAF50'),
('theme_button_background', '#2196F3'),
('theme_button_background_type', 'gradient'),
('theme_button_text', '#ffffff'),
('theme_button_border', 'transparent'),
('theme_headline_color', '#ffffff'),
('theme_color_background', '#050509'),
('theme_color_surface', '#111118'),
('theme_color_text', '#f5f5f5'),
('theme_color_text_secondary', '#b0b0b0'),
('brand_platform_name', 'Resenha 2.0'),
('brand_platform_short', 'Resenha'),
('brand_mascot_name', 'Tuquinha'),
('brand_agency_name', 'Agência Tuca'),
('brand_slogan', 'Branding vivo na veia'),
('brand_company_name', 'Nuvem Labs'),
('brand_user_agent', 'TuquinhaApp'),
('brand_community_name', 'Comunidade do Tuquinha'),
('brand_logo_url', '')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);