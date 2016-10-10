<?php
  if(defined("DEBUG")){
    set_time_limit(DEBUG);
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    $GLOBALS["startup"] = microtime(true);
  }

  require_once('src/system/topLevel.php');
  \elpho\system\Starter::start(__DIR__);
