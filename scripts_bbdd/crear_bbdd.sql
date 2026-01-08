CREATE DATABASE 4vchef_db;
USE 4vchef_db;

CREATE TABLE `recipe_types` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `nutrient_types` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `unit` VARCHAR(50) NOT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `recipes` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `number_diner` INT NOT NULL,
  `recipe_type_id` BIGINT NOT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_recipes_recipe_types`
    FOREIGN KEY (`recipe_type_id`)
    REFERENCES `recipe_types` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE `ingredients` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `quantity` DECIMAL(10,2) NOT NULL,
  `unit` VARCHAR(50) NOT NULL,
  `recipe_id` BIGINT NOT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ingredients_recipes`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `recipes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE `steps` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `order_step` INT NOT NULL COMMENT 'Renamed from order to avoid keyword conflict',
  `description` TEXT NOT NULL,
  `recipe_id` BIGINT NOT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_steps_recipes`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `recipes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE `recipe_nutrients` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `quantity` DECIMAL(10,2) NOT NULL,
  `recipe_id` BIGINT NOT NULL,
  `nutrient_type_id` BIGINT NOT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_rn_recipes`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `recipes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_rn_nutrient_types`
    FOREIGN KEY (`nutrient_type_id`)
    REFERENCES `nutrient_types` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE `ratings` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `rate` INT NOT NULL CHECK (`rate` >= 0 AND `rate` <= 5),
  `recipe_id` BIGINT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ratings_recipes`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `recipes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);