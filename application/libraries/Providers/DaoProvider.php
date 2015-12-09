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

        $daoContainer['AutoRefund'] = function () {
            return new D\AutoRefundDao();
        };

        $daoContainer['Attachment'] = function () {
            return new D\AttachmentDao();
        };

        $daoContainer['AuditLog'] = function () {
            return new D\AuditLogDao();
        };

        $daoContainer['AdwordsData'] = function () {
            return new D\AdwordsDataDao();
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

        $daoContainer['CompensationReason'] = function () {
            return new D\CompensationReasonDao();
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

        $daoContainer['Supplier'] = function () {
            return new D\SupplierDao();
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

        $daoContainer['ProductComplementaryAcc'] = function () {
            return new D\ProductComplementaryAccDao();
        };

        $daoContainer['ProductType'] = function () {
            return new D\ProductTypeDao();
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

        $daoContainer['Delivery'] = function () {
            return new D\DeliveryDao();
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

        $daoContainer['CourierFeed'] = function () {
            return new D\CourierFeedDao();
        };

        $daoContainer['CourierRegion'] = function () {
            return new D\CourierRegionDao();
        };

        $daoContainer['DeliveryType'] = function () {
            return new D\DeliveryTypeDao();
        };

        $daoContainer['DelayedOrder'] = function () {
            return new D\DelayedOrderDao();
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

        $daoContainer['DeliveryOption'] = function () {
            return new D\DeliveryOptionDao();
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

        $daoContainer['PlatformPmgw'] = function () {
            return new D\PlatformPmgwDao();
        };

        $daoContainer['PricingRules'] = function () {
            return new D\PricingRulesDao();
        };

        $daoContainer['Price'] = function () {
            return new D\PriceDao();
        };

        $daoContainer['PaymentGateway'] = function () {
            return new D\PaymentGatewayDao();
        };

        $daoContainer['PmgwCard'] = function () {
            return new D\PmgwCardDao();
        };

        $daoContainer['SubCatPlatformVar'] = function () {
            return new D\SubCatPlatformVarDao();
        };

        $daoContainer['Entity'] = function () {
            return new D\EntityDao();
        };

        $daoContainer['Event'] = function () {
            return new D\EventDao();
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

        $daoContainer['RiskRef'] = function () {
            return new D\RiskRefDao();
        };

        $daoContainer['Template'] = function () {
            return new D\TemplateDao();
        };

        $daoContainer['TemplateByPlatform'] = function () {
            return new D\TemplateByPlatformDao();
        };

        $daoContainer['TransmissionLog'] = function () {
            return new D\TransmissionLogDao();
        };

        $daoContainer['InterfaceExchangeRate'] = function () {
            return new D\InterfaceExchangeRateDao();
        };

        $daoContainer['IntegratedOrderFulfillment'] = function () {
            return new D\IntegratedOrderFulfillmentDao();
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

        $daoContainer['ReleaseOrderHistory'] = function () {
            return new D\ReleaseOrderHistoryDao();
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

        $daoContainer['SoPriorityScore'] = function () {
            return new D\SoPriorityScoreDao();
        };

        $daoContainer['SoPriorityScoreHistory'] = function () {
            return new D\SoPriorityScoreHistoryDao();
        };

        $daoContainer['SoRefundScore'] = function () {
            return new D\SoRefundScoreDao();
        };

        $daoContainer['SoRefundScoreHistory'] = function () {
            return new D\SoRefundScoreHistoryDao();
        };

        $daoContainer['SoReleaseOrder'] = function () {
            return new D\SoReleaseOrderDao();
        };

        $daoContainer['SoHoldStatusHistory'] = function () {
            return new D\SoHoldStatusHistoryDao();
        };

        $daoContainer['SoCompensation'] = function () {
            return new D\SoCompensationDao();
        };

        $daoContainer['SoCompensationHistory'] = function () {
            return new D\SoCompensationHistoryDao();
        };

        $daoContainer['SubjectDomain'] = function () {
            return new D\SubjectDomainDao();
        };

        $daoContainer['SubjectDomainDetail'] = function () {
            return new D\SubjectDomainDetailDao();
        };

        $daoContainer['SubjectDomainDetailLabel'] = function () {
            return new D\SubjectDomainDetailLabelDao();
        };

        $daoContainer['Shiptype'] = function () {
            return new D\ShiptypeDao();
        };

        $daoContainer['OrderNotes'] = function () {
            return new D\OrderNotesDao();
        };

        $daoContainer['OrderStatusHistory'] = function () {
            return new D\OrderStatusHistoryDao();
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

        $daoContainer['RaProduct'] = function () {
            return new D\RaProductDao();
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

        $daoContainer['Template'] = function () {
            return new D\TemplateDao();
        };

        $daoContainer['ScheduleJob'] = function () {
            return new D\ScheduleJobDao();
        };

        $daoContainer['InterfaceTracking'] = function () {
            return new D\InterfaceTrackingDao();
        };

        $daoContainer['InterfaceTrackingFeed'] = function () {
            return new D\InterfaceTrackingFeedDao();
        };

        $daoContainer['HoldReason'] = function () {
            return new D\HoldReasonDao();
        };

        $daoContainer['SiteConfig'] = function () {
            return new D\SiteConfigDao();
        };

        $daoContainer['PromotionCode'] = function () {
            return new D\PromotionCodeDao();
        };

        $daoContainer['ExternalCategory'] = function () {
            return new D\ExternalCategoryDao();
        };

        $daoContainer['ProductIdentifier'] = function () {
            return new D\ProductIdentifierDao();
        };

        $daoContainer['ExtCategoryMapping'] = function () {
            return new D\ExtCategoryMappingDao();
        };

        $daoContainer['CategoryMapping'] = function () {
            return new D\CategoryMappingDao();
        };

        $daoContainer['AffiliateSkuPlatform'] = function () {
            return new D\AffiliateSkuPlatformDao();
        };

        $daoContainer['Affiliate'] = function () {
            return new D\AffiliateDao();
        };

        $daoContainer['ProductNote'] = function () {
            return new D\ProductNoteDao();
        };

        $daoContainer['GoogleShopping'] = function () {
            return new D\GoogleShoppingDao();
        };

        $daoContainer['WmsInventory'] = function () {
            return new D\WmsInventoryDao();
        };

        $daoContainer['CompetitorMap'] = function () {
            return new D\CompetitorMapDao();
        };

        $daoContainer['PriceExtend'] = function () {
            return new D\PriceExtendDao();
        };

        $daoContainer['PriceMargin'] = function () {
            return new D\PriceMarginDao();
        };

        $daoContainer['DisplayQtyClass'] = function () {
            return new D\DisplayQtyClassDao();
        };

        $daoContainer['DisplayQtyFactor'] = function () {
            return new D\DisplayQtyFactorDao();
        };

        $daoContainer['FuncOption'] = function () {
            return new D\FuncOptionDao();
        };
    }
}
