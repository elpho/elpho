![elpho logo][logo]
Extension Library for PHp Object-orientation
============================================

Your non-invasive, lightweight, fast, dev-tool framework

### WARNING
The framework's core structure is dedicated to solve the leaked scope and procedural ancestry of PHP.
That said, it's nothing but a language extension.

## Hello Core

Here is the very verbose HelloWorld.php example using the `elpho/lang` package:

```php
<?php
   //composer
   require 'vendor/autoload.php';

   //The framework
   require 'vendor/elpho/elpho/startup.php';

   use elpho\lang\String;

   //The class name is same as file without ".php"
   class HelloWorld{
      //Entry method ($args is a parsed php://input)
      public static final function main($args=array()){
         //Wrapper class with lots of functions
         //Not really useful here
         $word = new String("Hello World!");

         //It calls toString() using PHP magic methods
         print($word);
      }
      //(optional) Shutdown method
      public static final function shutdown(){
      }
   }
?>
```

## Hello MVC

Here is the "Hello World" using the `elpho/mvc` package:

```php
<?php
   //composer
   require 'vendor/autoload.php';

   //The framework
   require 'vendor/elpho/elpho/startup.php';

   use elpho\mvc\Router;

   //The entry class
   class Index{
      public static final function main($args=array()){
         //Router
         $router = Router::getInstance(__DIR__);

         $router->map("", array("Home", "index"));
      }
   }
?>
```

```php
<?php
   use elpho\mvc\Controller;
   use elpho\mvc\View;

   //Home controller
   class Home extends Controller{
      public static function index($args){
         //Ideally use the mvc.View class
         $view = new View("template.html.php");
         $view->myMessageAttribute = "Hello World!";
         $view->render();
      }
   }
?>
```

```php
<!-- template.html.php -->
<!DOCTYPE html>
<html>
   <body>
      <p><?=$viewbag->myMessageAttribute?></p>
   </body>
</html>
```

```
   #.htaccess
   RewriteEngine On
   RewriteRule (.*) Index.php [QSD,L]
```

## System
The system folder contains all the framework core files.
Userland functions are declared in the file `system/topLevel.php`, they are:

1. `registerMain(file)`
This method is used when you need to tell elpho that your exposed file is not the main class.
Just pass the main class filename and it will call it when it finishes loading your app.

2. `call(function [, argument...])`
An alias to `call_user_func`.

3. `apply(function , argumentArray)`
An alias to `call_user_func_array`.

4. `matchTypes(type [, type]...)`
Returns true if the list of arguments in the **current function** matches the types passed to it.

## WORK IN PROGRESS
This framework is a work in progress.

Mail me at spark.crz(at)gmail.com if you wanna chat!
Or join `##php` and `##php-br` channels at [freenode.net IRC servers][1]

[1]: http://freenode.net/
[logo]: https://raw.githubusercontent.com/elpho/elpho/master/logo.png
