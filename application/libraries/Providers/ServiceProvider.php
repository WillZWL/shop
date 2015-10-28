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
        $servcieContainer['Aftership'] = function ($c) {
            return new S\AftershipService();
        };

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

        $servcieContainer['Clwms'] = function () {
            return new S\ClwmsService();
        };

        $servcieContainer['DeliveryOption'] = function () {
            return new S\DeliveryOptionService();
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

        $servcieContainer['ProductSearch'] = function () {
            return new S\ProductSearchService();
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

        $servcieContainer['Refund'] = function () {
            return new S\RefundService();
        };

        $servcieContainer['So'] = function () {
            return new S\SoService();
        };

        $servcieContainer['SoPriorityScore'] = function () {
            return new S\SoPriorityScoreService();
        };

        $servcieContainer['SoRefundScore'] = function () {
            return new S\SoRefundScoreService();
        };

        $servcieContainer['SplitOrder'] = function () {
            return new S\SplitOrderService();
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

        $servcieContainer['Price'] = function () {
            return new S\PriceService();
        };

        $servcieContainer['PriceWebsite'] = function () {
            return new S\PriceWebsiteService();
        };

        $servcieContainer['QuickSearch'] = function () {
            return new S\QuickSearchService();
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

        $servcieContainer['ProductApi'] = function () {
            return new S\ProductApiService();
        };

        $servcieContainer['PaymentGateway'] = function () {
            return new S\PaymentGatewayService();
        };

        $servcieContainer['PaymentGatewayRedirectCybersource'] = function () {
            return new S\PaymentGatewayRedirectCybersourceService();
        };

        $servcieContainer['EmailTemplate'] = function () {
            return new S\EmailTemplateService();
        };
		
		
		//Data transfer
        $servcieContainer['VbDataTransferPricesService'] = function () {
            return new S\VbDataTransferPricesService();
        };
		
        $servcieContainer['VbDataTransferBrandService'] = function () {
            return new S\VbDataTransferBrandService();
        };
		
        $servcieContainer['VbDataTransferCategoryExtendService'] = function () {
            return new S\VbDataTransferCategoryExtendService();
        };
		
        $servcieContainer['VbDataTransferCategoryService'] = function () {
            return new S\VbDataTransferCategoryService();
        };
		
        $servcieContainer['VbDataTransferColourExtendService'] = function () {
            return new S\VbDataTransferColourExtendService();
        };
		
        $servcieContainer['VbDataTransferColourService'] = function () {
            return new S\VbDataTransferColourService();
        };
		
        $servcieContainer['VbDataTransferFreightCatService'] = function () {
            return new S\VbDataTransferFreightCatService();
        };
		
        $servcieContainer['VbDataTransferProductContentExtendService'] = function () {
            return new S\VbDataTransferProductContentExtendService();
        };
		
        $servcieContainer['VbDataTransferProductContentService'] = function () {
            return new S\VbDataTransferProductContentService();
        };
		
        $servcieContainer['VbDataTransferProductCustomClassService'] = function () {
            return new S\VbDataTransferProductCustomClassService();
        };
		
        $servcieContainer['VbDataTransferProductIdentifierService'] = function () {
            return new S\VbDataTransferProductIdentifierService();
        };
		
        $servcieContainer['VbDataTransferProductImageService'] = function () {
            return new S\VbDataTransferProductImageService();
        };
		
        $servcieContainer['VbDataTransferProductKeywordService'] = function () {
            return new S\VbDataTransferProductKeywordService();
        };
		
        $servcieContainer['VbDataTransferProductNoteService'] = function () {
            return new S\VbDataTransferProductNoteService();
        };
		
        $servcieContainer['VbDataTransferProductsService'] = function () {
            return new S\VbDataTransferProductsService();
        };
		
        $servcieContainer['VbDataTransferProductWarrantyService'] = function () {
            return new S\VbDataTransferProductWarrantyService();
        };
		
        $servcieContainer['VbDataTransferRaGroupContentService'] = function () {
            return new S\VbDataTransferRaGroupContentService();
        };
		
        $servcieContainer['VbDataTransferRaGroupProductService'] = function () {
            return new S\VbDataTransferRaGroupProductService();
        };
		
        $servcieContainer['VbDataTransferRaGroupService'] = function () {
            return new S\VbDataTransferRaGroupService();
        };
		
        $servcieContainer['VbDataTransferRaProdCatService'] = function () {
            return new S\VbDataTransferRaProdCatService();
        };
		
        $servcieContainer['VbDataTransferRaProductService'] = function () {
            return new S\VbDataTransferRaProductService();
        };
		
        $servcieContainer['VbDataTransferSupplierProductService'] = function () {
            return new S\VbDataTransferSupplierProductService();
        };
		
        $servcieContainer['VbDataTransferVersionService'] = function () {
            return new S\VbDataTransferVersionService();
        };		
		
        $servcieContainer['VbProductImageService'] = function () {
            return new S\VbProductImageService();
        };
    }
}
