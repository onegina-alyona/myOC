<?php

// $child_id = uniqid();

// $descriptorspec = array(
//    2 => array("file", "./$child_id", "a") // stderr is a file to write to
// );

// $pipes = [];

// proc_open("./main", $descriptorspec, $pipes);

// $memory_used = file_get_contents("./$child_id");

// // at the end of the script
// file_put_contents('php://stderr', memory_get_peak_usage(true));

shell_exec("docker run -i --rm -v \"\$PWD\":/judge -w /judge gcc:4.9 g++ main.cpp -o main -O2 -Wall -lm --static 2>error.txt");

if (!empty($err_msg = file_get_contents("error.txt"))) {
	printf("%s\n", file_get_contents("error.txt"));
	exit();
}

$time_cost = microtime(true);
// $memory_cost = memory_get_usage();

$out = shell_exec("docker run -i --rm -v \"\$PWD\":/judge -w /judge gcc:4.9 timeout 15 ./main<in.txt >out.txt 2>error.txt");

// printf("%s\n", shell_exec("./memcost.sh"));

$time_cost = microtime(true) - $time_cost;
// $memory_cost = memory_get_usage() - $memory_cost;

printf("%.2f\n", $time_cost);
// printf("%f\n", $memory_cost);

