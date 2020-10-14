CREATE TABLE `tpms`.`ats_common_tool_config` (
  `name` VARCHAR(40) NOT NULL,
  `detail` TEXT NULL,
  `user` VARCHAR(20) NULL,
  `add_time` DATETIME NULL,
  `remark` VARCHAR(100) NULL,
  PRIMARY KEY (`name`));
