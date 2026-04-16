-- Migration to insert blue/orange theme colors to replace red colors
-- This ensures the system uses blue (#2196F3) and orange (#FF9800) instead of red

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
('theme_color_text_secondary', '#b0b0b0')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);