<?php
namespace ESG\Panther\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use ESG\Panther\Dao as D;

class DaoProvider implements ServiceProviderInterface
{
    public function register(Container $daoContainer)
    {
        $daoContainer['Colour'] = function () {
            return new D\ColourDao();
        };

        $daoContainer['User'] = function () {
            return new D\UserDao();
        };

        $daoContainer['AuditLog'] = function () {
            return new D\AuditLogDao();
        };

        $daoContainer['Config'] = function () {
            return new D\ConfigDao();
        };

        $daoContainer['UserRole'] = function () {
            return new D\UserRoleDao();
        };

        $daoContainer['Role'] = function () {
            return new D\RoleDao();
        };

        $daoContainer['ApplicationFeature'] = function () {
            return new D\ApplicationFeatureDao();
        };

        $daoContainer['Logmessage'] = function () {
            return new D\LogmessageDao();
        };

        $daoContainer['Region'] = function () {
            return new D\RegionDao();
        };

        $daoContainer['Country'] = function () {
            return new D\CountryDao();
        };

        $daoContainer['CountryExt'] = function () {
            return new D\CountryExtDao();
        };

        $daoContainer['RmaFc'] = function () {
            return new D\RmaFcDao();
        };

        $daoContainer['Product'] = function () {
            return new D\ProductDao();
        };

        $daoContainer['Faqadmin'] = function () {
            return new D\FaqadminDao();
        };

        $daoContainer['Colour'] = function () {
            return new D\ColourDao();
        };

        $daoContainer['ColourExtend'] = function () {
            return new D\ColourExtendDao();
        };

        $daoContainer['DeliveryTime'] = function () {
            return new D\DeliveryTimeDao();
        };

        $daoContainer['Language'] = function () {
            return new D\LanguageDao();
        };

        $daoContainer['Currency'] = function () {
            return new D\CurrencyDao();
        };

        $daoContainer['SellingPlatform'] = function () {
            return new D\SellingPlatformDao();
        };

        $daoContainer['Courier'] = function () {
            return new D\CourierDao();
        };

        $daoContainer['DeliveryType'] = function () {
            return new D\DeliveryTypeDao();
        };

        $daoContainer['Brand'] = function () {
            return new D\BrandDao();
        };

        $daoContainer['Category'] = function () {
            return new D\CategoryDao();
        };

        $daoContainer['CustomClassification'] = function () {
            return new D\CustomClassificationDao();
        };

        $daoContainer['CustomClassificationMapping'] = function () {
            return new D\CustomClassificationMappingDao();
        };

        $daoContainer['SkuMapping'] = function () {
            return new D\SkuMappingDao();
        };

    }
}
