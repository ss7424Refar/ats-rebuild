-- ats1.3.0.3

-- add dmi_manufacturer result_path
ALTER TABLE `tpms`.`ats_task_basic`
ADD COLUMN `dmi_manufacturer` VARCHAR(50) NULL DEFAULT NULL AFTER `shelf_switch`,
ADD COLUMN `result_path` VARCHAR(100) NULL DEFAULT NULL AFTER `tester`;

-- update result_path from steps
update ats_task_basic a
  left join ats_task_tool_steps b
    on a.task_id = b.task_id
set a.result_path = b.result_path;

-- ats1.4.0.2

use tpms;

update ats_task_tool_steps
set tool_name = 'FastBoot', element_json = '{"Tool_Name": "FastBoot", "Tool_Type": "FastBoot"}'
where tool_name = 'Others';

-- ats1.5.0.0

CREATE TABLE `tpms`.`ats_common_tool_config` (
  `name` VARCHAR(40) NOT NULL,
  `detail` TEXT NULL,
  `user` VARCHAR(20) NULL,
  `add_time` DATETIME NULL,
  `remark` VARCHAR(100) NULL,
  PRIMARY KEY (`name`));

--ats1.5.0.4

ALTER TABLE `tpms`.`ats_task_tool_steps`
CHANGE COLUMN `tool_name` `tool_name` VARCHAR(15) NULL DEFAULT NULL COMMENT '	' ;


-- ats1.6.0.0

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

-- ats1.6.0.1 open-close功能
CREATE TABLE `tpms`.`ats_image_report` (
  `name` VARCHAR(40) NOT NULL,
  `support` TEXT NULL,
  `xml` VARCHAR(60) NULL,
  `uploader` VARCHAR(15) NULL,
  `add_time` DATETIME NULL,
  PRIMARY KEY (`name`));