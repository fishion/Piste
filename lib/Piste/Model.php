<?php
namespace Piste;
/*=head1 Name
Piste\Model

=head1 DESCRIPTION
An abstract base class for all Pise Models

=head1 DEPENDENCIES

=cut*/
require_once('Piste/Dispatch/Models.php');

abstract class Model {

    public final function P_register(){
        $models = \Piste\Dispatch\Models::singleton();
        $models->register($this);
    }

}

?>
