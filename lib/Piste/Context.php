<?php
namespace Piste;
/*=head1 Name
Piste\Context

=head1 DESCRIPTION
Object (TODO singleton? Probably not - you might get leakage accross sessions)
which provides interface for different parts
of the PIste framework and environment to easily communicate and access 
each other.

=head1 DEPENDENCIES
File
=cut*/
require_once('Piste/Context/Request.php');
require_once('Piste/Context/Response.php');
require_once('Piste/Util/Env.php');
require_once('Piste/Util/Cookies.php');
require_once('Piste/Dispatch/Models.php');
require_once('Piste/Context/View.php');
require_once('Piste/Context/Controller.php');

/*=head1 Synopsis
*/
Class Context {
    private $env;
    private $request;
    private $response;
    private $cookies;
    private $action;
    private $models;
    private $view;
    private $controller;

    # capture execution stack for easier deugging
    public $execution_stack = array();
   
    function __construct($app_name){
        $this->env      = new Util\Env($app_name);
        $this->request  = new Context\Request();
        $this->response = new Context\Response();
    }


    # accessors to other objects
    public function env(){return $this->env;}
    public function request(){return $this->request;}
    public function req(){return $this->request;}
    public function response(){return $this->response;}
    public function res(){return $this->response;}
    public function cookies(){
        if (!isset($this->cookies)){
            $this->cookies  = new Util\Cookies();
        }
        return $this->cookies;
    }
    # access to models. Need genuine access to model. I think.
    # As long as it's not going to hold state information in the
    # object, I think that's OK
    public function model($model){
        if(!isset($this->models)){
            $this->models = \Piste\Dispatch\Models::singleton();
        }
        return $this->models->get_model($model);
    }
    # Contolled access to View via Context-namespaced proxy object
    function view(){
        if(!isset($this->view)){
            $this->view = new \Piste\Context\View();
        }
        return $this->view;
    }

    # Contolled access to controller via Context-namespaced proxy object
    function controller(){
        if(!isset($this->controller)){
            $this->controller = new \Piste\Context\Controller();
        }
        return $this->controller;
    }

    # set/get which action we decided was the main dispatch action
    # TODO: why is this a public method on context object?
    public function action(\Piste\Dispatch\Action $action = null){
        if (isset($action)){
            $this->action = $action;
        }
        return $this->action;
    }

    # proxy methods into other objects
    public final function template($template = null){
        return $this->response->template($template);
    }
    public function stash($v1 = null, $v2=null){
        return $this->response->stash($v1, $v2);
    }
    # TODO set_args as a public method on contect object or request object seems wrong.
    public function set_args($args = null){
        return $this->request->set_args($args);
    }
    public function args($index = null){
        return $this->request->args($index);
    }
}

?>
