<?php
namespace ESG\Panther\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use ESG\Panther\Service as S;
use ESG\Panther\Dao as D;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $servcieContainer)
    {

        $servcieContainer['Authorization'] = function ($c) {
            return new S\AuthorizationService();
        };

        $servcieContainer['Authentication'] = function ($c) {
            return new S\AuthenticationService();
        };

        $servcieContainer['Batch'] = function ($c) {
            return new S\BatchService();
        };

        $servcieContainer['Category'] = function () {
            return new S\CategoryService();
        };

        $servcieContainer['ContextConfig'] = function () {
            return new S\ContextConfigService();
        };

        $servcieContainer['Colour'] = function () {
            return new S\ColourService();
        };

        $servcieContainer['Courier'] = function () {
            return new S\CourierService();
        };

        $servcieContainer['Country'] = function () {
            return new S\CountryService();
        };

        $servcieContainer['Currency'] = function () {
            return new S\CurrencyService();
        };

        $servcieContainer['CustomClass'] = function () {
            return new S\CustomClassService();
        };

        $servcieContainer['CustomClassificationMapping'] = function () {
            return new S\CustomClassificationMappingService();
        };

        $servcieContainer['Language'] = function () {
            return new S\LanguageService();
        };

        $servcieContainer['PriceMargin'] = function () {
            return new S\PriceMarginService();
        };

        $servcieContainer['Product'] = function () {
            return new S\ProductService();
        };

        $servcieContainer['Log'] = function () {
            return new S\LogService();
        };

        $servcieContainer['IntegratedOrderFulfillment'] = function () {
            return new S\IntegratedOrderFulfillmentService();
        };

        $servcieContainer['DeliveryTime'] = function () {
            return new S\DeliveryTimeService();
        };

        $servcieContainer['ProductCreation'] = function () {
            return new S\ProductCreationService();
        };

        $servcieContainer['ExchangeRate'] = function () {
            return new S\ExchangeRateService();
        };

        $servcieContainer['User'] = function () {
            return new S\UserService();
        };

        $servcieContainer['So'] = function () {
            return new S\SoService();
        };

        $servcieContainer['Warehouse'] = function () {
            return new S\WarehouseService();
        };

        $servcieContainer['WmsInventory'] = function () {
            return new S\WmsInventoryService();
        };

        $servcieContainer['LoadSiteParameter'] = function () {
            return new S\LoadSiteParameterService();
        };

        $servcieContainer['Website'] = function () {
            return new S\WebsiteService();
        };

        $servcieContainer['PriceWebsite'] = function () {
            return new S\PriceWebsiteService();
        };

        $servcieContainer['PriceWebsite'] = function () {
            return new S\PriceWebsiteService();
        };

        $servcieContainer['Region'] = function () {
            return new S\regionService();
        };

        $servcieContainer['Courier'] = function () {
            return new S\CourierService();
        };

        $servcieContainer['Client'] = function () {
            return new S\ClientService();
        };

        $servcieContainer['ComplementaryAcc'] = function () {
            return new S\ComplementaryAccService();
        };

        $servcieContainer['Event'] = function () {
            return new S\EventService();
        };

        $servcieContainer['PdfRendering'] = function () {
            return new S\PdfRenderingService();
        };
    }
}
