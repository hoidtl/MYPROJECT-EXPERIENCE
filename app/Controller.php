<?php
class Controller {

    // Load model
    public function model($model){
        require_once "./models/" . $model . ".php";
        return new $model;
    }

    // Load view (KHÔNG BAO GIỜ DÙNG require_once)
    public function view($view, $data = []){
        extract($data); // giúp dùng biến trực tiếp trong View
        require "./views/" . $view . ".php";
    }
}
