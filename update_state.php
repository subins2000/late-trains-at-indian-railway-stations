<?php
header('Access-Control-Allow-Origin: *');
file_put_contents("state.json", file_get_contents($_FILES["file"]["tmp_name"]));
