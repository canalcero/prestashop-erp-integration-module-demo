# PrestaShop - ERP Generic Integration Base Module

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/canalcero/prestashop-erp-integration-module-demo)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PrestaShop Version](https://img.shields.io/badge/PrestaShop-1.7%20%7C%208.x-purple)](https://www.prestashop.com)

A generic, extendable base module for integrating a PrestaShop 1.7 / 8.x store with an external ERP system.

**Repository:** `https://github.com/canalcero/prestashop-erp-integration-module-demo`

---

## ⚠️ Important: This is a Boilerplate

This repository is **not** a plug-and-play module. It is a **template** or **boilerplate** designed to accelerate the development of a custom integration. It provides the necessary file structure, PrestaShop module boilerplate, and example hooks, but contains **no active integration logic**.

You must implement all the API communication, data mapping, and business logic specific to the target ERP.

## Features (What's Included)

* Standard PrestaShop module structure for 1.7/8.x.
* Basic `install()` and `uninstall()` methods.
* A sample configuration page (Module settings).
* Placeholders for common integration hooks (e.g., order creation, product updates).
* A suggested directory structure for services, controllers, and API clients.

## Prerequisites

Before you begin, ensure you have the following:

* A working PrestaShop 1.7.x or 8.x installation.
* Composer installed.
* Developer-level knowledge of PHP and PrestaShop module development.
* Access to the target ERP's API documentation and credentials.

## Getting Started

Follow these steps to use this template for your own integration.

### 1. Set Up Your Module

1.  **Clone or Download:** Get a copy of this repository.
2.  **Rename Files:** This is the most crucial step. The default module name is `baseprestashoperpmodule`. You must rename:
    * The main folder: `prestashop-erp-integration-module-demo` -> `my_erp_module`
    * The main PHP file: `baseprestashoperpmodule.php` -> `myerpmodule.php`
    * The main class name inside the file: `BasePrestashopErpModule` -> `MyErpModule`
3.  **Update `config.xml`:** Open `config.xml` and update:
    * `<name>`: Set to your module's technical name (e.g., `myerpmodule`).
    * `<displayName>`: Set to the user-friendly name (e.g., `My ERP Integration`).
    * `<description>`: Write a short description.
    * `<author>`: Add your name or company.
4.  **Update `composer.json`:** (Optional but recommended) Update the `name` and `description` fields.

### 2. Installation

1.  **Run Composer:** Navigate to your module's root directory in the terminal and run:
    ```bash
    composer install
    ```
2.  **Deploy to PrestaShop:**
    * **Option 1 (Zip):** Zip the entire module folder and upload it via the PrestaShop Back Office (Module Manager > Upload a module).
    * **Option 2 (Copy):** Copy the entire module folder into the `/modules` directory of your PrestaShop installation.
3.  **Install:** In the PrestaShop Back Office, find your module in the Module Manager and click **Install**.

## How to Customize

This module is a skeleton. You need to build the integration logic.

### 1. Configuration Page

Start in `controllers/admin/AdminMyErpModuleController.php` (or rename as needed) and the associated template. This is where you should add configuration fields for:

* ERP API Endpoint
* API Key / Username / Password
* Sync toggles (e.g., "Enable Order Sync," "Enable Stock Sync")
* Webhook URLs
* CRON job settings

### 2. API Client

It is highly recommended to create a dedicated service for communicating with your ERP.

* Create a class in `src/Service/ErpApiClient.php`.
* Use Guzzle (or a similar HTTP client) to handle `GET`, `POST`, `PUT` requests.
* Handle authentication, headers, and error responses within this service.

### 3. Data Sync Logic

The "how" and "when" of data synchronization is core to your module.

#### Common Hooks

You will need to register and use PrestaShop's hooks. Add your logic to the methods in your main module file (`myerpmodule.php`).

* **Order Sync (PS -> ERP):**
    * `hookActionValidateOrder`: Triggered when an order is successfully placed. Good for sending new orders to the ERP.
    * `hookActionOrderStatusUpdate`: Triggered when an order's status changes (e.g., to "shipped").
* **Product Sync (ERP -> PS):**
    * This is typically handled by a CRON job or a webhook from the ERP.
    * You will create `Product` or `Combination` objects programmatically.
* **Stock Sync (ERP -> PS):**
    * Also typically handled by a CRON job.
    * Use `StockAvailable::setQuantity()` to update stock levels.
* **Stock Sync (PS -> ERP):**
    * `hookActionUpdateQuantity`: Triggered when a product's quantity changes for any reason (including a sale).

#### CRON Jobs

For batch processing (like syncing all products or pulling stock levels), you should create a dedicated CRON controller.

1.  Create a file in `controllers/front/cron.php`.
2.  Implement security (e.g., a secure token) to prevent public execution.
3.  Add logic to fetch/push data in batches.
4.  Set up a cron job on your server to call this URL:
    `http://yourshop.com/index.php?fc=module&module=myerpmodule&controller=cron&token=YOUR_SECURE_TOKEN`

### 4. Data Mapping

Create services or helper classes to map data structures.

* **Example:** A PrestaShop `Address` object must be translated into the format your ERP's API expects for a customer address. Your mapper service will handle this transformation.

## Development Roadmap

This is a general checklist of what you need to build:

* [ ] **Authentication:** Implement API client authentication (OAuth, API Key, etc.).
* [ ] **Config Page:** Build the form to store credentials and settings.
* [ ] **Order Export:** Send new orders to the ERP (via `hookActionValidateOrder`).
* [ ] **Shipment Update:** Update PrestaShop order status when ERP marks it as shipped (via webhook or CRON).
* [ ] **Product Import/Sync:** Create/update PrestaShop products from ERP data (via CRON).
* [ ] **Stock Sync:** Keep PrestaShop stock levels in sync with the ERP (via CRON or webhooks).
* [ ] **Customer Sync:** (Optional) Sync customer data between systems.
* [ ] **Logging:** Implement robust logging (e.g., using Monolog or a `ps_log` table) to track API errors and sync failures.
* [ ] **Error Handling:** What happens if the ERP is down? Implement retry logic or an admin notification system.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details.