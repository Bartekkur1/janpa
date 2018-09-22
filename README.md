# Janpa ![janpa-logo](https://github.com/Bartekkur1/janpa/images/janpa-logo-small.png "Logo")

### Janpa is ultra lightweight PHP MVC framework. Created for my school project and because other PHP frameworks had lots of things that I didn’t used at all and some that I couldn't find usage for. Whole janpa framework have around 600 lines of code. Framework uses built-in library that contains routing and few super classes.

<hr>

### Instaling
  This version do not contain any frontend pages :( 
  1. Download repo
  2. Setup your server (I uses xampp) and drop all files there
  3. Update your config.ini file for database connection
  4. Delete example controllers & models (optional)
  5. Done, ready to go!
<hr>

### Here's how directory looks like. Htaccess isolates app directory from unwanted access. 
In app directory lies everything, models, controllers and the most important config.ini file where database password is stored.
Public directory don't have htaccess so it can be accessed by everyone, our images and stylesheet. Index in main directory is our routing page, we will write all paths there.

* app
  * controllers
    * BlogController.php
    * UserController.php
  * lib
    * Controller.php
    * Input.php
    * Model.php
    * QueryBuilder.php
    * Router.php
    * Time.php
  * model
    * Blog_model.php
	  * User_model.php
  * view
    * index.php
  * htaccess
  * config.ini
* public
  * headers.php
  * css
    * style.css
* htaccess
* index.php <- our routing page


<hr>

I created a schema that shows how this thing works.
![schema](https://github.com/Bartekkur1/janpa/images/schema-small.png "Schema")

### Let's go through that schema with with code examples
1. User uses some kind of interaction with server, user may send input like POST request or just type server link in browser. For example let's take a look at user that trying to access article at our blog page.
> webaddress/article/1
2. Router checks if there is a route like "/article" in our main index file which is our routing page.
```php
foreach ($this->routes as $route) {
  if ($route->path == $path) {
    // route found - code continues
  }
}
// if route is not found
echo "<h1>Page not found</h1>";
```
3. Every route have optional variable for securing
```php
  $router->Map("/new_post", "BlogController/NewPost", true);
  $router->Map("/login", "UserController/Login");
```
In this example route "/new_post" is secured by third argument (true) given in maping function (by default its set to false)

4. Login is ingrained deep intro rouing. It means that if you want to make some kind of login system on your webpage you have to use session["user"] to make it through router security without permision deined or edit router.
```php
if($route->secure) {
  if(!isset($_SESSION["user"])) {
    echo "<h1>Permision denied</h1>";
    header("Location: /login");
    die;
  }
}
```
I know this isn't the best idea but it works so Im not changing it for now.

5. Creates instance of class from routing class name and includes input class as basic Controller library.

```php
$controller = new $route->controller_name;
$method_params = array();
foreach($route->params as $id => $param) {
    if(!empty($full_path[$id-1])) {
        array_push($method_params, $full_path[$id-1]);
    } else {
        array_push($method_params, null);
    }
}
call_user_func_array(array($controller, $route->function_name), $method_params);
die;
```

In php you can create class instance just of a its name given by string.

6. Using class function given in index. 
```php
//index route
$router->Map("/article/id", "BlogController/ViewArticle");
 
//this function gets called from BlogController
public function ViewArticle($id) {
    $this->load_model("Article_model");
    $article = $this->Article_model->get_by_id($id);
    $this->render("article", array(
        "article" => $article,
    ));
}
```

In this controller function we use "Article_model" to load article from
database with our id given in GET method, at the end rendering article
view with our article data/object.

7. Loading models into our controller.

After checking if file exists it requires once so that it can create a new class object containing given model.

```php
// example used in BlogController
$this->load_model("Blog_model");

//Function simply checks if model exists and then creates instance of it in given controller.
function load_model($model_name)
{
    if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php")) {
        require_once $_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php";
    } else {
        echo "Model named : $model_name not found";
        die;
    }
    $this->$model_name = new $model_name;
}
```

8. Model and QueryBuilder
```php
class Model
{
    function __construct()
    {
        $this->qb = new QueryBuilder();
    }
}
```

Every model inherits from "mother model" class database connection so its not establishing connection for every model.

**Query builder itself is big, 200 lines of functions we can use to interact with database.
Even that I created it myself sometimes I have to look there for a function that im not sure is there or I don’t exactly remember how it works. Im not going to make a querybuilder documentation, I don’t have time and patient…**

##### Model examples : 

  1. Our example blog model which returns article object from database.
  ```php
function get_by_id($id) {
    $this->qb->select("posts");
    $this->qb->where(array(
        "id" => $id,
    ));
    return $this->qb->execute();
}

//after loading model inside controller we can do something like that
$article = $this->Article_model->get_by_id($id);
  ```
  2. Again blog model example. This one deletes article from database by given id.
  ```php
public function delete($id) {
    $this->qb->delete("posts");
    $this->qb->where(array(
        "id" => $id,
    ));
    return $this->qb->execute();
}

//usage
$this->Blog_model->delete($id)
  ```







