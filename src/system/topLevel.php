<?php
  function registerMain($filename=null){
    \elpho\system\Starter::registerMain($filename);
  }
  function call($callback,$args=null,$_=null){
    $args = func_get_args();
    array_shift($args);
    return call_user_func_array($callback,$args);
  }
  function apply($callback,$args=array()){
    return call_user_func_array($callback,$args);
  }

  function matchTypes($type,$_=null){
    $trace = debug_backtrace();
    $types = func_get_args();
    $arguments = $trace[1]["args"];

    $i = -1;
    foreach($types as $type){
      $i++;
      $argument = $arguments[$i];

      if(is_object($argument)){
        if(!is_a($argument,$type)) return false;
        continue;
      }

      if(gettype($argument) !== $type) return false;
    }
    return true;
  }
