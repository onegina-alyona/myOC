<?php 
include("lang_list.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>My Online Compiler</title>
	<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
	<script type="text/javascript" src="assets/jquery.min.js"></script>
</head>
<body>
	<div>
		<select id="lang_id">
			<?php foreach ($langs as $key => $val): ?>
			<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div>
		<textarea id="code" name="code" style="width:100%; height:30vh;"><?php echo file_get_contents("./sample/main.cpp"); ?>
		</textarea>
	</div>

	<div>
		<textarea id="stdin" name="stdin" style="width:100%; height:25vh;">100000</textarea>
	</div>

	<div>
		<textarea id="stdout" name="stdout" style="width:100%; height:25vh;"></textarea>
	</div>

	<button id="submit_button">Submit</button>

	<script type="text/javascript">

		var langs = <?php echo json_encode($langs); ?>;

		$('#submit_button').click(() => {
			$.ajax({
				type: 		"POST",
				url: 		"submit.php",
				dataType: 	"json", 
				data: {
					lang_id: $('#lang_id').val(),
					source:  $('#code').val(),
					input: 	 $('#stdin').val()
				}
			})
			.done((data, textStatus, jqXHR) => {
				$('#stdout').val(JSON.stringify(data));
			});
		});

		$('#lang_id').change((e) => {			
			$.ajax({
				type: 		"POST",
				url: 		"./template/load_template.php",
				data: {
					lang_id: $('#lang_id').val(),
				}
			})
			.done((data, textStatus, jqXHR) => {
				$('#code').val(data);
			});
		});

		$(document).ready(() => {
			$.ajax({
				type: 		"POST",
				url: 		"./template/load_template.php",
				data: {
					lang_id: $('#lang_id').val(),
				}
			})
			.done((data, textStatus, jqXHR) => {
				$('#code').val(data);
			});
		});

	</script>
</body>
</html>