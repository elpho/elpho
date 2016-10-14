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

      self::registerEntryFile($_SERVER["SCRIPT_FILENAME"]);
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

    public static function registerEntryClass($target, $noError=false){
      self::$entryMethod = null;
      self::$exitMethod = null;

      if(!class_exists($target)){
        if(!defined("DEBUG") || $noError)
          return;

        throw new \Exception("Could not find entry class '$target'");
      }

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

    private static function registerEntryFile($filename=null, $namespace=""){
      if(!$filename)
        return;

      $currentClass = basename($filename,".php");
      $target = $namespace.'\\'.$currentClass;

      self::registerEntryClass($target, true);
    }
  }
