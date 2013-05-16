<?php
namespace Piste\Context;
/*=head1 Name
Piste\Context\View

=head1 DESCRIPTION

=head1 DEPENDENCIES
=cut*/

require_once('Logger.php');

Class View {
    private $status = 200;
    private $classname;
    private $template;

    # the string representation of the view class chosen
    public function classname($classname = null){
        if ($classname){ $this->classname = $classname; }
        return $this->classname;
    }

    # get/set a template for rendering output
    public final function template($template = null){
        if (isset($template)){
            $this->template = $template;
        }
        return $this->template;
    }

    public function show_404(){
        \Logger::info('setting view to show 404 page');
        $this->status = 404;
    }

    public function get_status(){
        return $this->status;
    }
}

?>
