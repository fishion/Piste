<?php
namespace Piste;
/*=head1 Name
Piste\Context

=head1 DESCRIPTION
Object (TODO singleton? Probably not - you might get leakage accross sessions)
which provides interface for different parts
of the Piste framework and environment to easily communicate and access
each other.
Doesn't have a lot of methods of it's own, it mostly proxies to other objects

=head1 DEPENDENCIES
File
=cut*/
require_once('Piste/Context/Request.php');
require_once('Piste/Context/Response.php');
require_once('Piste/Context/Stash.php');
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
    private $stash;
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
    function view($viewname = null){
        if(!isset($this->view)){
            $this->view = new \Piste\Context\View();
        }
        if (isset($viewname)){
            # allow setting of viewname by $pc->view($name)
            $this->view->classname($viewname);
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
    # This is set before controller actions and is used in views to
    # decide, for example, whether a page was found. If you want to
    # overwrite it in your controller and lie to the View methods, then
    # Piste won't stop you, though it's probably not recommended.
    public function action(\Piste\Dispatch\Action $action = null){
        if (isset($action)){
            $this->action = $action;
        }
        return $this->action;
    }

    # proxy methods into other objects
    public final function template($template = null){
        return $this->view()->template($template);
    }
    public function stash($v1 = null, $v2=null){
        if (!isset($this->stash)){
            $this->stash = new Context\Stash();
        }
        return $this->stash->getset($v1, $v2);
    }
    public function args($index = null){
        return $this->request->args($index);
    }
}

?>
