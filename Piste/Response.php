<?php
namespace Piste;
/*=head1 Name
Piste\Response

=head1 DESCRIPTION

*/
Class Response {
    private $view;
    private $stash = array();
    private $body = 'No content set';

    public function view($view = null){
        if ($view){ $this->view = $view; }
        return $this->view;
    }

    public function stash($params = null, $value = null){
        if ($value & !is_string($params)){
            throw new Exception("Bad parameters to Piste\\Response::stash() method. Using 2 parameter form, the first value must be a string key");
        }
        if ($value){
            $this->stash[$params] = $value;
        } elseif ($params) {
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

    public function respond(){
        echo $this->body;
    }

}

?>
