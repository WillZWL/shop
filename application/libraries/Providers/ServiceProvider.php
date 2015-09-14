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

        $servcieContainer['ColourDao'] = function($c) {
            return new D\ColourDao();
        };

        $servcieContainer['UserDao'] = function($c) {
            return new D\ColourDao();
        };



        $servcieContainer['Colour'] = function () {
            return new S\ColourService();
        };

        $servcieContainer['Authorization'] = function ($c) {
            return new S\AuthorizationService($c['UserDao']);
        };

        $servcieContainer['Authentication'] = function ($c) {
            return new S\AuthenticationService($c['ColourDao']);
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
    }
}
