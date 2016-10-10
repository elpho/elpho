<?php
  namespace elpho\system;

  use elpho\lang\StaticType;

  final class Starter extends StaticType{
    private static $entryMethod;
    private static $exitMethod;
    private static $started = false;

    public static function start(){
      if (self::$started)
        throw new \Exception("Starter can only be run once!");

      self::registerMain();
      register_shutdown_function(array("\\elpho\\system\\Starter","callPrimaryMethods"));
      self::$started = true;
    }

    private static function registerEntry($method){
      self::$entryMethod = $method;
    }

    private static function registerExit($method){
      self::$exitMethod = $method;
    }

    private static function callEntry($args=array()){
      if(!self::$entryMethod)
        return;

      if(!method_exists(self::$entryMethod[0],self::$entryMethod[1]))
        return;

      call(self::$entryMethod,$args);
    }

    private static function callExit(){
      if(!self::$exitMethod)
        return;

      if(!method_exists(self::$exitMethod[0],self::$exitMethod[1]))
        return;

      call(self::$exitMethod);
    }

    private static function registerEntryClass($name){
      $target = $name;
      if(!class_exists($target))
        $target = '\\main\\'.$target;

      if(!class_exists($target))
        throw new Exception("Could not find entry class '$name' at global or main namespace");

      self::registerEntry(array($target,"main"));
      self::registerExit(array($target,"shutdown"));
    }

    private static function fixShutdownScope(){
      chdir(dirname($_SERVER["SCRIPT_FILENAME"]));
    }

    public static function callPrimaryMethods(){
      try{
        self::fixShutdownScope();
        self::callEntry(parse_str(file_get_contents("php://input"),$inputData));
        self::callExit();
      }catch(\Exception $e){
        echo $e->getMessage();
      }
    }

    public static function registerMain($filename=null){
      if(!$filename)
        $filename = $_SERVER["SCRIPT_FILENAME"];

      $currentClass = basename($filename,".php");
      self::registerEntryClass($currentClass);
    }
  }
