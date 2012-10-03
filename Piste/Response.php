<?php
namespace Piste;
/*=head1 Name
Piste\Response

=head1 DESCRIPTION

*/
Class Response {
    private $view;
    private $stash = array();
    private $args = array();
    private $body = 'No content set';
    private $return_404 = false;

    public function view($view = null){
        if ($view){ $this->view = $view; }
        return $this->view;
    }

    public function stash($params = null, $value = null){
        if ($value !== null & !is_string($params)){
            throw new Exception("Bad parameters to Piste\\Response::stash() method. Using 2 parameter form, the first value must be a string key");
        }
        if ($value !== null){
            # setting in 2 param form
            $this->stash[$params] = $value;
        } elseif (is_string($params)) {
            # getting a stash param
            return isset($this->stash[$params]) ? $this->stash[$params] : null;
        } elseif ($params){
            # setting in one param form
            $this->stash = array_merge($this->stash, $params);
        }
        return $this->stash;
    }

    public function clear_stash(){
        $this->stash = array();
    }

    public function body($body = null){
        if ($body){ $this->body = $body; }
        return $this->body;
    }

    public function redirect($url){
        # TODO : test redirects
        # should return a REDIRECT 302 to browser
        header("Location: $url");
        exit;
    }

    public function return_404($set = null){
        if (isset($set)){
            # TODO for regular apache 404
            # header("HTTP/1.0 404 Not Found");
            # Wheras for fcgi 404 you need to do 
            # header("Status: 404 Not Found");
            header("HTTP/1.0 404 Not Found");
            $this->return_404 = $set;
        }
        return $this->return_404;
    }

    public function respond(){
        echo $this->body;
    }

}

?>
