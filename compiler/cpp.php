<?php

$run_id = time();

$result = array(
	'run_id' => $run_id,
	'status' => array(
		'code' => "",
		'message' => ""
	),
	'time_cost' => "",
	'memory_cost' => "",
	'output' => "",
	'source' => $source
);

$lang_dir = "/var/tmp/{$lang_id}";

$running_dir = "{$lang_dir}_{$run_id}";

// permission issue
if (!mkdir($running_dir, 0777, true)) {
	$result['status']['code'] = "permission_issue";
	$result['status']['message'] = "Permission Denined: No generate directory";
	die(json_encode($result));
} 

$handle = fopen("{$running_dir}/main.cpp", "w");
fwrite($handle, $source);
fclose($handle);
exec("chmod -R 777 {$running_dir}");

$compile_cmd = "bash ./compiler/{$lang_id}.sh {$running_dir}";
shell_exec($compile_cmd);
$message = file_get_contents("{$running_dir}/error.txt");
exec("chmod -R 777 {$running_dir}");

//compile error
if (!empty($message)) { 
	$result['status']['code'] = "compile_error";
	$result['status']['message'] = $message;
	die(json_encode($result));
}

// permission issue
if (!file_exists("{$running_dir}/main")) {
	$result['status']['code'] = "permission_issue";
	$result['status']['message'] = "Permission Denined: No generate executable file";
	die(json_encode($result));
}

$start_time = microtime(true);

// exist input data
$handle = fopen("{$running_dir}/in.txt", "w");
fwrite($handle, $input);
fclose($handle);
exec("chmod -R 777 {$running_dir}");

$exec_cmd = "bash ./compiler/memory_cost.sh ./main {$running_dir}";

die($exec_cmd);


$memory_cost = shell_exec($exec_cmd);
exec("chmod -R 777 {$running_dir}");

// runtime error
$message = trim(file_get_contents("{$running_dir}/error.txt"));
if (!empty($message)) { 
	$result['status']['code'] = "runtime_error";
	$result['status']['message'] = $message;
	die(json_encode($result));
}

// time limit exceed
if (empty(trim(file_get_contents("{$running_dir}/output.txt")))) {
	$result['status']['code'] = "Timelimit Exceed Error";
	$result['status']['message'] = "Timelimit Exceed Error";
	die(json_encode($result));	
}

$result['status']['code'] = 'ok';
$result['output'] = $output;
$result['time_cost'] = microtime(true) - $start_time;
$result['memory_cost'] = $memory_cost;

// exec("rm -rf {$running_dir}");

echo json_encode($result);
