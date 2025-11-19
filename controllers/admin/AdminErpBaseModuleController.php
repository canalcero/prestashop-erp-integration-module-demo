<?php

class AdminErpBaseModuleController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'configuration';
        $this->className = 'Configuration';
        $this->lang = false;
        
        parent::__construct();

        $this->fields_options = [
            'general' => [
                'title' => $this->l('ERP Settings'),
                'fields' => [
                    'ERP_API_URL' => [
                        'title' => $this->l('API URL'),
                        'type' => 'text',
                        'validation' => 'isUrl',
                        'required' => true,
                    ],
                    'ERP_API_KEY' => [
                        'title' => $this->l('API Key'),
                        'type' => 'text',
                        'validation' => 'isGenericName',
                        'required' => true,
                    ],
                    'ERP_CRON_SECURE_TOKEN' => [
                        'title' => $this->l('CRON Secure Token'),
                        'type' => 'text',
                        'validation' => 'isGenericName',
                        'required' => true,
                        'desc' => $this->l('A secure token to protect your CRON endpoint.'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }
}