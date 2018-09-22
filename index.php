<?php
    // MVC include
    require "app/lib/Router.php";
    require "app/lib/Controller.php";
    require "app/lib/Model.php";

    // session start
    session_start();

    // routing example
    $router = new Router();
    $router->Map("/login", "UserController/Login");
    $router->Map("/", "BlogController/Index");
    $router->Map("/article/id", "BlogController/ViewArticle");
    $router->Map("/new_post", "BlogController/NewPost", true);
    $router->Map("/delete_post/id", "BlogController/DeletePost", true);
    $router->Start();


    