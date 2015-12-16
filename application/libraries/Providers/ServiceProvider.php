<?php
namespace ESG\Panther\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use ESG\Panther\Service as S;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $serviceContainer)
    {
        $serviceContainer['Base'] = function ($c) {
            return new S\BaseService($c);
        };

        $serviceContainer['Aftership'] = function () {
            return new S\AftershipService();
        };

        $serviceContainer['Authorization'] = function () {
            return new S\AuthorizationService();
        };

        $serviceContainer['Authentication'] = function () {
            return new S\AuthenticationService();
        };

        $serviceContainer['Batch'] = function () {
            return new S\BatchService();
        };

        $serviceContainer['Category'] = function () {
            return new S\CategoryService();
        };

        $serviceContainer['ContextConfig'] = function () {
            return new S\ContextConfigService();
        };

        $serviceContainer['Colour'] = function () {
            return new S\ColourService();
        };

        $serviceContainer['Courier'] = function () {
            return new S\CourierService();
        };

        $serviceContainer['Country'] = function () {
            return new S\CountryService();
        };

        $serviceContainer['Currency'] = function () {
            return new S\CurrencyService();
        };

        $serviceContainer['CustomClass'] = function () {
            return new S\CustomClassService();
        };

        $serviceContainer['CustomClassificationMapping'] = function () {
            return new S\CustomClassificationMappingService();
        };

        $serviceContainer['Clwms'] = function () {
            return new S\ClwmsService();
        };

        $serviceContainer['ClwmsTrackingFeed'] = function () {
            return new S\ClwmsTrackingFeedService();
        };

        $serviceContainer['DeliveryOption'] = function () {
            return new S\DeliveryOptionService();
        };

        $serviceContainer['Language'] = function () {
            return new S\LanguageService();
        };

        $serviceContainer['PriceMargin'] = function () {
            return new S\PriceMarginService();
        };

        $serviceContainer['Product'] = function () {
            return new S\ProductService();
        };

        $serviceContainer['ProductImage'] = function () {
            return new S\ProductImageService();
        };

        $serviceContainer['ProductSearch'] = function () {
            return new S\ProductSearchService();
        };

        $serviceContainer['Log'] = function () {
            return new S\LogService();
        };

        $serviceContainer['IntegratedOrderFulfillment'] = function () {
            return new S\IntegratedOrderFulfillmentService();
        };

        $serviceContainer['DeliveryTime'] = function () {
            return new S\DeliveryTimeService();
        };

        $serviceContainer['ProductCreation'] = function () {
            return new S\ProductCreationService();
        };

        $serviceContainer['ExchangeRate'] = function () {
            return new S\ExchangeRateService();
        };

        $serviceContainer['User'] = function () {
            return new S\UserService();
        };

        $serviceContainer['Refund'] = function () {
            return new S\RefundService();
        };

        $serviceContainer['So'] = function () {
            return new S\SoService();
        };

        $serviceContainer['SoPriorityScore'] = function () {
            return new S\SoPriorityScoreService();
        };

        $serviceContainer['SoRefundScore'] = function () {
            return new S\SoRefundScoreService();
        };

        $serviceContainer['SoCompensation'] = function () {
            return new S\SoCompensationService();
        };

        $serviceContainer['SplitOrder'] = function () {
            return new S\SplitOrderService();
        };

        $serviceContainer['Warehouse'] = function () {
            return new S\WarehouseService();
        };

        $serviceContainer['WmsInventory'] = function () {
            return new S\WmsInventoryService();
        };

        $serviceContainer['LoadSiteParameter'] = function () {
            return new S\LoadSiteParameterService();
        };

        $serviceContainer['Website'] = function () {
            return new S\WebsiteService();
        };

        $serviceContainer['Price'] = function () {
            return new S\PriceService();
        };

        $serviceContainer['PriceWebsite'] = function () {
            return new S\PriceWebsiteService();
        };

        $serviceContainer['QuickSearch'] = function () {
            return new S\QuickSearchService();
        };

        $serviceContainer['Region'] = function () {
            return new S\RegionService();
        };

        $serviceContainer['Courier'] = function () {
            return new S\CourierService();
        };

        $serviceContainer['Client'] = function () {
            return new S\ClientService();
        };

        $serviceContainer['ComplementaryAcc'] = function () {
            return new S\ComplementaryAccService();
        };

        $serviceContainer['Event'] = function () {
            return new S\EventService();
        };

        $serviceContainer['PdfRendering'] = function () {
            return new S\PdfRenderingService();
        };

        $serviceContainer['ProductApi'] = function () {
            return new S\ProductApiService();
        };

        $serviceContainer['PaymentGateway'] = function () {
            return new S\PaymentGatewayService();
        };

        $serviceContainer['PaymentGatewayRedirectCybersource'] = function () {
            return new S\PaymentGatewayRedirectCybersourceService();
        };

        $serviceContainer['Email'] = function () {
            return new S\EmailService();
        };

        $serviceContainer['Template'] = function () {
            return new S\TemplateService();
        };

        $serviceContainer['SellingPlatform'] = function () {
            return new S\SellingPlatformService();
        };

        $serviceContainer['SkuMapping'] = function () {
            return new S\SkuMappingService();
        };

        $serviceContainer['ProductIdentifier'] = function () {
            return new S\ProductIdentifierService();
        };

        $serviceContainer['Brand'] = function () {
            return new S\BrandService();
        };

        $serviceContainer['PromotionCode'] = function () {
            return new S\PromotionCodeService();
        };

        $serviceContainer['SupplierProd'] = function () {
            return new S\SupplierProdService();
        };

		//Data transfer
        $serviceContainer['VbDataTransferPrices'] = function () {
            return new S\VbDataTransferPricesService();
        };

        $serviceContainer['VbDataTransferBrand'] = function () {
            return new S\VbDataTransferBrandService();
        };

        $serviceContainer['VbDataTransferCategoryExtend'] = function () {
            return new S\VbDataTransferCategoryExtendService();
        };

        $serviceContainer['VbDataTransferCategory'] = function () {
            return new S\VbDataTransferCategoryService();
        };

        $serviceContainer['VbDataTransferColourExtend'] = function () {
            return new S\VbDataTransferColourExtendService();
        };

        $serviceContainer['VbDataTransferColour'] = function () {
            return new S\VbDataTransferColourService();
        };

        $serviceContainer['VbDataTransferFreightCat'] = function () {
            return new S\VbDataTransferFreightCatService();
        };

        $serviceContainer['VbDataTransferProductContentExtend'] = function () {
            return new S\VbDataTransferProductContentExtendService();
        };

        $serviceContainer['VbDataTransferProductContent'] = function () {
            return new S\VbDataTransferProductContentService();
        };

        $serviceContainer['VbDataTransferProductCustomClass'] = function () {
            return new S\VbDataTransferProductCustomClassService();
        };

        $serviceContainer['VbDataTransferProductIdentifier'] = function () {
            return new S\VbDataTransferProductIdentifierService();
        };

        $serviceContainer['VbDataTransferProductImage'] = function () {
            return new S\VbDataTransferProductImageService();
        };

        $serviceContainer['VbDataTransferProductKeyword'] = function () {
            return new S\VbDataTransferProductKeywordService();
        };

        $serviceContainer['VbDataTransferProductNote'] = function () {
            return new S\VbDataTransferProductNoteService();
        };

        $serviceContainer['VbDataTransferProducts'] = function () {
            return new S\VbDataTransferProductsService();
        };

        $serviceContainer['VbDataTransferProductWarranty'] = function () {
            return new S\VbDataTransferProductWarrantyService();
        };

        $serviceContainer['VbDataTransferRaGroupContent'] = function () {
            return new S\VbDataTransferRaGroupContentService();
        };

        $serviceContainer['VbDataTransferRaGroupProduct'] = function () {
            return new S\VbDataTransferRaGroupProductService();
        };

        $serviceContainer['VbDataTransferRaGroup'] = function () {
            return new S\VbDataTransferRaGroupService();
        };

        $serviceContainer['VbDataTransferRaProdCat'] = function () {
            return new S\VbDataTransferRaProdCatService();
        };

        $serviceContainer['VbDataTransferRaProduct'] = function () {
            return new S\VbDataTransferRaProductService();
        };

        $serviceContainer['VbDataTransferSupplierProduct'] = function () {
            return new S\VbDataTransferSupplierProductService();
        };

        $serviceContainer['VbDataTransferVersion'] = function () {
            return new S\VbDataTransferVersionService();
        };

        $serviceContainer['VbProductImage'] = function () {
            return new S\VbProductImageService();
        };

        $serviceContainer['VbDataTransferExternalCategory'] = function () {
            return new S\VbDataTransferExternalCategoryService();
        };

        $serviceContainer['VbDataTransferExtCategoryMapping'] = function () {
            return new S\VbDataTransferExtCategoryMappingService();
        };

        $serviceContainer['VbDataTransferCategoryMapping'] = function () {
            return new S\VbDataTransferCategoryMappingService();
        };

        $serviceContainer['Affiliate'] = function () {
            return new S\AffiliateService();
        };

        $serviceContainer['SoFactory'] = function () {
            return new S\SoFactoryService();
        };

        $serviceContainer['SoPaymentQueryLog'] = function () {
            return new S\SoPaymentQueryLogService();
        };

        $serviceContainer['SoPaymentLog'] = function () {
            return new S\SoPaymentLogService();
        };

        $serviceContainer['CartSession'] = function () {
            return new S\CartSessionService();
        };

        $serviceContainer['PricingRules'] = function () {
            return new S\PricingRulesService();
        };

        $serviceContainer['PlatformBizVar'] = function () {
            return new S\PlatformBizVarService();
        };

        $serviceContainer['Google'] = function () {
            return new S\GoogleService();
        };

        $serviceContainer['GoogleShopping'] = function () {
            return new S\GoogleShoppingService();
        };

        $serviceContainer['GoogleConnect'] = function () {
            return new S\GoogleConnectService();
        };

        $serviceContainer['GoogleRequestBatch'] = function () {
            return new S\GoogleRequestBatchService();
        };

        $serviceContainer['PendingGoogleApiRequest'] = function () {
            return new S\PendingGoogleApiRequestService();
        };

        $serviceContainer['GoogleApiRequest'] = function () {
            return new S\GoogleApiRequestService();
        };

        $serviceContainer['CategoryMapping'] = function () {
            return new S\CategoryMappingService();
        };

        $serviceContainer['PricingTool'] = function () {
            return new S\PricingToolService();
        };

        $serviceContainer['DisplayQty'] = function () {
            return new S\DisplayQtyService();
        };

        $serviceContainer['Supplier'] = function () {
            return new S\SupplierService();
        };

        $serviceContainer['AffiliateSkuPlatform'] = function () {
            return new S\AffiliateSkuPlatformService();
        };

        $serviceContainer['BundleConfig'] = function () {
            return new S\BundleConfigService();
        };

        $serviceContainer['PricingToolWebsite'] = function () {
            return new S\PricingToolWebsiteService();
        };

        $serviceContainer['PricingToolEbay'] = function () {
            return new S\PricingToolEbayService();
        };

        $serviceContainer['Flex'] = function () {
            return new S\FlexService();
        };

        $serviceContainer['Pagination'] = function () {
            return new S\PaginationService();
        };

        $serviceContainer['RptOrderNotInRiaReport'] = function () {
            return new S\RptOrderNotInRiaReportService();
        };

        $serviceContainer['MoneybookersPmgwReport'] = function () {
            return new S\MoneybookersPmgwReportService();
        };

        $serviceContainer['PaypalHkPmgwReport'] = function () {
            return new S\PaypalHkPmgwReportService();
        };

        $serviceContainer['PaypalAuPmgwReport'] = function () {
            return new S\PaypalAuPmgwReportService();
        };

        $serviceContainer['PaypalNzPmgwReport'] = function () {
            return new S\PaypalNzPmgwReportService();
        };

        $serviceContainer['DeliveryType'] = function () {
            return new S\DeliveryTypeService();
        };

        $serviceContainer['Delivery'] = function () {
            return new S\DeliveryService();
        };

    }
}
