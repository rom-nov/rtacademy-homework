<!doctype html>
<html lang="uk">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" type="text/css" href="./View/style.css">
	<title>11.9</title>
</head>
<body>
<form id="form" action="" method="get">
    <div class="form_row">
        <label for="country">Країна</label>
        <select name="country" id="country">
            <option value="">Оберіть країну...</option>
			<?php
				foreach( $countries as $value )
				{
					echo('<option value=' . $value . '>' .  $value . '</option>');
				}
			?>
        </select>
    </div>
	<div class="form_row">
		<label for="city">Місто</label>
        <input id="city" name="city" list="citiesList">
        <datalist id="citiesList">
        </datalist>
	</div>
</form>
<script src="./View/script.js"></script>
</body>
</html>