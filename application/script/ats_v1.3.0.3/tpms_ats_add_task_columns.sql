-- add dmi_manufacturer result_path
ALTER TABLE `tpms`.`ats_task_basic`
ADD COLUMN `dmi_manufacturer` VARCHAR(50) NULL DEFAULT NULL AFTER `shelf_switch`,
ADD COLUMN `result_path` VARCHAR(100) NULL DEFAULT NULL AFTER `tester`;

-- update result_path from steps
update ats_task_basic a
  left join ats_task_tool_steps b
    on a.task_id = b.task_id
set a.result_path = b.result_path;