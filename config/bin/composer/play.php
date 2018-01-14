<?php

if(file_exists(str_replace('config/bin/composer', '', dirname(__FILE__))."config/bin/composer/composerAutoExecute.php")){
	require_once str_replace('config/bin/composer', '', dirname(__FILE__))."config/bin/composer/composerAutoExecute.php";
}