use croissantShow;

--
-- Create Admin
--
INSERT INTO `users` (`prenom`, `nom`, `mail`, `login`, `is_admin`, `is_activ`, `date_creation_compte`, `mot_de_passe`, `croissant_buy`) VALUES ( 'root', 'root', 'root@root.fr', 'root', '1', '1', current_timestamp(), '$2y$10$7aZrDGDTbhEiUf9NAYfs2uJNoARVHCI/Yxf1O3SGQmjBTAEm0LuS.', '0');