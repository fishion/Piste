<?php
namespace Piste;
/*=head1 Name
Piste\POB

=head1 DESCRIPTION
Object (TODO singleton?) which provides interface for controllers
to easily communicate with and access other parts of the framework

*/
Class POB {
    private $data = array();
    
    public function data($params = null, $value = null){
        if ($value & !is_string($params)){
            throw Exception("Bad parameters to Piste POB data() method. Using 2 parameter form, the first value must be a string key");
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
