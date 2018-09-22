<?php

class BlogController extends Controller {

    public $error_msg;

    public function NewPost() {
        $this->load_model("Blog_model");
        if($form = $this->Input->Post()) {
            if($this->Blog_model->add($form)) {
                $this->error_msg = "Blog post added successfully";
            } else {
                $this->error_msg = "Something went wrong...";
            }
        }
        $this->render("new_post", array(
            "error_msg" => $this->error_msg,
        ));
    }

    public function ViewArticle($id) {
        $this->load_model("Article_model");
        $article = $this->Article_model->get_by_id($id);
        $this->render("article", array(
            "article" => $article,
        ));
    }

    public function DeletePost($id) {
        $this->load_model("Blog_model");
        if($this->Blog_model->delete($id)) {
            $this->error_msg = "Deleted successfully";
        } else {
            $this->error_msg = "Something went wrong...";
        }
        $this->render("delete_post", array(
            "error_msg" => $this->error_msg,
        ));
    }

    public function Index() {
        $this->load_model("Blog_model");
        $posts = $this->Blog_model->get_all();
        $this->render("index", array(
            "posts" => $posts,
        ));
    }
}