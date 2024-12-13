<?php
require dirname(__DIR__) . "/include/env.php";
if(IS_DEV){
    require "/../main_script_dev/include/mainInclude.php";
} else {
    require "/../main_script/include/mainInclude.php";
}