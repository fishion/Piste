<?php
namespace Piste;
/*=head1 Name
Piste\View

=head1 DESCRIPTION
An abstract class define what methods a view class must implement
and providing fallback functions

=head1 DEPENDENCIES
File
=cut*/
require_once('Piste/Dispatch/Views.php');

abstract class View {
/*=head1 Constructor
    Sorts out config for you.
    You can add $base_config into view class which this constructor will overwrite
    with parameters from a user defined $config variable in application view class
=over
=cut*/
    function __construct(Context $pc){
        # sort out config
        if (!isset($this->base_config)) {$this->base_config = array();}
        if ($this->base_config && !is_array($this->base_config)){
            throw new \Exception("View's base_config should be an associative array");
        }
        if (!isset($this->config)) {$this->config = array();}
        if ($this->config && !is_array($this->config)){
            throw new \Exception("Application config should be an associative array");
        }
        $this->config = array_replace($this->base_config, $this->config);

    }

    public final function P_register(){
        $views = \Piste\Dispatch\Views::singleton();
        $views->register($this);
    }


    # Overridable methods

    # 'render' method responsible for rendering whatever 
    # template has been set and
    # storing rendered content in response()->body() 
    abstract public function render(Context $pc);

    public function render_404(Context $pc){
        $pc->response()->status(404);
        $pc->response()->body($this->get_404_body($pc));
    }

    protected function get_404_body(Context $pc){
        # TODO make better default 404
        return <<<________EOHTML
            <html>
                <head>
                </head>
                <body>
                    <h1>Piste 404</h1>
                    <p>Sorry. Couldn't find that page for you!</p>
                </body>
            </html>
________EOHTML;
    }
}

?>
