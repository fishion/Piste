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
require_once('Piste/Request.php');
require_once('Piste/Response.php');

/*=head1 Synopsis
*/
Class Context {
    private $data = array();
    private $request;
    private $response;
    private $view;
   
    function __construct(){
        $this->request = new Request();
        $this->response = new Response();
    }

    public function request(){return $this->request;}
    public function response(){return $this->response;}
    public function view($view = null){
        if ($view){ $this->view = $view; }
        return $this->view;
    }

    public function data($params = null, $value = null){
        if ($value & !is_string($params)){
            throw new Exception("Bad parameters to Piste POB data() method. Using 2 parameter form, the first value must be a string key");
        }
        if ($value){
            $this->data[$params] = $value;
        } elseif ($params) {
            $this->data = array_merge($this->data, $params);
        }
        return $this->data;
    }
    public function clear_data(){
        $this->data = array();
    }
}

?>
