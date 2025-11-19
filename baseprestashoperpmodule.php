<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use MyErpModule\Service\ErpApiClient;

class BasePrestashopErpModule extends Module
{
    public function __construct()
    {
        $this->name = 'baseprestashoperpmodule';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('ERP Integration Base Module');
        $this->description = $this->l('A base module to integrate PrestaShop with an external ERP.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('actionValidateOrder') &&
            $this->registerHook('actionOrderStatusUpdate') &&
            $this->registerHook('actionUpdateQuantity');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Entry point for the module configuration page.
     */
    public function getContent()
    {
        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminErpBaseModule')
        );
    }
    
    // HOOKS

    /**
     * Hook for order creation.
     * Use this hook to send new order data to the ERP.
     */
    public function hookActionValidateOrder($params)
    {
        try {
            PrestaShopLogger::addLog('ERP Sync: New order detected.', 1);

            $apiUrl = Configuration::get('ERP_API_URL');
            $apiKey = Configuration::get('ERP_API_KEY');

            if (empty($apiUrl) || empty($apiKey)) {
                PrestaShopLogger::addLog('ERP Sync: API URL or Key not configured.', 3);
                return;
            }

            $order = $params['order'];
            $orderData = [
                'id' => $order->id,
                'reference' => $order->reference,
                'total_paid' => $order->total_paid_tax_incl,
                'customer_email' => $params['customer']->email,
            ];

            $apiClient = new ErpApiClient($apiUrl, $apiKey);
            
            if ($apiClient->sendOrder($orderData)) {
                PrestaShopLogger::addLog('ERP Sync: Order ' . $order->reference . ' sent successfully.', 1);
            } else {
                PrestaShopLogger::addLog('ERP Sync: Failed to send order ' . $order->reference . '.', 3);
            }
        } catch (Exception $e) {
            PrestaShopLogger::addLog('ERP Sync: An error occurred: ' . $e->getMessage(), 3);
        }
    }

    /**
     * Hook for order status updates.
     * Use this to notify the ERP of changes (e.g., shipped, canceled).
     */
    public function hookActionOrderStatusUpdate($params)
    {
        // $newOrderStatus = $params['newOrderStatus'];
        // $id_order = (int)$params['id_order'];
        // Implement your logic here.
    }

    /**
     * Hook for quantity updates.
     * Use this to sync stock changes from PrestaShop back to the ERP.
     */
    public function hookActionUpdateQuantity($params)
    {
        // $id_product = (int)$params['id_product'];
        // $id_product_attribute = (int)$params['id_product_attribute'];
        // $quantity = (int)$params['quantity'];
        // Implement your logic here.
    }
}