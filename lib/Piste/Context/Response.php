<?php
namespace Piste\Context;
/*=head1 Name
Piste\Context\Response

=head1 DESCRIPTION


=head1 DEPENDENCIES
=cut*/
require_once('Piste/Context/Response/Headers.php');

/*=head1 Synopsis

*/
Class Response {
    private $view;
    private $stash = array();
    private $template;
    private $headers;
    private $body = '';

    function __construct(){
        $this->headers = new Response\Headers();
    }

    # the string representation of the view class chosen
    # TODO: move this to $pc->view($viewname)
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

    # get/set a template for rendering output
    # TODO: this should probably be in the $pc->view->interface object too huh?
    public final function template($template = null){
        if (isset($template)){
            $this->template = $template;
        }
        return $this->template;
    }

    public function body($body = null){
        if (isset($body)){ $this->body = $body; }
        return $this->body;
    }

    public function respond(){
        $this->headers->respond();
        echo $this->body;
    }

    # pass on header methods
    public function redirect($url){
        $this->headers->redirect($url);
    }
    public function status($status){
        $this->headers->status($status);
    }
    public function content_type($ct){
        $this->headers->content_type($ct);
    }

}

?>
