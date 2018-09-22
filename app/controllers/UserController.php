<?php

class UserController extends Controller
{

    public $error_msg, $success_msg;

    public function Login()
    {
        $this->load_model("User_model");
        $this->User_model->is_logged() ? header("Location: /") : "";
        if($form = $this->Input->Post()) {
            if($this->User_model->user_exists($form)) {
                $this->User_model->login($form) ? header("Location: /") : $this->error_msg = "Wrong password";
            } else {
                $this->error_msg = "User doesnt exist";
            }
        }
        $this->render("login", array(
            "error_msg" => $this->error_msg,
            "success_msg" => $this->success_msg,
        ));
    }

    public function Logout()
    {
        $this->load_model("User_model");
        $this->User_model->logout();
        header("Location: /login");
    }

}