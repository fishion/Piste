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

    public function render(\Piste\Context $pc){
        if (!$pc->action()){
            return $this->render_404($pc);
        }
        $pc->response()->content_type('Content-type: application/json');
        $pc->response()->body(
                json_encode($pc->stash())
        );  
    }

    public function render_404(\Piste\Context $pc){
        $pc->response()->content_type('Content-type: application/json');
        parent::render_404($pc);
    }

    public function get_404_body(\Piste\Context $pc){
        return json_encode(array('message' => 'content not found'));
    }
}

?>
