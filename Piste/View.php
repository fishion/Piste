<?php
namespace Piste;
/*=head1 Name
Piste\View

=head1 DESCRIPTION
An interface to define what methods a view class must implement

*/
abstract class View {

    abstract public function render($pc);

    protected function get_config(){
        
    }

}

?>
