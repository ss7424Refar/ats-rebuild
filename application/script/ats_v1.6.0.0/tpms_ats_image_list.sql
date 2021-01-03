CREATE TABLE `tpms`.`ats_bind_image_list` (
  `id` VARCHAR(20) NOT NULL,
  `tino` VARCHAR(20) NOT NULL,
  `file_name` VARCHAR(100) NOT NULL,
  `bind_name` TEXT NOT NULL,
  `machine` VARCHAR(45) NULL,
  `os` VARCHAR(45) NULL,
  `region` VARCHAR(10) NULL,
  `phase` VARCHAR(10) NULL,
  `create_time` DATETIME NULL,
  PRIMARY KEY (`id`, `file_name`));


ALTER TABLE `tpms`.`ats_bind_image_list`
CHARACTER SET = utf8 ;