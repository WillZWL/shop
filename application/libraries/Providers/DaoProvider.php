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

        $daoContainer['DeliveryTime'] = function () {
            return new D\DeliveryTimeDao();
        };

        $daoContainer['Country'] = function () {
            return new D\CountryDao();
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

    }
}
