<?php
namespace ESG\Panther\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use ESG\Panther\Dao as D;

class DaoProvider implements ServiceProviderInterface
{
    public function register(Container $daoContainer)
    {
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

        $daoContainer['RegionCountry'] = function () {
            return new D\RegionCountryDao();
        };

        $daoContainer['Country'] = function () {
            return new D\CountryDao();
        };

        $daoContainer['CountryExt'] = function () {
            return new D\CountryExtDao();
        };

        $daoContainer['Rma'] = function () {
            return new D\RmaDao();
        };

        $daoContainer['RmaFc'] = function () {
            return new D\RmaFcDao();
        };

        $daoContainer['Product'] = function () {
            return new D\ProductDao();
        };

        $daoContainer['SupplierProd'] = function () {
            return new D\SupplierProdDao();
        };

        $daoContainer['ProductCustomClassification'] = function () {
            return new D\ProductCustomClassificationDao();
        };

        $daoContainer['ProductSpecDetails'] = function () {
            return new D\ProductSpecDetailsDao();
        };

        $daoContainer['ProductWarranty'] = function () {
            return new D\ProductWarrantyDao();
        };

        $daoContainer['ProductKeyword'] = function () {
            return new D\ProductKeywordDao();
        };

        $daoContainer['ProductContent'] = function () {
            return new D\ProductContentDao();
        };

        $daoContainer['ProductImage'] = function () {
            return new D\ProductImageDao();
        };

        $daoContainer['ProductContentExtend'] = function () {
            return new D\ProductContentExtendDao();
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

        $daoContainer['Client'] = function () {
            return new D\ClientDao();
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

        $daoContainer['CourierRegion'] = function () {
            return new D\CourierRegionDao();
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

        $daoContainer['CategoryExtend'] = function () {
            return new D\CategoryExtendDao();
        };

        $daoContainer['CategoryContent'] = function () {
            return new D\CategoryContentDao();
        };

        $daoContainer['CategoryBanner'] = function () {
            return new D\CategoryBannerDao();
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

        $daoContainer['PlatformBizVar'] = function () {
            return new D\PlatformBizVarDao();
        };

        $daoContainer['PlatformCourier'] = function () {
            return new D\PlatformCourierDao();
        };

        $daoContainer['Price'] = function () {
            return new D\PriceDao();
        };

        $daoContainer['PlatformCourier'] = function () {
            return new D\PlatformCourierDao();
        };

        $daoContainer['SubCatPlatformVar'] = function () {
            return new D\SubCatPlatformVarDao();
        };

        $daoContainer['Entity'] = function () {
            return new D\EntityDao();
        };

        $daoContainer['ExchangeRate'] = function () {
            return new D\ExchangeRateDao();
        };

        $daoContainer['ExchangeRateApproval'] = function () {
            return new D\ExchangeRateApprovalDao();
        };

        $daoContainer['ExchangeRateHistory'] = function () {
            return new D\ExchangeRateHistoryDao();
        };

        $daoContainer['TransmissionLog'] = function () {
            return new D\TransmissionLogDao();
        };

        $daoContainer['InterfaceExchangeRate'] = function () {
            return new D\InterfaceExchangeRateDao();
        };

        $daoContainer['Batch'] = function () {
            return new D\BatchDao();
        };

        $daoContainer['FtpInfo'] = function () {
            return new D\FtpInfoDao();
        };

        $daoContainer['FreightCategory'] = function () {
            return new D\FreightCategoryDao();
        };

        $daoContainer['FreightCatCharge'] = function () {
            return new D\FreightCatChargeDao();
        };

        $daoContainer['FulfillmentCentre'] = function () {
            return new D\FulfillmentCentreDao();
        };

        $daoContainer['So'] = function () {
            return new D\SoDao();
        };

        $daoContainer['SoItem'] = function () {
            return new D\SoItemDao();
        };

        $daoContainer['SoItemDetail'] = function () {
            return new D\SoItemDetailDao();
        };

        $daoContainer['SoExtend'] = function () {
            return new D\SoExtendDao();
        };

        $daoContainer['SoAllocate'] = function () {
            return new D\SoAllocateDao();
        };

        $daoContainer['SoPaymentStatus'] = function () {
            return new D\SoPaymentStatusDao();
        };

        $daoContainer['SoHoldReason'] = function () {
            return new D\SoHoldReasonDao();
        };

        $daoContainer['SoCreditChk'] = function () {
            return new D\SoCreditChkDao();
        };

        $daoContainer['SoRisk'] = function () {
            return new D\SoRiskDao();
        };

        $daoContainer['SoShipment'] = function () {
            return new D\SoShipmentDao();
        };

        $daoContainer['OrderNotes'] = function () {
            return new D\OrderNotesDao();
        };

        $daoContainer['Refund'] = function () {
            return new D\RefundDao();
        };

        $daoContainer['RefundHistory'] = function () {
            return new D\RefundHistoryDao();
        };

        $daoContainer['RefundReason'] = function () {
            return new D\RefundReasonDao();
        };

        $daoContainer['RefundItem'] = function () {
            return new D\RefundItemDao();
        };

        $daoContainer['Unit'] = function () {
            return new D\UnitDao();
        };

        $daoContainer['UnitType'] = function () {
            return new D\UnitTypeDao();
        };

        $daoContainer['Warehouse'] = function () {
            return new D\WarehouseDao();
        };

        $daoContainer['WeightCategory'] = function () {
            return new D\WeightCategoryDao();
        };

        $daoContainer['WeightCatCharge'] = function () {
            return new D\WeightCatChargeDao();
        };

    }
}
