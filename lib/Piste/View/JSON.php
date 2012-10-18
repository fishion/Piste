<?php
namespace Piste\View;
/*=head1 Name
Piste\View\JSON

=head1 DESCRIPTION
JSON view returns the stash as a JSON object

=head1 DEPENDENCIES
File
=cut*/
require_once('Piste/View.php');

abstract Class JSON extends \Piste\View {

    public function render($pc){
        header('Content-type: application/json');
        $pc->response()->body(
            json_encode($pc->response()->stash())
        );
    }    
}

?>
