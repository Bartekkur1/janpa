<?php

class Blog_model extends Model {

    /**
     * @param array $form 
     * should contain database rows 
     * example. if you have posts table and there
     * row content, form should looks like this
     * $form["content"]
     */
    public function add($form) {
        $this->qb->insert("posts", $form);
        return $this->qb->execute();
    }

    // deletes post by given id
    public function delete($id) {
        $this->qb->delete("posts");
        $this->qb->where(array(
            "id" => $id,
        ));
        return $this->qb->execute();
    }

    // returns post object by given id
    public function get_by_id($id) {
        $this->qb->select("posts");
        $this->qb->where(array(
            "id" => $id,
        ));
        return $this->qb->execute();
    }

    // returns all posts
    public function get_all() {
        $this->qb->select("posts");
        $this->qb->where();
        return $this->qb->execute();
    }

}