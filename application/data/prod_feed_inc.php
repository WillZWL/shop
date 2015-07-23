<?php

$ProductIDType = "ASIN";
$fulfillmentCentreID = 'DEFAULT';
$isSecondHand = 0;
$autoPrice = 0;
$AFCondition = "New";
$shiptype = "direct";
$AFProductTaxCode = "A_GEN_NOTAX";
$AFLaunchDate = "01-01-2008";
$AFSalesEndPeriod = 172800;
$AFConditionNote_amuk = "\"Valuebasket provides FREE delivery on all orders! This item is brand new & dispatched from our UK warehouse. We offer a dedicated customer service team and a money back guarantee.\"";
$AFConditionNote_amfr = "\"Valuebasket vous offre les meilleurs produits au meilleurs prix. Nous vous assurons une livraison rapide, un remboursement garantie, et un service clientèle dédié à répondre à toutes vos questions. Votre carte de crédit ne sera chargée qu'après l'expédition de la commande.\"";
$AFDefaultFreightWeight = 2.00;
$AFMaxDesLength = 1980;
$AFShippingWeightUnit = "KG";
$AFIsAvailable = "False";
$AFSerialNumberRequired = "false";
$AFIsGiftWrapAvailable = "false";
$AFIsGiftMessageAvailable = "false";
$AFDeliveryChannel = "direct_ship";
$FeedHeader = "SKU,ProductClass,ProductType,StandardProductID,ProductIDType,ProductTaxCode,LaunchDate,DiscontinueDate,ReleaseDate,ConditionType,ConditionNote,RebateStartDate,RebateEndDate,RebateMessage,RebateName,Title,Brand,Designer,Description,ItemDimensionsLength,ItemDimensionsLengthUnit,ItemDimensionsWidth,ItemDimensionsWidthUnit,ItemDimensionsHeight,ItemDimensionsHeightUnit,ItemDimensionsWeight,ItemDimensionsWeightUnit,ShippingWeight,ShippingWeightUnit,MerchantCatalogNumber,MaxOrderQuantity,SerialNumberRequired,Prop65,LegalDisclaimer,Manufacturer,MfrPartNumber,Memorabilia,Autographed,ItemType,IsGiftWrapAvailable,IsGiftMessageAvailable,IsDiscontinuedByManufacturer,MaxAggregateShipQuantity,Priority,BrowseExclusion,RecommendationExclusion,RegisteredParameter,BulletPoint,BulletPoint,BulletPoint,BulletPoint,BulletPoint,SearchTerms,SearchTerms,SearchTerms,SearchTerms,SearchTerms,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,PlatinumKeywords,UsedFor,UsedFor,UsedFor,UsedFor,UsedFor,OtherItemAttribute,OtherItemAttribute,OtherItemAttribute,OtherItemAttribute,OtherItemAttribute,TargetAudience,TargetAudience,TargetAudience,SubjectContent,SubjectContent,SubjectContent,SubjectContent,SubjectContent,DeliveryChannel,DeliveryChannel,Cost,MSRP,SalePrice,SaleStartDate,SaleEndDate,MainImageURL,SwatchImageURL,OtherImageURL1,OtherImageURL2,OtherImageURL3,OtherImageURL4,OtherImageURL5,OtherImageURL6,OtherImageURL7,OtherImageURL8,FulfillmentCenterID,IsAvailable,Quantity,FulfillmentLatency,ParentSKU,RestockDate,RelationType,RelationQuantity,SortHeuristic,TypedPrice,Reserved,Reserved,Reserved";
$headersPEngine = "SKU" . "," . "RepriceEnabled" . "," . "ASIN" . "," . "EAN" . "," . "UPC" . "," . "ProductSearchIndex" . "," . "BasicCost" . "\n";
$headersPrice = "SKU" . "," . "ListPrice" . "," . "SalePrice" . "," . "StartDate" . "," . "EndDate" . "\n";
$headersInv = "SKU" . "," . "FulfillmentCentreID" . "," . "IsAvailable" . "," . "Quantity" . "," . "RestockDate" . "," . "FulfillmentLatency" . "\n";

$lang = array(
    "profit_alert_title" => "Negative Profit Margin Detected",
    "profit_alert" => "Please kindly notice that the following skus were skipped because of having negative profit margin while it is not on clearance: ",
    "nothing_to_feed_title" => "No item was put into the feed",
    "nothing_to_feed" => "Since there was no matching product to be fed, no feed was generated and uploaded to Ixtens"
);

?>