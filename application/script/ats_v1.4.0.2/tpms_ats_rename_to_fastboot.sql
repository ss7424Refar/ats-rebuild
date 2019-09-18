use tpms;

update ats_task_tool_steps
set tool_name = 'FastBoot', element_json = '{"Tool_Name": "FastBoot", "Tool_Type": "FastBoot"}'
where tool_name = 'Others';