<?php
// Simple router for testing
$uri = $_SERVER['REQUEST_URI'];

if ($uri == '/' || $uri == '/home') {
    echo "<h1>Home Page</h1>";
    echo "<p>Welcome to the test home page!</p>";
    echo "<p><a href='/grades'>Grades</a> | <a href='/questions'>Questions</a> | <a href='/exam'>Exam</a></p>";
} 
elseif ($uri == '/grades') {
    echo "<h1>Grades Page</h1>";
    echo "<p>This is the grades page. It works!</p>";
    echo "<p><a href='/'>Back to Home</a></p>";
} 
elseif ($uri == '/questions') {
    echo "<h1>Questions Page</h1>";
    echo "<p>This is the questions page. It works!</p>";
    echo "<p><a href='/'>Back to Home</a></p>";
} 
elseif ($uri == '/exam') {
    echo "<h1>Exam Page</h1>";
    echo "<p>This is the exam page. It works!</p>";
    echo "<p><a href='/'>Back to Home</a></p>";
} 
else {
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>The requested page '$uri' was not found.</p>";
    echo "<p><a href='/'>Back to Home</a></p>";
} 