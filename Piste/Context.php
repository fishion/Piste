<?php
namespace Piste;
/*=head1 Name
Piste\Context

=head1 DESCRIPTION
Object (TODO singleton?) which provides interface for different parts
of the PIste framework and environment to easily communicate and access 
each other.

=head1 DEPENDENCIES
File
=cut*/
require_once('Piste/Env.php');
require_once('Piste/Request.php');
require_once('Piste/Response.php');

/*=head1 Synopsis
*/
Class Context {
    private $env;
    private $request;
    private $response;
    private $action;
   
    function __construct($app_name){
        $this->env = new Env($app_name);
        $this->request = new Request();
        $this->response = new Response();
    }

    public function env(){return $this->env;}
    public function request(){return $this->request;}
    public function req(){return $this->request;}
    public function response(){return $this->response;}
    public function res(){return $this->response;}

    public function action(\Piste\Dispatch\Action $action = null){
        if (isset($action)){
            $this->action = $action;
        }
        return $this->action;
    }

    public function stash($v1 = null, $v2=null){
        return $this->response->stash($v1, $v2);
    }
    public function set_args($args = null){
        return $this->request->set_args($args);
    }
    public function args(){
        return $this->request->args();
    }
}

?>
