<?php

if (isset($_POST) && !empty($_POST)) {

	$lang_id = $_POST["lang_id"];
	$source = $_POST["source"];
	$input = $_POST["input"];

	include("compiler/" . $lang_id . ".php");

}