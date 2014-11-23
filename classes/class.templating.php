<?php
class Template
{
    private function output()
    {
        $this->vars["global"] = array('template' => site_default_template, '_GET' => $GLOBALS['_GET']);
        echo $this->template->render($this->vars);
    }

    public function render($tmpl, $vars = array())
    {
        global $twig;
        $this->vars = $vars;
        $this->tmpl = $tmpl;

        $this->template = $twig->loadTemplate('_parts/'.$this->tmpl.'.html');
        $this->output();
    }
}
?>