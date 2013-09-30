SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `restaurant_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `restaurant_db` ;

-- -----------------------------------------------------
-- Table `restaurant_db`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `restaurant_db`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  `email` VARCHAR(255) NULL ,
  `ip_address` VARCHAR(255) NULL ,
  `created_at` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restaurant_db`.`categories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `restaurant_db`.`categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restaurant_db`.`restaurants`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `restaurant_db`.`restaurants` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `yelp_id` TEXT NULL ,
  `name` VARCHAR(255) NULL ,
  `address` VARCHAR(255) NULL ,
  `city` VARCHAR(100) NULL ,
  `state` VARCHAR(50) NULL ,
  `country` VARCHAR(50) NULL ,
  `zip_code` VARCHAR(45) NULL ,
  `contact_number` VARCHAR(255) NULL ,
  `website` TEXT NULL ,
  `image_path` TEXT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restaurant_db`.`reviews`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `restaurant_db`.`reviews` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `restaurant_id` INT(11) NOT NULL ,
  `category_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  `rating` FLOAT NULL ,
  `times_rated` FLOAT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ratings_categories1_idx` (`category_id` ASC) ,
  INDEX `fk_reviews_users1_idx` (`user_id` ASC) ,
  INDEX `fk_reviews_restaurants1_idx` (`restaurant_id` ASC) ,
  CONSTRAINT `fk_ratings_categories1`
    FOREIGN KEY (`category_id` )
    REFERENCES `restaurant_db`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reviews_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `restaurant_db`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reviews_restaurants1`
    FOREIGN KEY (`restaurant_id` )
    REFERENCES `restaurant_db`.`restaurants` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `restaurant_db`.`comments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `restaurant_db`.`comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `restaurant_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  `title` TEXT NULL ,
  `comment` TEXT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_comments_restaurants1_idx` (`restaurant_id` ASC) ,
  INDEX `fk_comments_users1_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_comments_restaurants1`
    FOREIGN KEY (`restaurant_id` )
    REFERENCES `restaurant_db`.`restaurants` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `restaurant_db`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `restaurant_db` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
