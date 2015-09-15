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
        $servcieContainer['Colour'] = function () {
            return new S\ColourService();
        };

        $servcieContainer['Authorization'] = function ($c) {
            return new S\AuthorizationService();
        };

        $servcieContainer['Authentication'] = function ($c) {
            return new S\AuthenticationService();
        };

        $servcieContainer['Category'] = function () {
            return new S\CategoryService();
        };

        $servcieContainer['ContextConfig'] = function () {
            return new S\ContextConfigService();
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

        $servcieContainer['Country'] = function () {
            return new S\CountryService();
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
    }
}
