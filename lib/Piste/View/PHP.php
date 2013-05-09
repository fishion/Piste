<?php
namespace Piste\View;
/*=head1 Name
Piste\View\PHP

=head1 DESCRIPTION
PHP view is the basic view for php html output

=head1 DEPENDENCIES
File
=cut*/
require_once('Logger.php');
require_once('File.php');
require_once('Piste/View.php');

abstract Class PHP extends \Piste\View {

    function __construct($pc){
        parent::__construct($pc);
        # include the include path
        if ($this->config['template_include']){
            $dirpath = $pc->env()->app_base() . $this->config['template_include'];
            $dir = new \File($dirpath);
            if ($dir->is_dir()){
                $pc->env()->add_include_path($dirpath);
            } else {
                throw new \Exception("Include path '$dirpath' not found");
            }
        }
    }

    protected $base_config = array(
/*=item DEBUG_SERVER
Boolean providing a quick and easy way to dump the contents of $_SERVER.
Default 0
=cut*/
        'DEBUG_SERVER'      => 0,
/*=item template_base
Directory relative to application root where you expect to find templates.
Default 'template/base/'.
=cut*/
        'template_base'     => 'template/base/',
/*=item template_include
Directory relative to application root where you want to put included templates.
Default 'template/base/'.
=cut*/
        'template_include'  => 'template/include/',
/*=item wrapper
path to php file (relative to include_path) containing a wrapper. The wrapper should 'echo $Pcontent' at some stage to pull in the main page contents.
Default value is null.
=cut*/
        'wrapper'           => null,
/*=item 404
Path to a 404 template if dispatch file not found
default: null
=cut*/
        '404'               => null,
/*=item template_suffix
Suffix to add to dispatch path to find filename
Default php
=back
=cut*/
        'template_suffix'   => '.php'
    );

    public function render($pc){
        if ($pc->response()->body()){
            # response content as been set directly.
            # Nothing to render
            return;
        }
        
        # make sure we can get a template
        $template = $this->get_template($pc);
        if (!isset($template)){
            \Logger::debug("Can't find");
            return $this->render_404($pc);
        }

        # make stash available as global vars in template
        foreach ($pc->response()->stash() as $key => $val){
            \Logger::debug("Making stash item $key available in global scope");
            $GLOBALS[$key] = $val;
        }

        # require page content & store in output buffer
        # By rendering the page innards before the 
        # wrapper, you can set variables in the page
        # which will be used in the rendered wrapper.
        # This is useful
        ob_start();

        # make Piste Context object available too. WHy not.
        $GLOBALS['pc'] = $pc;

        try {
            require $template;
        } catch(\Exception $e){
            throw new \Exception("File Error - couldn't find template: $e<br>");
        }
        if ($this->config['DEBUG_SERVER']) {
            echo '<pre>';
            print_r($_SERVER);
            echo '</pre>';
        }
        if ($this->config['wrapper']){
            # TODO : not sure I like Pcontent as a fixed variable name
            $Pcontent = ob_get_clean();
            ob_start();
            require($this->config['wrapper']);
            $pc->res()->body(ob_get_clean());
        } else {
            $pc->res()->body(ob_get_clean());
        }
    }    

    public function render_404($pc){
        if (isset($this->config['404'])){
            $pc->response()->template($this->config['404']);
            $this->render($pc);
        } else {
            parent::render_404($pc);
        }
    }

/*=head2 full_template_path()
=cut*/
    private function full_template_path($pc){
        return  $pc->env()->app_base()
              . $this->config['template_base']
              . $pc->response()->template()
              . $this->config['template_suffix'];
    }

/*=head2 get_template()
=cut*/
    private function get_template($pc){
        # make sure we have a template set
        if (!$pc->response()->template() && $pc->action()){
            \Logger::debug('Setting default template to ' . $pc->action()->default_template());
            $pc->response()->template($pc->action()->default_template());
        }

        $template = new \File($this->full_template_path($pc));
        if ($template->is_file()){
            return $template;
        }
        return null;
    }
}

?>
