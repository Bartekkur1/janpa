<?php 
class User_model extends Model
{

    public $error_msg;

    /**
     * return hashed password
     * @param string $password password
     * @return string hashed password
     */
    public function hash_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * checks if session user id is set
     * @return bool success
     */
    public function is_logged()
    {
        return isset($_SESSION["user"]);
    }

    /**
     * destroy session
     */
    public function logout()
    {
        session_unset();
        session_destroy();
    }
    
    /**
     * @param array $loginForm should contain input 
     * to check if user exists in db - checks by user login
     * @return bool $user_exists
     */
    public function user_exists($loginForm)
    {
        $this->qb->exists("users", array(
            "login" => $loginForm["login"],
        ));
        $user_exists = $this->qb->execute();
        return (!empty($user_exists)) ? true : false;
    }

    /**
     * @param array $loginForm should contain input -> login / password
     * @return array $user object to session
     */
    public function login($loginForm)
    {
        $this->qb->select("users", array("password"));
        $this->qb->where(array(
            "login" => $loginForm["login"],
        ));
        $db_password = $this->qb->execute()[0]->password;
        if(password_verify($loginForm["password"], $db_password)) {
            $this->qb->select("users");
            $this->qb->where(array(
                "login" => $loginForm["login"],
            ));
            $_SESSION["user"] = $this->qb->execute();
            return true;
        } else {
            return false;
        }
    }
}
?>