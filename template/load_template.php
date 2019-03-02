<?php

if (isset($_POST) && !empty($_POST)) {

	echo file_get_contents($_POST["lang_id"]);

}