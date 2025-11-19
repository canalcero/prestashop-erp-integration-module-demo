<?php

class baseprestashoperpmoduleronModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        // Secure the CRON endpoint with a token
        $token = Tools::getValue('token');
        $storedToken = Configuration::get('ERP_CRON_SECURE_TOKEN');

        if (!$token || $token !== $storedToken) {
            header('HTTP/1.1 401 Unauthorized');
            exit('Unauthorized');
        }

        // --- CRON Tasks ---
        $this->syncProducts();
        $this->syncStock();

        // Output a success message
        echo 'CRON tasks executed successfully.';
        exit;
    }

    /**
     * Sync products from ERP to PrestaShop.
     */
    private function syncProducts()
    {
        // To be implemented:
        // 1. Instantiate the ErpApiClient.
        // 2. Fetch product data from the ERP.
        // 3. Loop through the data and create/update PrestaShop products.
        // PrestaShopLogger::addLog('Product sync started.');
    }

    /**
     * Sync stock levels from ERP to PrestaShop.
     */
    private function syncStock()
    {
        // To be implemented:
        // 1. Instantiate the ErpApiClient.
        // 2. Fetch stock data from the ERP.
        // 3. Loop through the data and update stock levels using StockAvailable::setQuantity().
        // PrestaShopLogger::addLog('Stock sync started.');
    }
}