USE 4vchef_db;

INSERT INTO `recipe_types` (`id`, `name`, `description`, `deleted_at`) VALUES
(1, 'Postre', 'Platos dulces para finalizar la comida', NULL),
(2, 'Principal', 'Platos fuertes del menú', NULL),
(3, 'Entrante', 'Aperitivos y platos ligeros', NULL);

INSERT INTO `nutrient_types` (`id`, `name`, `unit`, `deleted_at`) VALUES
(1, 'Calorías', 'kcal', NULL),
(2, 'Proteínas', 'gr', NULL),
(3, 'Grasas', 'gr', NULL),
(4, 'Carbohidratos', 'gr', NULL);

INSERT INTO `recipes` (`id`, `title`, `number_diner`, `recipe_type_id`, `deleted_at`) VALUES
(1, 'Tiramisu', 4, 1, NULL),
(2, 'Paella Valenciana', 6, 2, NULL),
(3, 'Ensalada César', 2, 3, NULL);

INSERT INTO `ingredients` (`name`, `quantity`, `unit`, `recipe_id`, `deleted_at`) VALUES
('Queso Mascarpone', 500.00, 'gr', 1, NULL),
('Huevos', 4.00, 'ud', 1, NULL),
('Azúcar', 100.00, 'gr', 1, NULL),
('Café', 200.00, 'ml', 1, NULL),
('Bizcochos de soletilla', 200.00, 'gr', 1, NULL),
('Cacao en polvo', 30.00, 'gr', 1, NULL);

INSERT INTO `ingredients` (`name`, `quantity`, `unit`, `recipe_id`, `deleted_at`) VALUES
('Arroz Bomba', 500.00, 'gr', 2, NULL),
('Pollo', 400.00, 'gr', 2, NULL),
('Conejo', 300.00, 'gr', 2, NULL),
('Judía verde', 200.00, 'gr', 2, NULL),
('Garrofó', 100.00, 'gr', 2, NULL),
('Azafrán', 0.50, 'gr', 2, NULL);

INSERT INTO `steps` (`order_step`, `description`, `recipe_id`, `deleted_at`) VALUES
(1, 'Separar las completas y las yemas de los huevos.', 1, NULL),
(2, 'Batir las yemas con el azúcar hasta que blanqueen.', 1, NULL),
(3, 'Añadir el mascarpone y mezclar suavemente.', 1, NULL),
(4, 'Montar las claras a punto de nieve e incorporar a la mezcla.', 1, NULL),
(5, 'Mojar los bizcochos en el café y colocar en la base.', 1, NULL),
(6, 'Cubrir con la crema y refrigerar 4 horas.', 1, NULL);

INSERT INTO `steps` (`order_step`, `description`, `recipe_id`, `deleted_at`) VALUES
(1, 'Sofreír la carne con aceite de oliva.', 2, NULL),
(2, 'Añadir la verdura y rehogar.', 2, NULL),
(3, 'Agregar el tomate rallado y el pimentón.', 2, NULL),
(4, 'Añadir el agua y dejar cocer 20 minutos.', 2, NULL),
(5, 'Incorporar el arroz y el azafrán.', 2, NULL),
(6, 'Cocer 18 minutos hasta que el arroz esté seco.', 2, NULL);

INSERT INTO `recipe_nutrients` (`recipe_id`, `nutrient_type_id`, `quantity`, `deleted_at`) VALUES
(1, 1, 450.00, NULL),
(1, 2, 8.50, NULL),
(1, 3, 25.00, NULL),
(1, 4, 30.00, NULL);

INSERT INTO `recipe_nutrients` (`recipe_id`, `nutrient_type_id`, `quantity`, `deleted_at`) VALUES
(2, 1, 350.00, NULL),
(2, 2, 12.00, NULL),
(2, 3, 15.00, NULL);

INSERT INTO `ratings` (`rate`, `recipe_id`, `created_at`, `deleted_at`, `ip`) VALUES
(5, 1, NOW(), NULL, '127.0.0.1'),
(4, 1, NOW(), NULL, '127.0.0.2'),
(5, 1, NOW(), NULL, '127.0.0.3'),
(3, 2, NOW(), NULL, '127.0.0.1'),
(4, 2, NOW(), NULL, '127.0.0.2'),
(5, 2, NOW(), NULL, '127.0.0.3');