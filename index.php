<?php
use Steampixel\Route;
include 'src/Steampixel/Route.php';

define('BASEPATH','/');
function navi() {
  render("navbar.php");
}
function render($template, $params = array(), $directory = 'templates') {
  extract($params);
  ob_start(); 
  include($directory . '/' . $template);
  $applied_template = ob_get_contents();
  ob_end_clean();
  echo $applied_template;
}
// Add base route (startpage)
Route::add('/', function() {
  header("Location: /ro/home");
  die();
});
Route::add('/ro/home', function() {
  render("home.php");
});
Route::add('/ro/oferta-noastra', function() {
  render("oferta-noastra.php");
});
Route::add('/blog/avantajewebsite', function() {
  render("avantajewebsite.php");
});
// Another base route example

// Route with regexp parameter
// Be aware that (.*) will match / (slash) too. For example: /user/foo/bar/edit
// Also users could inject SQL statements or other untrusted data if you use (.*)
// You should better use a saver expression like /user/([0-9]*)/edit or /user/([A-Za-z]*)/edit
Route::add('/user/(.*)/edit', function($id) {
  navi();
  echo 'Edit user with id '.$id.'<br>';
});

// Accept only numbers as parameter. Other characters will result in a 404 error
Route::add('/foo/([0-9]*)/bar', function($var1) {
  navi();
  echo $var1.' is a great number!';
});

// Crazy route with parameters
Route::add('/(.*)/(.*)/(.*)/(.*)', function($var1,$var2,$var3,$var4) {
  navi();
  echo 'This is the first match: '.$var1.' / '.$var2.' / '.$var3.' / '.$var4.'<br>';
});

// Long route example
// By default this route gets never triggered because the route before matches too
Route::add('/foo/bar/foo/bar', function() {
  echo 'This is the second match (This route should only work in multi match mode) <br>';
});

// Trailing slash example
Route::add('/aTrailingSlashDoesNotMatter', function() {
  navi();
  echo 'a trailing slash does not matter<br>';
});

// Case example
Route::add('/theCaseDoesNotMatter',function() {
  navi();
  echo 'the case does not matter<br>';
});

// 405 test
Route::add('/this-route-is-defined', function() {
  navi();
  echo 'You need to patch this route to see this content';
}, 'patch');

Route::pathNotFound(function($path) {
  header('HTTP/1.0 404 Not Found');
  echo 'Error 404 :-(<br>';
  echo 'The requested path "'.$path.'" was not found!';
});

Route::methodNotAllowed(function($path, $method) {
  header('HTTP/1.0 405 Method Not Allowed');
  navi();
  echo 'Error 405 :-(<br>';
  echo 'The requested path "'.$path.'" exists. But the request method "'.$method.'" is not allowed on this path!';
});
Route::run(BASEPATH);