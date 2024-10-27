<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class RestrictAccess extends Module
{
    public function __construct()
    {
        $this->name = 'restrictaccess';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Iksuri';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Restrict Access to Products and Categories');
        $this->description = $this->l('Restricts access to product and category pages for non-logged-in users.');
        $this->ps_versions_compliancy = array('min' => '8.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionFrontControllerSetMedia');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        $controller = get_class($this->context->controller);

        if (
            in_array($controller, ['ProductController', 'CategoryController']) &&
            !$this->context->customer->isLogged()
        ) {
            $redirectUrl = $this->context->link->getPageLink('authentication', true);
            Tools::redirect($redirectUrl);
        }
    }
}

