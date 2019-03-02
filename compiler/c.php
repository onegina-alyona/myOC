<?php

// running directory path: /var/tmp/cpp/timestamp
// compile with docker: docker run --rm -v \"$PWD\":/usr/src/main -w /usr/src/main gcc:4.9 gcc -o main main.c

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

if (!mkdir($running_dir, 0777, true)) {

	$result['status']['message'] = 'Failure of generation directory';
	die(json_encode($result));

} 

$handle = fopen("{$running_dir}/main.c", "w");
fwrite($handle, $source);
fclose($handle);
exec("chmod -R 777 {$running_dir}");

$compile_cmd = "bash ./compiler/{$lang_id}.sh {$running_dir}";
shell_exec($compile_cmd);
$message = file_get_contents("{$running_dir}/error.txt");
exec("chmod -R 777 {$running_dir}");

if (!empty($message)) { //compile error

	$result['status']['code'] = "Compile Error";
	$result['status']['message'] = $message;
	die(json_encode($result));

}

if (!file_exists("{$running_dir}/main")) { // permission issue

	$result['status']['code'] = "Permission Issue";
	$result['status']['message'] = "Permission Denined: No generate executable file";
	die(json_encode($result));

}

$start_time = microtime(true);

if (!empty($input)) { // exist input data

	$handle = fopen("{$running_dir}/in.txt", "w");
	fwrite($handle, $input);
	fclose($handle);
	exec("chmod -R 777 {$running_dir}");

	$output = shell_exec("timeout 5s {$running_dir}/main <{$running_dir}/in.txt 2>{$running_dir}/error.txt");
	// $output = `timeout 5s {$running_dir}/main <{$running_dir}/in.txt 2>{$running_dir}/error.txt`;
	exec("chmod -R 777 {$running_dir}");

} else { // no input data

	$output = shell_exec("timeout 5s {$running_dir}/main 2>{$running_dir}/error.txt");
	exec("chmod -R 777 {$running_dir}");

}

$message = trim(file_get_contents("{$running_dir}/error.txt"));

if (!empty($message)) { // runtime error

	$result['status']['code'] = "Runtime Error";
	$result['status']['message'] = $message;
	die(json_encode($result));

}

$result['status']['code'] = 'ok';
$result['output'] = $output;
$result['time_cost'] = microtime(true) - $start_time;

// exec("rm -rf {$running_dir}");

echo json_encode($result);

