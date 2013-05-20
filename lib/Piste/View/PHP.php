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

    function __construct(\Piste\Context $pc){
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

/*=head2 render(\Piste\Context $pc)
=cut*/
    public function render(\Piste\Context $pc){
        if ($pc->response()->body()){
            # response content has already been set directly.
            # Nothing to render
            return;
        }
        
        # make sure we have a template
        $template = $this->find_template($pc);
        if (!isset($template)){
            return $this->render_404($pc);
        }

        $pc->response()->body(
            $this->render_template($pc, $template)
        );
    }    

/*=head2 get_404_body(\Piste\Context $pc)
=cut*/
    public function get_404_body(\Piste\Context $pc){
        $template = $this->template_exists($pc, $this->config['404']);
        if (!isset($template)){
            # no valid 404 teplate set. Use default.
            return parent::get_404_body($pc);
        }
        return $this->render_template($pc, $template);
    }


/*=head2 find_template(\Piste\Context $pc)
=cut*/
    private function find_template(\Piste\Context $pc){
        # make sure we have a template path set
        if (!$pc->view()->template() && $pc->action()){
            \Logger::debug('Setting default template to ' . $pc->action()->default_template());
            $pc->view()->template($pc->action()->default_template());
        } elseif (!$pc->view()->template() && !$pc->action()){
            # no template, no action.
            return;
        }
        return $this->template_exists($pc, $pc->view()->template());
    }
/*=head2 template_exists(\Piste\Context $pc, $template)
=cut*/
    private function template_exists($pc, $template){
        $t_file = new \File($this->full_template_path($pc, $template));
        if (isset($t_file) && $t_file->is_file()){
            return $t_file;
        }

        \Logger::debug("Can't find $template");
        return null;
    }
/*=head2 full_template_path(\Piste\Context $pc, $template)
=cut*/
    private function full_template_path(\Piste\Context $pc, $template){
        return  $pc->env()->app_base()
              . $this->config['template_base']
              . $template
              . $this->config['template_suffix'];
    }

/*=head2 render_template(\Piste\Context $pc, $template)
=cut*/
    private function render_template(\Piste\Context $pc, $template){
        # make stash available as global vars in template
        foreach ($pc->response()->stash() as $key => $val){
            \Logger::debug("Making stash item $key available in global scope");
            $GLOBALS[$key] = $val;
        }

        # make Piste Context object available too. WHy not.
        $GLOBALS['pc'] = $pc;

        # require page content & store in output buffer
        # By rendering the page innards before the 
        # wrapper, you can set variables in the page
        # which will be used in the rendered wrapper.
        # This is useful
        ob_start();

        # render page template
        require $template;

        if ($this->config['DEBUG_SERVER']) {
            echo '<pre>';
            print_r($_SERVER);
            echo '</pre>';
        }
        if ($this->config['wrapper']){
            $wrapped_content = ob_get_clean();
            ob_start();
            require($this->config['wrapper']);
        }
        return ob_get_clean();
    }
}

?>
