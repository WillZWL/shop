<?php if(! defined('BASEPATH')) exit('No Direct script access allowed');
include_once "Base_service.php";
include_once(APPPATH."hooks/Country_selection.php");

define('SRC_PATH_2',BASEPATH. 'plugins/adwords/src');
define('ADS', BASEPATH. 'plugins/adwords/src/Google/Api/Ads');
ini_set('include_path', implode(array(
    ini_get('include_path'), PATH_SEPARATOR, SRC_PATH_2
)));

define('ADWORDS_LIB',ADS.'/AdWords/Lib');
define('COMMON_LIB',ADS.'Common/Lib');
include_once ADWORDS_LIB.'/AdWordsUser.php';
//define('ADWORDS_VERSION', 'v201306');
//define('ADWORDS_VERSION', 'v201402');
//define('ADWORDS_VERSION', 'v201406');
//define('ADWORDS_VERSION', 'v201409');
define('ADWORDS_VERSION', 'v201502');


class Adwords_service extends Base_service
{
    private $user;
    private $platform_biz_var_service;
    private $product_service;
    private $price_dao;
    private $adwords_data_dao;
    private $product_dao;
    private $context_config_service;
    private $cache_api_request_dao;
    private $config_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . 'libraries/service/Platform_biz_var_service.php');
        $this->platform_biz_var_service = new Platform_biz_var_service();
        include_once(APPPATH . 'libraries/service/Product_service.php');
        $this->product_service = new Product_service();

        include_once(APPPATH . 'libraries/service/Context_config_service.php');
        $this->context_config_service = new Context_config_service();

        //include_once(APPPATH . 'libraries/service/Googlebase_product_feed_service.php');
        //$this->product_service = new Googlebase_product_feed_service();

        include_once(APPPATH . 'libraries/dao/Price_dao.php');
        $this->price_dao = new Price_dao();

        include_once(APPPATH . 'libraries/dao/Adwords_data_dao.php');
        $this->adwords_data_dao = new Adwords_data_dao();

        include_once(APPPATH . 'libraries/dao/Product_dao.php');
        $this->product_dao = new Product_dao();

        include_once(APPPATH . 'libraries/dao/Cache_api_request_dao.php');
        $this->cache_api_request_dao = new Cache_api_request_dao();

        include_once(APPPATH . 'libraries/dao/Config_dao.php');
        $this->config_dao = new Config_dao();

        set_time_limit(3600);
    }

    public function init_account($ad_accountId = "")
    {
        $this->user = new AdWordsUser();
        $this->user->SetClientCustomerId($ad_accountId);
        //$this->user->LogDefaults();
        return $this->user;
    }

    function removeCampaign($ad_accountId = "", $campaignId="")
    {
        try{
         // Get the service, which loads the required classes.
            $campaignService = $this->user->GetService('CampaignService', ADWORDS_VERSION);

            // Create campaign with DELETED status.
            $campaign = new Campaign();
            $campaign->id = $campaignId;
            $campaign->status = 'DELETED';
            // Rename the campaign as you delete it, to avoid future name conflicts.
            $campaign->name = 'Deleted ' . date('Ymd his');

             // Create operations.
            $operation = new CampaignOperation();
            $operation->operand = $campaign;
            $operation->operator = 'SET';

            $operations = array($operation);

            // Make the mutate request.
            $result = $campaignService->mutate($operations);

            // Display result.
            $campaign = $result->value[0];
            //printf("Campaign with ID '%s' was deleted.\n", $campaign->id);
        }catch(Exception $e) {
          //printf("An error has occurred: %s\n", $e->getMessage());
        }
    }


    function get_specific_campaign($user, $campaing_name="")
    {
        try
        {
            $campaignService = $this->user->GetService('CampaignService', ADWORDS_VERSION);
            $selector = new Selector();
            $selector->fields = array('Id', 'Name', 'Status', 'Cost');
            $selector->predicates[] = new Predicate('Name','EQUALS', $campaing_name);
            $selector->predicates[] =
                new Predicate('Status', 'IN', array('ENABLED','PAUSED'));
            $selector->ordering[] = new OrderBy('Name', 'ASCENDING');
            $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
            $result = array();
            do{
                $page = $campaignService->get($selector);
                if($page->totalNumEntries >1)
                {
                    return array("duplicate"=>"more than one Campaign $campaing_name Found");
                }
                else
                {
                    if (isset($page->entries)) {
                        return $page->entries[0];
                    } else {
                        return array("empty"=>"No Campaign Result Found");
                    }
                }
                $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
            }while ($page->totalNumEntries > $selector->paging->startIndex);
        }catch(Exception $e){
            return $result = array('error' => "An error has occurred: ". $e->getMessage());
        }
    }

    function get_specific_adGroup($user, $campaingId = '', $adGroupName = '')
    {
        try
        {
            $adGroupAdService = $user->GetService('AdGroupService', ADWORDS_VERSION);
            $selector = new Selector();
            $selector->fields = array('Id', 'Name');
            $selector->predicates[] = new Predicate('CampaignId','EQUALS', $campaingId);
            $selector->predicates[] = new Predicate('Name','STARTS_WITH', $adGroupName);
            $selector->ordering[] = new OrderBy('Name', 'ASCENDING');
            $selector->predicates[] =
                new Predicate('Status', 'IN', array('ENABLED','PAUSED'));
            $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
            $result = array();
            $page = $adGroupAdService->get($selector);
            if($page->totalNumEntries >1)
            {
                return array("duplicate"=>"more than one adGroup $adGroupName Found in CampaignID: ".$campaingId);
            }
            else
            {
                if (isset($page->entries)) {
                    return $page->entries[0];
                } else {
                    return array("empty"=>"No adGroup Result Found in campaingID: ".$campaingId);
                }
            }
        }catch(Exception $e){
            return $result = array('error' => "An error has occurred: ". $e->getMessage());
        }
        return $result;
    }

    function get_specific_keyword($user, $adGroupId = '', $text = false)
    {
        # set $text as true if you want the keyword text & id
        try
        {
            $adGroupCriterionService = $user->GetService('AdGroupCriterionService', ADWORDS_VERSION);
            $selector = new Selector();
            $selector->fields = array('KeywordText', 'Id');
            $selector->predicates[] = new Predicate('AdGroupId', 'IN', array($adGroupId));
            $selector->predicates[] =
                new Predicate('CriteriaType', 'IN', array('KEYWORD'));
            $selector->predicates[] =
                new Predicate('Status', 'IN', array('ENABLED','PAUSED'));
            $selector->ordering[] = new OrderBy('KeywordText', 'ASCENDING');
            $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
            $result = array();
            $page = $adGroupCriterionService->get($selector);

            $result = array();
            do {
                // Make the get request.
                $page = $adGroupCriterionService->get($selector);

                // Display results.
                if (isset($page->entries))
                {
                    foreach ($page->entries as $adGroupCriterion)
                    {
                        if($text === false)
                        {
                            //return the keywords ID
                            $result[] = $adGroupCriterion->criterion->id;
                        }
                        else
                        {
                            // return keyword text with keyword_id as array key
                            $id = $adGroupCriterion->criterion->id;
                            $result[$id] = $adGroupCriterion->criterion->text;
                        }
                    }
                }
                else
                {
                    return array("empty"=>"No keyword Result Found in adGroupId: ".$adGroupId);
                }

                // Advance the paging index.
                $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
            } while ($page->totalNumEntries > $selector->paging->startIndex);
        }catch(Exception $e){
            return $result = array('error' => "An error has occurred: ". $e->getMessage());
        }
        return $result;
    }

    function add_paramter_to_keyword($user, $adGroupId, $keywordId_list = array(), $ad_content = array())
    {
        try{
            $adParamService = $user->GetService('AdParamService', ADWORDS_VERSION);

            $operation = array();

            foreach($keywordId_list as $keywordId)
            {
                $adParamOperation = new AdParamOperation();
                $price = $ad_content['price'];
                //insertionText in paramIndex 1 only, as there is one param -- price only
                $adParam = new AdParam($adGroupId, $keywordId, $price, 1);
                $adParamOperation->operand = $adParam;
                $adParamOperation->operator = 'SET';
                $operations[] = $adParamOperation;
            }

            $adParams = $adParamService->mutate($operations);
            $result = array();
            foreach ($adParams as $adParam) {
                $result[$adParam->paramIndex] = $adParam->insertionText;
            }
            return $result;
        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }
    }



    function create_adGroup($user, $campaignId = "", $adGroupName = '')
    {
        try
        {
            $adGroupAdService = $user->GetService('AdGroupService', ADWORDS_VERSION);
            $adGroup = new AdGroup();
            $adGroup->campaignId = $campaignId;
            $adGroup->name = $adGroupName;

            // Set bids (required).
            $bid = new CpcBid();
            $bid->bid =  new Money(500000);
            //$bid->contentBid = new Money(500000);
            $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
            $biddingStrategyConfiguration->bids[] = $bid;
            $adGroup->biddingStrategyConfiguration = $biddingStrategyConfiguration;

            // Set additional settings (optional).
            $adGroup->status = 'ENABLED';

            // Create operation.
            $operation = new AdGroupOperation();
            $operation->operand = $adGroup;
            $operation->operator = 'ADD';
            $operations[] = $operation;

            $result = $adGroupAdService->mutate($operations);
            if(count($result->value) == 1)
            {
                return $result->value[0];
            }
            else
            {
                return $result = array('error' => "adGroup Create failed");
            }
        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }
    }

    function create_adText($user, $adGroupId = '', $adContent = '')
    {
        try
        {
            $adGroupAdService = $user->GetService('AdGroupAdService', ADWORDS_VERSION);
            $numAds = 2;
            $opertions = array();

            for($i = 0; $i < $numAds; $i++)
            {
                $textAd = new TextAd();
                $textAd->headline = $adContent['headline'];
                $textAd->description1 =  $adContent["description_info"][$i]["line_1"];
                $textAd->description2 =  $adContent["description_info"][$i]["line_2"];
                $textAd->displayUrl = $adContent["display_url"];
                $textAd->url =  $adContent["destination_url"];

                // Create ad group ad.
                $adGroupAd = new AdGroupAd();
                $adGroupAd->adGroupId = $adGroupId;
                $adGroupAd->ad = $textAd;
                // Set additional settings (optional).
                $adGroupAd->status = 'ENABLED';
                // Create operation.
                $operation = new AdGroupAdOperation();
                $operation->operand = $adGroupAd;
                $operation->operator = 'ADD';
                $operations[] = $operation;
            }


            $result = $adGroupAdService->mutate($operations);

            if(count($result->value) == 2)
            {
                return $result->value;
            }
            else
            {
                return $result = array('error' => "adText Create failed");
            }
        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }
    }

    function create_keyword($user, $adGroupId = "", $ad_content = array(), $matchtype = "EXACT")
    {
        try
        {
            $adGroupCriterionService =
                $user->GetService('AdGroupCriterionService', ADWORDS_VERSION);

            $MaxNumberKeywords = min(count($ad_content['keyword']), 5000);
            $operations = array();
            $matchTypeList = array('EXACT', 'PHRASE', 'BROAD');

            for($i = 0; $i< $MaxNumberKeywords; $i++)
            {
                // EXACT, PHRASE, BROAD
                foreach($matchTypeList as $matchType)
                {
                    $keyword = new Keyword();

                    if($matchType == "BROAD")
                    {
                        //change the Broad match to Broad modifier match
                        $temp_arr = explode(" ", $ad_content["keyword"][$i]);
                        $new_keyword = "+".implode(" +", $temp_arr);
                        $ad_content["keyword"][$i] = $new_keyword;

                        if(strlen($new_keyword) > 80)
                        {
                            continue;
                        }
                    }

                    $keyword->text = $ad_content["keyword"][$i];

                    // Create biddable ad group criterion.
                    $keyword->matchType = $matchType;
                    $adGroupCriterion = new BiddableAdGroupCriterion();
                    $adGroupCriterion->adGroupId = $adGroupId;
                    $adGroupCriterion->criterion = $keyword;

                    // Set additional settings (optional).
                    $adGroupCriterion->userStatus = 'ENABLED';
                    //$adGroupCriterion->destinationUrl = $ad_content["destination_url"];

                    // Set bids (optional).
                    $bid = new CpcBid();
                    $bid->bid = new Money(1000000);
                    $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
                    $biddingStrategyConfiguration->bids[] = $bid;
                    $adGroupCriterion->biddingStrategyConfiguration = $biddingStrategyConfiguration;

                    $adGroupCriteria[] = $adGroupCriterion;

                    // Create operation.
                    $operation = new AdGroupCriterionOperation();
                    $operation->operand = $adGroupCriterion;
                    $operation->operator = 'ADD';
                    $operations[] = $operation;
                }

                /*
                switch ($matchtype)
                {
                    case 'EXACT':
                        $keyword->matchType = 'EXACT';
                        break;

                    case 'PHRASE':
                        $keyword->matchType = 'PHRASE';
                        break;

                    case 'BROAD':
                        $keyword->matchType = 'BROAD';
                        break;

                    default:
                        $keyword->matchType = 'EXACT';
                        break;
                }
                */
            }

            $result = $adGroupCriterionService->mutate($operations);


            if(count($result->value) > 0)
            {
                return $result->value[0];
            }
            else
            {
                return $result = array('error' => "Keyword Create failed");
            }
        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }
    }

    function compaign_info($user)
    {
         $campaignService = $user->GetService('CampaignService', ADWORDS_VERSION);
        $ad_accountId = $user->GetClientCustomerId();

        $selector = new Selector();
        $selector->fields = array('Id', 'Name');
        //$selector->predicates[] = new Predicate('Name','EQUALS', 'Digital Cameras');
        $selector->ordering[] = new OrderBy('Name', 'ASCENDING');
        $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
        $result = array();

        try{
            do{
            $page = $campaignService->get($selector);
                // Display results.
                if (isset($page->entries)) {
                  foreach ($page->entries as $campaign) {
                    $result[] = array("campaignName"=>$campaign->name, "campaignId"=>$campaign->id, "ad_accountId"=>$ad_accountId);
                  }
                } else {
                  $result[] = array('error' => "No campaigns were found");
                }
                // Advance the paging index.
            $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
            }while ($page->totalNumEntries > $selector->paging->startIndex);
        }catch(Exception $e){
            $result[] = array('error' => "An error has occurred: ". $e->getMessage());
        }
        return $result;
    }


    function adgroup_info($user, $campaignId)
    {
        $adGroupService = $user->GetService('AdGroupService', ADWORDS_VERSION);
        $ad_accountId = $user->GetClientCustomerId();
        $selecttor = new Selector();
        $selector->fields = array("Id", "Name");
        $selector->ordering[] =  new OrderBy('Name', 'ASCENDING');
        $selector->predicates[] = new Predicate("CampaignId", "EQUALS", $campaignId);
        $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);

        $result = array();
        try{
            do{
                $page = $adGroupService->get($selector);

                if(isset($page->entries)){
                    foreach ($page->entries as $adGroup) {
                        $result[] = array("adGroupName"=>$adGroup->name, "adGroupId"=>$adGroup->id, "ad_accountId"=>$ad_accountId, "campaignId"=>$campaignId);
                    }
                }
                else
                {
                     $result[] = array('error' => "No AdGroup were found");
                }
                 $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
            }while ($page->totalNumEntries > $selector->paging->startIndex);
        }catch(Exception $e){
            $result[] = array('error' => "An error has occurred: ". $e->getMessage());
        }
        return $result;
    }

    function keyword_info($user, $adGroupId)
    {
          $ad_accountId = $user->GetClientCustomerId();
          $adGroupCriterionService = $user->GetService('AdGroupCriterionService', ADWORDS_VERSION);

          // Create selector.
          $selector = new Selector();
          $selector->fields = array('KeywordText', 'KeywordMatchType', 'Id', 'Parameter');
          $selector->ordering[] = new OrderBy('KeywordText', 'ASCENDING');

          // Create predicates.
          $selector->predicates[] = new Predicate('AdGroupId', 'IN', array($adGroupId));
          $selector->predicates[] =
              new Predicate('CriteriaType', 'IN', array('KEYWORD'));

          $selector->predicates[] =
              new Predicate('Status', 'EQUALS', 'ENABLED');
          // Create paging controls.
          $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);

        $result = array();
        try{
            do{
                $page = $adGroupCriterionService->get($selector);
                if(isset($page->entries)){
                    foreach ($page->entries as $adGroupCriterion) {
                        $result[] = array("keyword"=>$adGroupCriterion->criterion->text, "matchType"=>$adGroupCriterion->criterion->matchType, "keywordId"=>$adGroupCriterion->criterion->id,
                                    "ad_accountId"=>$ad_accountId, "adGroupId"=>$adGroupId,
                                    "paramter"=>$adGroupCriterion->criterion->parameter
                                    );
                    }
                }
                else
                {
                     $result[] = array('error' => "No AdGroup were found");
                }
                 $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
            }while ($page->totalNumEntries > $selector->paging->startIndex);
        }catch(Exception $e){
            $result[] = array('error' => "An error has occurred: ". $e->getMessage());
        }
        return $result;
    }

    public function keyword_parameter_info($user, $adGroupId, $keywordId)
    {
         $adParamService = $user->GetService('AdParamService', ADWORDS_VERSION);
         $selector = new Selector();
         $selector->fields = array('CriterionId', 'InsertionText', 'ParamIndex');
         $selector->predicates[] = new Predicate('AdGroupId', 'IN', array($adGroupId));

        $adParamPage = $adParamService->get($selector);
        return  $adParamPage->entries;
    }


    public function adGroup_ad_info($user, $adGroupId)
    {
        $adGroupCriterionService = $user->GetService('AdGroupAdService', ADWORDS_VERSION);
        $ad_accountId = $user->GetClientCustomerId();
        // Create selector.
        $selector = new Selector();
        $selector->fields = array('Headline', 'Id');
        $selector->ordering[] = new OrderBy('Headline', 'ASCENDING');

        // Create predicates.
        $selector->predicates[] = new Predicate('AdGroupId', 'IN', array($adGroupId));
        $selector->predicates[] = new Predicate('AdType', 'IN', array('TEXT_AD'));
        $selector->predicates[] =
            //new Predicate('Status', 'IN', array('ENABLED', 'PAUSED', 'DISABLED'));
            new Predicate('Status', 'IN', array('ENABLED'));
        // Create paging controls.
        $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);

        $result = array();
        try{
            do{
                $page = $adGroupCriterionService->get($selector);
                if(isset($page->entries)){
                    foreach ($page->entries as $adGroupAd) {
                        $result[] = array("headline"=>$adGroupAd->ad->headline, "adGroupAd_id"=>$adGroupAd->ad->id, "ad_accountId"=>$ad_accountId, "adGroupId"=>$adGroupId,
                        "adGroupAd_url"=>$adGroupAd->ad->url, "adGroupAp_display_url"=>$adGroupAd->ad->displayUrl,
                        "adGroupAd_description1"=>$adGroupAd->ad->description1,
                        "adGroupAd_description2"=>$adGroupAd->ad->description2
                        );
                    }
                }
                else
                {
                     $result[] = array('error' => "No AdGroup were found");
                }
                 $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
            }while ($page->totalNumEntries > $selector->paging->startIndex);
        }catch(Exception $e){
            $result[] = array('error' => "An error has occurred: ". $e->getMessage());
        }
        return $result;
    }


    public function deleteAd($ad_accountId, $adGroupId, $adId)
    {
        $this->user->SetClientCustomerId($ad_accountId);
         $adGroupAdService = $this->user->GetService('AdGroupAdService', ADWORDS_VERSION);

  // Create base class ad to avoid setting type specific fields.
      $ad = new Ad();
      $ad->id = $adId;

      // Create ad group ad.
      $adGroupAd = new AdGroupAd();
      $adGroupAd->adGroupId = $adGroupId;
      $adGroupAd->ad = $ad;

      // Create operation.
      $operation = new AdGroupAdOperation();
      $operation->operand = $adGroupAd;
      $operation->operator = 'REMOVE';

      $operations = array($operation);

      // Make the mutate request.
      try{
      $result = $adGroupAdService->mutate($operations);
      }catch(Exception $e){
        echo $e->getMessage();
      }


      // Display result.
      $adGroupAd = $result->value[0];
      //printf("Ad with ID '%s' was deleted.\n", $adGroupAd->ad->id);
    }

    public function deleteAdGroup($user, $adGroupId, $ad_content = array())
    {
        try{
            $adGroupService = $user->GetService('AdGroupService', ADWORDS_VERSION);

             // Create ad group with DELETED status.
            $adGroup = new AdGroup();
            $adGroup->id = $adGroupId;
            $adGroup->status = 'DELETED';
            // Rename the ad group as you delete it, to avoid future name conflicts.
            if($ad_content)
            {
                $adGroup_name = $ad_content['sku'];
            }
            else
            {
                $adGroup_name = '';
            }

            $adGroup->name = 'Deleted '. $adGroup_name. ' ' . date('Ymd his');

            // Create operations.
            $operation = new AdGroupOperation();
            $operation->operand = $adGroup;
            $operation->operator = 'SET';

            $operations = array($operation);

            // Make the mutate request.
            return $result = $adGroupService->mutate($operations);
        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }

    }

    public function mail_adcontent($ad_content, $subject)
    {
        $mail_content = "";
        if(is_array($ad_content))
        {
            foreach($ad_content as $key=>$val)
            {
                $mail_content .= $key . ":" . $val . "\r\n";
            }
            $mail_content = wordwrap($mail_content, 70, "\r\n");
        }
        mail("adwords@eservicesgroup.com", $subject, $mail_content);
        //mail("jesslyn@eservicesgroup.com", $subject, $mail_content);
    }

    public function pause_or_resume_adGroup($sku, $platform_id, $status)
    {
        $ad_content = $this->process_data($sku, $platform_id);

        if(!$result = $this->is_valid_ad_content($ad_content))
        {
            $sbuject = 'pause ad: invalid ad content';
            $ad_content['error'] = "invalid ad content";
            $this->mail_adcontent($ad_content,$sbuject);
        }
        else
        {
            if($user = $this->init_account($ad_content["ad_accountId"]))
            {
                $result = $this->get_specific_campaign($user, $ad_content["cat_name"]);
                if(array_key_exists('error', $result))
                {
                    $sbuject = 'Pause ad Error';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['error'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('duplicate', $result))
                {
                    $sbuject = 'Update campaign Duplicate Error: duplicate campaign';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['duplicate'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('empty', $result))
                {
                    $sbuject = 'pause ad warning: campaign does not exist';
                    $ad_content['error'] = $sbuject;
                    $this->mail_adcontent($ad_content,$sbuject);
                }
                else
                {
                    $campaingId = $result->id;
                    $adGroupName = $sku;
                    $result = $this->get_specific_adGroup($user, $campaingId, $adGroupName);
                    if(array_key_exists('error', $result))
                    {
                        $sbuject = 'Pause Ad Error';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['error'];


                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('duplicate', $result))
                    {
                        $sbuject = 'Pause Ad Error: duplicate adGroup';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['duplicate'];

                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('empty', $result))
                    {
                        //if not adGroup found, then do nothing
                        $ad_content['error'] = "pause ad warning: adGroup no Exists";
                    }
                    else
                    {
                        $adGroupId = $result->id;
                        $result = $this->update_adGroup_handler_v2($user, $adGroupId, $status);
                        if(array_key_exists('error',$result))
                        {
                            $sbuject = 'Pause Ad Error';
                            $ad_content['File'] = __FILE__;
                            $ad_content['Line'] = __LINE__;
                            $ad_content['error'] = $result['error'];

                            $this->mail_adcontent($ad_content,$sbuject);
                        }
                        else
                        {
                            $sbuject = "Adwords Success";
                            $ad_content['success'] = $result['success'];
                            $this->mail_adcontent($ad_content,$sbuject);
                        }
                    }

                }
            }
        }

        if(isset($ad_content['error']))
        {
            $this->api_request_result_update($sku, $platform_id, 0, $ad_content['error']);
        }
        else
        {
            $this->api_request_result_update($sku, $platform_id, 1, "");
        }
    }

    /*
    function update_adGroup_handler($user, $adGroupId, $status='PAUSED')
    {
        try{
            $adGroupService = $user->GetService('AdGroupService', ADWORDS_VERSION);
            $adGroup = new AdGroup();
            $adGroup->id = $adGroupId;

            $adGroup->status = $status;
            $operation = new AdGroupOperation();
            $operation->operand = $adGroup;
            $operation->operator = 'SET';

            $operations = array($operation);
            $result = $adGroupService->mutate($operations);

            if($adGroup = $result->value[0])
            {
                return array('success'=>$adGroup->name." $status successfully");
            }
            else
            {
                return $result = array('error' => "$status adGroup Fail");
            }
        }catch(Exception $e){
            return $result= array('error' => "$status adGroup Error: ". $e->getMessage());
        }
    }
*/

/*
    public function start_point($sku, $platform_id)
    {
        $ad_content = $this->process_data($sku, $platform_id);
        if(!$this->is_valid_ad_content($ad_content))
        {
            $sbuject = 'invalid ad content '.$sku .' - '.$platform_id;
            $ad_content['error'] = $sbuject;
            $this->mail_adcontent($ad_content,$sbuject);
        }
        else
        {
            //connect to account using account_id first
            if($user = $this->init_account($ad_content["ad_accountId"]))
            {
                $result = $this->get_specific_campaign($user, $ad_content["cat_name"]);

                if(array_key_exists('error', $result))
                {
                    //this error most probably due to wrong ad_accoundId
                    $sbuject = 'unknown Error: maybe wrong ad_accoundID';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['error'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('duplicate', $result))
                {
                    //if found more than one campaign exists, then do not continue
                    $sbuject = 'Duplicate Error: duplicate campaign';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['duplicate'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('empty', $result))
                {
                    //need to create a new campaign with category name as campaign name
                    $ad_content['error'] = "create adGroup warning, not campaign found";
                }
                else
                {
                    //all this good here, then
                    //return the campaign ID and check the adgroup : sku if already there or not
                    $campaingId = $result->id;
                    $adGroupName = $sku;


                    $result = $this->get_specific_adGroup($user, $campaingId, $adGroupName);

                    if(array_key_exists('error', $result))
                    {
                        $sbuject = 'unknown Error: maybe wrong ad_accoundID';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['error'];

                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('duplicate', $result))
                    {
                        //if found more than one adGroup exists, then do not continue
                        $sbuject = 'Duplicate Error: duplicate adGroup';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['duplicate'];

                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('empty', $result))
                    {
                        //as Expected for a new create product, go ahead and create an adGroup for it.
                        //adGroupName is like 10280-AA-NA_Canon EOS 600D with 18-55mm f/3.5-5.6 IS II Lens Kit
                        $adGroupName = $sku."_".$ad_content['keyword'][0];
                        $result = $this->create_adGroup($user, $campaingId, $adGroupName);
                        if(array_key_exists('error',$result))
                        {
                            $sbuject = 'unknown Error: adGroup create failed';
                            $ad_content['File'] = __FILE__;
                            $ad_content['Line'] = __LINE__;
                            $ad_content['error'] = $result['error'];

                            $this->mail_adcontent($ad_content,$sbuject);
                        }
                        else
                        {
                            //if success, then need to create adText and keywords
                            $sbuject = 'adGroup created successfully';
                            $this->mail_adcontent($ad_content,$sbuject);

                            $adGroupId = $result->id;
                            $result = $this->create_adText($user, $adGroupId, $ad_content);

                            if(array_key_exists('error', $result))
                            {
                                //if error, adText creation fail, so delete the adGroup as well
                                $sbuject = 'unknown Error: adText create failed';
                                $ad_content['File'] = __FILE__;
                                $ad_content['Line'] = __LINE__;
                                $ad_content['error'] = $result['error'];

                                $this->mail_adcontent($ad_content,$sbuject);

                                $delete_result = $this->deleteAdGroup($user, $adGroupId, $ad_content);

                                if(array_key_exists('error', $delete_result))
                                {
                                    $sbuject = 'unknown Error: adText delete failed';
                                    $ad_content['error'] = $delete_result['error'];

                                    $this->mail_adcontent($ad_content,$sbuject);
                                }
                                else
                                {
                                    $sbuject = 'adText delete successfully';
                                    $this->mail_adcontent($ad_content,$sbuject);
                                }
                            }
                            else
                            {
                                //adText create successfully, then create keywords
                                $sbuject = 'adText created successfully';
                                $this->mail_adcontent($ad_content,$sbuject);
                                $keyword_result = $this->create_keyword($user, $adGroupId, $ad_content);

                                if(array_key_exists('error', $keyword_result))
                                {
                                    $sbuject = 'Error: keyword create failed';
                                    $ad_content['error'] = $keyword_result['error'];

                                    $delete_result = $this->deleteAdGroup($user, $adGroupId, $ad_content);
                                    $this->mail_adcontent($ad_content,$sbuject);
                                }
                                else
                                {
                                    $sbuject = $ad_content['sku']. ' - '. $ad_content['platform_id'].'ad created successfully';
                                    //$this->create_adwords_data($ad_content['sku'], $ad_content['platform_id']);
                                    $this->mail_adcontent($ad_content,$sbuject);
                                }

                            }

                        }
                    }
                    else
                    {
                        //mean this sku adGroup already exists here, so what to do next?? delete or what.
                        $subject = "adGroup ".$ad_content["sku"].'-'.$ad_content["platform_id"].' already exists';
                        $ad_content['error'] = "adGroup already exists";
                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                }
            }
        }

        if(isset($ad_content['error']))
        {
            $this->api_request_result_update($sku, $platform_id, 0, $ad_content['error']);
        }
        else
        {
            $this->api_request_result_update($sku, $platform_id, 1, "");
        }
    }
*/
    public function update_ad_price($sku, $platform_id)
    {
         //need first to check if this adGroup -- sku exists or NOT
        $ad_content = $this->process_data($sku, $platform_id);
        if(!$result = $this->is_valid_ad_content($ad_content))
        {
            $sbuject = 'Ad price update: invalid ad content ';
            $ad_content["error"] = $sbuject;
            $this->mail_adcontent($ad_content,$sbuject);
        }
        else
        {
            if($user = $this->init_account($ad_content["ad_accountId"]))
            {
                $result = $this->get_specific_campaign($user, $ad_content["cat_name"]);
                if(array_key_exists('error', $result))
                {
                    //this error most probably due to wrong ad_accoundId
                    $sbuject = 'Update Price Error: maybe wrong ad_accountID';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['error'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('duplicate', $result))
                {
                    //if found more than one campaign exists, then do not continue
                    $sbuject = 'Update Price Duplicate Error: duplicate campaign';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['duplicate'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('empty', $result))
                {
                    $sbuject = 'Warning: no campaign'. $ad_content['cat_name'].'-'.$ad_content['platform_id'].'-'.$ad_content['sku'];
                    $this->mail_adcontent($ad_content,$sbuject);
                }
                else
                {
                    //campaign found, return the campaign ID back
                    $campaingId = $result->id;
                    $adGroupName = $sku;

                    $result = $this->get_specific_adGroup($user, $campaingId, $adGroupName);
                    if(array_key_exists('error', $result))
                    {
                        $sbuject = 'Update Price Error: maybe wrong ad_accountID';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['error'];
                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('duplicate', $result))
                    {
                        //if found more than one adGroup exists, then do not continue
                        $sbuject = 'Duplicate Error: duplicate adGroup';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['duplicate'];

                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('empty', $result))
                    {
                        $sbuject = 'adGroup not found '.$ad_content['sku'].'-'.$ad_content['platform_id'];
                        $ad_content["error"] = $sbuject;
                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    else
                    {
                        //get the adGroup, return back the adGroup ID and continue
                        //so get all the keywords back
                        $adGroupId = $result->id;
                        $result = $this->get_specific_keyword($user, $adGroupId);
                        if(array_key_exists('error', $result))
                        {
                            $sbuject = 'Update Price Error';
                            $ad_content['File'] = __FILE__;
                            $ad_content['Line'] = __LINE__;
                            $ad_content['error'] = $result['error'];
                            $this->mail_adcontent($ad_content,$sbuject);
                        }
                        else
                        {
                            //good here, $result is an array with keywords ID
                            $keywordId_list = $result;
                            $result = $this->add_paramter_to_keyword($user, $adGroupId, $keywordId_list, $ad_content);
                            if(array_key_exists('error', $result))
                            {
                                $sbuject = $ad_content['sku'].' - '.$ad_content['platform_id'].' Price Update Error';
                                $ad_content['File'] = __FILE__;
                                $ad_content['Line'] = __LINE__;
                                $ad_content['error'] = $result['error'];
                                $this->mail_adcontent($ad_content,$sbuject);
                            }
                            else
                            {
                                $sbuject = $ad_content['sku'].' - '.$ad_content['platform_id']." Price Update Successfully";
                                $this->mail_adcontent($ad_content,$sbuject);
                            }
                        }
                    }
                }
            }
            else
            {
                $subject = "Account no Exists.";
                $ad_content['error'] = $subject;
                $this->mail_adcontent($ad_content,$sbuject);
            }
        }

        if(isset($ad_content['error']))
        {
            $this->api_request_result_update($sku, $platform_id, 0, $ad_content['error']);
        }
        else
        {
            $this->api_request_result_update($sku, $platform_id, 1, "");
        }
    }

    function process_data($sku, $platform_id, $test = 0)
    {   //$mail_content = "Error: adwords_services.php LINE:".__LINE__."/r/n";
        //echo wordwrap("chara ctersc harac ter scharac ter scharac terscharac terscha ract er sc ha racter schar a ct ersc  acte rschar acters cha racters", 30, "<br>");
        if($platform_biz_var_obj = $this->get_platform_biz_var_service()->get(array("selling_platform_id"=>$platform_id)))
        {
            $language_id = $platform_biz_var_obj->get_language_id();
            $country_id = $platform_biz_var_obj->get_platform_country_id();

            if($prod_obj = $this->get_product_service()->get_pc_dao()->get(array("prod_sku"=>$sku, "lang_id"=>$language_id)))
            {
                $prod_name = $prod_obj->get_prod_name();
            }
            elseif($prod_obj = $this->get_product_service()->get_pc_dao()->get(array("prod_sku"=>$sku, "lang_id"=>"en")))
            {
                $prod_name = $prod_obj->get_prod_name();
            }
            else
            {
                $prod_name = "valuebasket";
            }


            //keyword has 80 characters limitation.. no more than 10 words
            //in order to use product name as keyword, split it by space and reduce the word number to meet the limitation.
            $keywords = $this->get_product_service()->get_pk_dao()->get_list(array("sku"=>$sku, "lang_id"=>$language_id));
            $keyword_arr = array();
            $prod_name_temp_list = explode(' ', $prod_name);
            $keyword_prod_name = $prod_name;


            if(strlen($keyword_prod_name) > 80 )
            {
                for($i = count($prod_name_temp_list); $i > 0; $i--)
                {
                    $keyword_prod_name = implode(' ', array_slice($prod_name_temp_list, 1, ($i-1)));
                    if(strlen($keyword_prod_name) > 80)
                    {
                        continue;
                    }
                    else
                    {
                        break;
                    }
                }
            }

            $keyword_arr[] = $keyword_prod_name;

            if($keywords)
            {
                foreach ($keywords as $k=>$v)
                {
                    if(strlen($v->get_keyword()) > 80)
                    {
                        continue;
                    }
                    else
                    {
                        $keyword_arr[] = $v->get_keyword();
                    }
                }
            }

            $purified_keyword = array();


            foreach($keyword_arr as $keyword_temp)
            {
                if(empty($keyword_temp)) continue;

                $keyword = preg_replace('/[^a-zA-Z0-9_-\s\#\$\&\_\-\[\]\+\.\/\:]/', "", $keyword_temp);
                //get the first 10 words
                $keyword = implode(' ', array_slice(explode(' ', $keyword), 0, 10));
                $purified_keyword[] = $keyword;
            }

            $price_obj = $this->get_price_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
            $price = number_format(floor($price_obj->get_price()), 0, '', ',');

            $from_char = array(' ', '.');
            $to_char = array('-','-');

            $prod_name = str_replace($from_char, $to_char, $prod_name);






            $product_obj = $this->get_product_service()->get_dao()->get(array("sku"=>$sku));
            $category_obj = $this->get_product_service()->get_cat_dao()->get(array("id"=>$product_obj->get_cat_id()));
            $cat_name = $category_obj->get_name();




            switch($language_id)
            {
                case 'fr':  $A_description_1 = "{KeyWord:$cat_name} chez ValueBasket";
                            $A_description_2 = "Garantie 3 Ans. Livraison Gratuite!";

                            $B_description_1 = "{KeyWord:$cat_name} chez ValueBasket";
                            $B_description_2 = "Meilleurs Prix. Achetez Maintenant!";
                            break;

                case 'it':  $A_description_1 = "{KeyWord:$cat_name} su ValueBasket";
                            $A_description_2 = "Garanzia 3 Anni e Spedizione Gratis";

                            $B_description_1 = "{KeyWord:$cat_name} su ValueBasket";
                            $B_description_2 = "Il Prezzo più Basso. Compra Ora!";
                            break;

                case 'es':  $A_description_1 = "{KeyWord:$cat_name} con ValueBasket";
                            $A_description_2 = "Envío gratuito. 3 años de garantía.";

                            $B_description_1 = "{KeyWord:$cat_name} con ValueBasket";
                            $B_description_2 = "El mejor precio. ¡Compra ahora!";
                            break;

                case 'ru':  $A_description_1 = "Самая низкая цена! Успейте купить";
                            $A_description_2 = "Бесплатная экпресс-доставка в РФ";

                            $B_description_1 = "Спецпредложение. Доставка бесплатно";
                            $B_description_2 = "Успейте купить по выгодной цене!";
                            break;

                default :  $A_description_1 = "{KeyWord:$cat_name} from ValueBasket";
                           $A_description_2 = "3-Year Warranty and Free Shipping!";

                           $B_description_1 = "{KeyWord:$cat_name} from ValueBasket";
                           $B_description_2 = "Best Price in Town. Shop Today!";
                           break;
            }

            //ad text description content -- platform id dependent

            $platform_id_dependent_ad_text = array('WEBPL');

            if(in_array($platform_id, $platform_id_dependent_ad_text))
            {
                switch($platform_id)
                {
                    case "WEBPL":   $A_description_1 = "Kup za najlepszą cenę!";
                                    $A_description_2 = "Bezpłatna, szybka dostawa do Polski";

                                    $B_description_1 = "Specjalna oferta. Najlepsza cena!";
                                    $B_description_2 = "Bezpłatna 5-7 dn. dostawa do Polski";
                                    break;
                }
            }

            if(strtoupper($country_id) == "PH")
            {
                $A_description_2 = "In Stock Now and Free Shipping";
            }

            $A_description_1 = mb_substr($A_description_1, 0, 45, 'UTF-8');
            $A_description_2 = mb_substr($A_description_2, 0, 35, 'UTF-8');

            $B_description_1 = mb_substr($B_description_1, 0, 45, 'UTF-8');
            $B_description_2 = mb_substr($B_description_2, 0, 35, 'UTF-8');

            $description_info = array();

            $description_info[] = array("line_1"=>$A_description_1, "line_2"=>$A_description_2);
            $description_info[] = array("line_1"=>$B_description_1, "line_2"=>$B_description_2);

            //$currency_sign = $this->currency_service->get_sign($platform_id);



            $eur_sign_country_list = array('IT', 'MT', 'FI', 'IE', 'BE', 'FR', 'ES');
            $pound_sign_country_list = array('GB');
            $dollar_sign_country_list = array("SG", "AU", "NZ");
            $CHF_sign_country_list = array('CH');
            $philippine_sign_country_list = array("PH");
            $malaysiz_sign_country_list = array("MY");
            $pl_sign_country_list = array('PL');
            $ru_sign_country_list = array('RU');


            if(in_array($country_id, $eur_sign_country_list))
            {
                $currency_sign = '€';
            }
            elseif(in_array($country_id, $pound_sign_country_list))
            {
                $currency_sign = '£';
            }
            elseif(in_array($country_id, $dollar_sign_country_list))
            {
                $currency_sign = '$';
            }
            elseif(in_array($country_id, $CHF_sign_country_list))
            {
                $currency_sign = 'CHF';
            }
            elseif(in_array($country_id, $philippine_sign_country_list))
            {
                $currency_sign = '₱';
            }
            elseif(in_array($country_id, $malaysiz_sign_country_list))
            {
                $currency_sign = 'RM';
            }
            elseif(in_array($country_id, $ru_sign_country_list))
            {
                $currency_sign = 'p';
            }
            elseif(in_array($country_id, $pl_sign_country_list))
            {
                $currency_sign = 'zl';
            }
            else
            {
                //default currency sign
                $currency_sign = '$';
            }

            $customized_display_url = array('RU', 'PL');
            if(in_array($country_id, $customized_display_url))
            {
                $display_url = "ValueBasket.".strtolower($country_id);
            }
            else
            {
                $display_url = Country_selection::rewrite_domain_by_country("www.valuebasket.com", $country_id);
            }

            $destination_url = "http://" . $display_url . "/".$language_id."_".$country_id."/".$prod_name."/mainproduct/view/".$sku."?AF=GOO".strtoupper($country_id);


            //currency sign position
            if(in_array($country_id, array('FR','ES', 'RU', 'PL')))
            {
                $headline = $cat_name. " {param1:". $price."}".$currency_sign;

                if($country_id == "RU" || $country_id =="PL")
                {
                    $headline .=".";
                }

            }
            else
            {
                $headline =  $cat_name. ' '.$currency_sign."{param1:". $price."}" ;
            }

            $headline = mb_substr($headline, 0, 37, 'UTF-8');

            if($result = $this->get_accountId_from_country_id($country_id))
            {
                $ad_accountId = $result;
            }
            else
            {
                $ad_accountId = "";
            }


            $result = array("headline"=>$headline,
            "description_info"=>$description_info,
            "display_url"=>$display_url,
            "destination_url"=>$destination_url,
            "cat_name"=>$cat_name,
            "keyword"=>$purified_keyword,
            "ad_accountId"=>$ad_accountId,
            "sku"=>$sku,
            "platform_id"=>$platform_id,
            "currency"=>$currency_sign,
            "price"=>$price
            );

            if($test)
            {
                var_dump($result);die();
            }
            return $result;
        }
        else
        {
            return array();
        }


    }

    public function get_accountId_from_country_id($country_id)
    {
        if($this->context_config_service->value_of('is_dev_site'))
        {
            if($this->debug)
            {
                // return "493-907-8910";
                // return "212-603-9902";
            }
            return false;
        }
//493-907-8910 is a testing account
/*
        $account_list = array(
            "GB" => "493-907-8910"
            //"FI" => "960-837-9622"
        );
*/

        $account_list = array(
        "BE" => "423-123-0557",
        "AU" => "212-603-9902",
        "ES" => "361-241-0604",
        "FR" => "316-460-3467",
        "IT" => "899-782-9704",
        "GB" => "220-522-9085",
        "CH" => "556-933-8151",
        "FI" => "960-837-9622",
        "MT" => "933-307-6722",
        "IE" => "766-479-7671",
        "PT" => "229-179-7402",
        "NZ" => "182-353-3787",
        "MY" => "492-329-4157",
        "PH" => "952-771-4151",
        "SG" => "383-339-9953",
        "RU" => "339-560-2926",
        "PL" => "966-202-1553"
        );

        if(array_key_exists($country_id, $account_list))
        {
            return $account_list[$country_id];
        }
        else
        {
            return false;
        }
    }

    public function is_valid_ad_content($ad_content = array())
    {
        $result = TRUE;
        if(empty($ad_content))
        {
            $result = false;
        }
        else
        {
            foreach($ad_content as $val)
            {
                if(empty($val))
                {
                    $result = false;
                }
            }
        }
        return $result;
    }

    public function update_adGroup_status_by_stock_status($sku = "", $platform_id ="", $status = "")
    {
        if($prod_obj = $this->get_product_dao()->get(array("sku"=>$sku)))
        {
            if($platform_id_list = $this->get_platform_biz_var_service()->get_pricing_tool_platform_list($sku, "WEBSITE"))
            {
                foreach($platform_id_list as $platform_obj)
                {
                    if($adwords_data_obj = $this->get_adwords_data_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_obj->get_selling_platform_id())))
                    {
                        $website_status =  $prod_obj->get_website_status(); //value: I, O, A, P
                        $adwords_status = $adwords_data_obj->get_status(); // value: 0, 1
                        if($website_status == "I")
                        {
                            $website_status = 1;
                            //if sku out of stock, then paused all adGroup; but if it's in stock, then enable all adGroup but pause
                            //those explict selected adGroup.

                            if($platform_id != $platform_obj->get_selling_platform_id())
                            {
                                continue;
                            }
                            else
                            {
                                if(!$status)
                                {
                                    $website_status = 0;
                                }
                            }
                        }
                        else
                        {
                            $website_status = 0;
                        }

                        //if($adwords_status != $website_status)
                        //{
                            if($website_status == 0)
                            {
                                $status = "PAUSED";
                            }
                            else
                            {
                                $status = "ENABLED";
                            }

                            $this->pause_or_resume_adGroup($sku, $platform_obj->get_selling_platform_id(), $status);
                        //}
                    }
                }
            }
        }
    }

    public function create_adGroup_by_platform_list($google_adwords_target_platform_list = "", $sku = "")
    {
        //convert to array;
        if(!is_array($google_adwords_target_platform_list))
        {
            $temp_array = array();
            $temp_array[] = $google_adwords_target_platform_list;
            $google_adwords_target_platform_list = $temp_array;
        }

        if(empty($google_adwords_target_platform_list) || empty($sku))
        {
            return FALSE;
        }
        else
        {
            foreach($google_adwords_target_platform_list as $key => $platform_id)
            {
                if($adwords_obj = $this->get_adwords_data_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id)))
                {
                    continue;
                }
                else
                {
                    //start_point is a function to create adGroup, dont be confuse by create_adGroup function
                    $this->start_point_v2($sku, $platform_id);
                }
            }
        }
    }

    public function update_adGroup_keyword_price_paramter($sku = "", $platform_id = "", $new_price = "")
    {
        if(!empty($sku) && !empty($new_price) && $new_price > 0)
        {
            if($adwords_data_obj = $this->get_adwords_data_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id)))
            {
                $this->update_ad_price($sku, $platform_id);
            }
        }
    }



    public function create_adwords_data($sku = "", $platform_id = "")
    {
        if(empty($platform_id) || empty($sku))
            exit();

        $new_obj = $this->get_adwords_data_dao()->get();
        $new_obj->set_sku($sku);
        $new_obj->set_platform_id($platform_id);
        $new_obj->set_status(1);
        $this->get_adwords_data_dao()->insert($new_obj);
    }


    function delete_adwords_data($ad_content = array())
    {
        if(is_array($ad_content) && isset($ad_content['sku']) && isset($ad_content['platform_id']))
        {
            if($adwords_data_obj = $this->get_adwords_data_dao()->get(array("sku"=>$ad_content['sku'], "platform_id"=>$ad_content['platform_id'])))
            {
                $this->get_adwords_data_dao()->delete($adwords_data_obj);
            }
        }
    }


    function get_platform_biz_var_service()
    {
        return $this->platform_biz_var_service;
    }

    function get_product_service()
    {
        return $this->product_service;
    }

    function get_price_dao()
    {
        return $this->price_dao;
    }

    function get_adwords_data_dao()
    {
        return $this->adwords_data_dao;
    }

    function get_product_dao()
    {
        return $this->product_dao;
    }

    /*
    public function cache_api_exec()
    {
        $where = $option = array();
        $where["api"] = "AD";
        $where["exec"] = 0;
        $option["limit"] = -1;

        if($config_vo = $this->config_dao->get(array("variable"=>"adwords_api_at_job")))
        {
            $config_vo->set_value(0);
            $this->config_dao->update($config_vo);
        }

        if($cache_api_list = $this->cache_api_request_dao->get_list($where, $option))
        {
            foreach($cache_api_list as $api_obj)
            {
                $api_obj->set_exec(1);
                $this->cache_api_request_dao->update($api_obj);
            }


            foreach($cache_api_list as $api_obj)
            {
                $sku = $api_obj->get_sku();
                $platform_id = $api_obj->get_platform_id();
                $stock_update = $api_obj->get_stock_update();
                $price_update = $api_obj->get_price_update();
                $is_item_create = $api_obj->get_item_create();


                $platform_biz_obj = $this->get_platform_biz_var_service()->get(array("selling_platform_id"=>$platform_id));
                $country_id = $platform_biz_obj->get_platform_country_id();

                if($account_id = $this->get_accountId_from_country_id($country_id))
                {
                    if($is_item_create == "Y")
                    {
                        $this->start_point($sku, $platform_id);

                        if($stock_update == "PAUSED")
                        {
                            $this->pause_or_resume_adGroup($sku, $platform_id, $stock_update);
                        }
                    }
                    elseif(($stock_update != "N") && in_array($stock_update, array("PAUSED", "ENABLED")))
                    {
                        $this->pause_or_resume_adGroup($sku, $platform_id, $stock_update);
                        if($price_update != "N" && $stock_update == "ENABLED")
                        {
                            $this->update_ad_price($sku, $platform_id, $stock_update);
                        }
                    }
                    elseif($price_update != "N")
                    {
                        $this->update_ad_price($sku, $platform_id, $stock_update);
                    }
                }
            }
        }
    }
    */

    function api_request_result_update($sku, $platform_id, $status = 1, $comment="")
    {
        if($comment) $comment = htmlspecialchars($comment, ENT_QUOTES);

        if($adwords_data_obj = $this->get_adwords_data_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id)))
        {
            $adwords_data_obj->set_api_request_result($status);
            if($status == 1)
            {
                $adwords_data_obj->set_comment($comment);
            }
            else
            {
                $old_comment = $adwords_data_obj->get_comment();
                $new_comment = $old_comment?($old_comment.';'.$comment):$comment;
                $adwords_data_obj->set_comment($new_comment);
            }
            $this->get_adwords_data_dao()->update($adwords_data_obj);
        }
    }






    public function add_campaign_v2($user, $ad_Content="")
    {
        try{
            $budgetService = $user->GetService('BudgetService', ADWORDS_VERSION);
            // Create the shared budget (required).
            $budget = new Budget();
            $budget->name = 'Interplanetary Cruise Budget #' . uniqid();
            $budget->period = 'DAILY';
            $budget->amount = new Money(50000000);
            $budget->deliveryMethod = 'STANDARD';

            $operations = array();

            // Create operation.
            $operation = new BudgetOperation();
            $operation->operand = $budget;
            $operation->operator = 'ADD';
            $operations[] = $operation;

            // Make the mutate request.
            $result = $budgetService->mutate($operations);
            $budget = $result->value[0];

            // Get the CampaignService, which loads the required classes.
            $campaignService = $user->GetService('CampaignService', ADWORDS_VERSION);

            $numCampaigns = 2;
            $operations = array();

            $campaign = new Campaign();
            $campaign->name = $ad_Content['cat_name'];
            $campaign->advertisingChannelType = 'SEARCH';

            // Set shared budget (required).
            $campaign->budget = new Budget();
            $campaign->budget->budgetId = $budget->budgetId;

            // Set bidding strategy (required).
            $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
            $biddingStrategyConfiguration->biddingStrategyType = 'MANUAL_CPC';

            // You can optionally provide a bidding scheme in place of the type.
            $biddingScheme = new ManualCpcBiddingScheme();
            $biddingScheme->enhancedCpcEnabled = FALSE;
            $biddingStrategyConfiguration->biddingScheme = $biddingScheme;

            $campaign->biddingStrategyConfiguration = $biddingStrategyConfiguration;

            /*
            // Set keyword matching setting (required).
            // http://googleadsdeveloper.blogspot.sg/2014/08/adwords-close-variant-matching-for-all.html /
            $keywordMatchSetting = new KeywordMatchSetting();
            $keywordMatchSetting->optIn = TRUE;
            $campaign->settings[] = $keywordMatchSetting;


            // Set network targeting (optional).
            $networkSetting = new NetworkSetting();
            $networkSetting->targetGoogleSearch = TRUE;
            $networkSetting->targetSearchNetwork = TRUE;
            $networkSetting->targetContentNetwork = TRUE;
            $campaign->networkSetting = $networkSetting;

            // Set additional settings (optional).
            $campaign->status = 'PAUSED';
            $campaign->startDate = date('Ymd', strtotime('+1 day'));
            $campaign->endDate = date('Ymd', strtotime('+1 month'));
            $campaign->adServingOptimizationStatus = 'ROTATE';

            // Set frequency cap (optional).
            $frequencyCap = new FrequencyCap();
            $frequencyCap->impressions = 5;
            $frequencyCap->timeUnit = 'DAY';
            $frequencyCap->level = 'ADGROUP';
            $campaign->frequencyCap = $frequencyCap;

            // Set advanced location targeting settings (optional).
            $geoTargetTypeSetting = new GeoTargetTypeSetting();
            $geoTargetTypeSetting->positiveGeoTargetType = 'DONT_CARE';
            $geoTargetTypeSetting->negativeGeoTargetType = 'DONT_CARE';
            $campaign->settings[] = $geoTargetTypeSetting;
            */

            // Create operation.
            $operation = new CampaignOperation();
            $operation->operand = $campaign;
            $operation->operator = 'ADD';
            $operations[] = $operation;
            $result = $campaignService->mutate($operations);

            if(count($result->value) == 1)
            {
                return $result->value[0];
            }
            else
            {
                return $result = array('error' => "campaign Create failed:". $ad_Content['cat_name']);
            }

        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }
    }

    function add_AdGroups_v2(AdWordsUser $user, $campaignId = '', $adGroupName = '')
    {
        try
        {
            // Get the service, which loads the required classes.
            $adGroupService = $user->GetService('AdGroupService', ADWORDS_VERSION);

            $numAdGroups = 2;
            $operations = array();

            // Create ad group.
            $adGroup = new AdGroup();
            $adGroup->campaignId = $campaignId;
            $adGroup->name = $adGroupName;

            // Set bids (required).
            $bid = new CpcBid();
            $bid->bid =  new Money(500000);
            $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
            $biddingStrategyConfiguration->bids[] = $bid;
            $adGroup->biddingStrategyConfiguration = $biddingStrategyConfiguration;

            // Set additional settings (optional).
            $adGroup->status = 'ENABLED';

            // Targetting restriction settings - these setting only affect serving
            // for the Display Network.
            $targetingSetting = new TargetingSetting();
            // Restricting to serve ads that match your ad group placements.
            $targetingSetting->details[] =
            new TargetingSettingDetail('PLACEMENT', FALSE);
            // Using your ad group verticals only for bidding.
            $targetingSetting->details[] =
            new TargetingSettingDetail('VERTICAL', FALSE);
            $adGroup->settings[] = $targetingSetting;

            // Create operation.
            $operation = new AdGroupOperation();
            $operation->operand = $adGroup;
            $operation->operator = 'ADD';
            $operations[] = $operation;

            // Make the mutate request.
            $result = $adGroupService->mutate($operations);

            if(count($result->value) == 1)
            {
                return $result->value[0];
            }
            else
            {
                return $result = array('error' => "adGroup Create failed");
            }

        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }
    }


    function add_textAds_v2(AdWordsUser $user, $adGroupId, $adContent = '')
    {
        try
        {
            // Get the service, which loads the required classes.
            $adGroupAdService = $user->GetService('AdGroupAdService', ADWORDS_VERSION);

            $numAds = 2;
            $operations = array();
            for ($i = 0; $i < $numAds; $i++)
            {
                // Create text ad.
                $textAd = new TextAd();
                $textAd->headline = $adContent['headline'];
                $textAd->description1 = $adContent["description_info"][$i]["line_1"];
                $textAd->description2 = $adContent["description_info"][$i]["line_2"];
                $textAd->displayUrl = $adContent["display_url"];
                $textAd->url = $adContent["destination_url"];

                // Create ad group ad.
                $adGroupAd = new AdGroupAd();
                $adGroupAd->adGroupId = $adGroupId;
                $adGroupAd->ad = $textAd;

                // Set additional settings (optional).
                $adGroupAd->status = 'ENABLED';

                // Create operation.
                $operation = new AdGroupAdOperation();
                $operation->operand = $adGroupAd;
                $operation->operator = 'ADD';
                $operations[] = $operation;
            }

            // Make the mutate request.
            $result = $adGroupAdService->mutate($operations);

            if(count($result->value) == 2)
            {
                return $result->value;
            }
            else
            {
                return $result = array('error' => "adText Create failed");
            }

        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }
    }

    function add_keywords_v2(AdWordsUser $user, $adGroupId, $ad_content="")
    {
        try
        {
            // Get the service, which loads the required classes.
            $adGroupCriterionService =
            $user->GetService('AdGroupCriterionService', ADWORDS_VERSION);

            $numKeywords = min(count($ad_content['keyword']), 5000);
            $matchTypeList = array('EXACT', 'PHRASE', 'BROAD');
            $operations = array();
            for ($i = 0; $i < $numKeywords; $i++)
            {
                foreach($matchTypeList as $matchType)
                {
                    // Create keyword criterion.
                    $keyword = new Keyword();

                    if($matchType == "BROAD")
                    {
                        //change the Broad match to Broad modifier match
                        $temp_arr = explode(" ", $ad_content["keyword"][$i]);
                        $new_keyword = "+".implode(" +", $temp_arr);
                        $ad_content["keyword"][$i] = $new_keyword;

                        if(strlen($new_keyword) > 80)
                        {
                            continue;
                        }
                    }

                    $keyword->text = $ad_content["keyword"][$i];
                    $keyword->matchType = $matchType;

                    // Create biddable ad group criterion.
                    $adGroupCriterion = new BiddableAdGroupCriterion();
                    $adGroupCriterion->adGroupId = $adGroupId;
                    $adGroupCriterion->criterion = $keyword;

                    // Set additional settings (optional).
                    $adGroupCriterion->userStatus = 'ENABLED';

                    // Set bids (optional).
                    $bid = new CpcBid();
                    $bid->bid =  new Money(1000000);
                    $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
                    $biddingStrategyConfiguration->bids[] = $bid;
                    $adGroupCriterion->biddingStrategyConfiguration = $biddingStrategyConfiguration;

                    $adGroupCriteria[] = $adGroupCriterion;

                    // Create operation.
                    $operation = new AdGroupCriterionOperation();
                    $operation->operand = $adGroupCriterion;
                    $operation->operator = 'ADD';
                    $operations[] = $operation;
                }
            }

            // Make the mutate request.
            $result = $adGroupCriterionService->mutate($operations);

            if(count($result->value) > 0)
            {
                return $result->value[0];
            }
            else
            {
                return $result = array('error' => "Keyword Create failed");
            }
        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }
    }

    public function start_point_v2($sku, $platform_id)
    {
        $ad_content = $this->process_data($sku, $platform_id);
        if(!$this->is_valid_ad_content($ad_content))
        {
            $sbuject = 'invalid ad content '.$sku .' - '.$platform_id;
            $ad_content['error'] = $sbuject;
            $this->mail_adcontent($ad_content,$sbuject);
        }
        else
        {
            //connect to account using account_id first
            if($user = $this->init_account($ad_content["ad_accountId"]))
            {

                $result = $this->get_campaign_by_name_v2($user, $ad_content["cat_name"]);

                //if campaign no exists, then create it
                if(array_key_exists('empty', $result))
                {
                    $result = $this->add_campaign_v2($user, $ad_content);
                }

                if(array_key_exists('error', $result))
                {
                    //this error most probably due to wrong ad_accoundId
                    $sbuject = 'unknown Error: maybe wrong ad_accountID';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['error'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('duplicate', $result))
                {
                    //if found more than one campaign exists, then do not continue
                    $sbuject = 'Duplicate Error: duplicate campaign';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['duplicate'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                //elseif(array_key_exists('empty', $result))
                //{
                //  need to create a new campaign with category name as campaign name
                //  $ad_content['error'] = "create adGroup warning, not campaign found";
                //}
                else
                {
                    //all this good here, then
                    //return the campaign ID and check the adgroup : sku if already there or not
                    $campaingId = $result->id;
                    $adGroupName = $sku;

                    $result = $this->get_adGroups_by_name_v2($user, $campaingId, $adGroupName);

                    if(array_key_exists('error', $result))
                    {
                        $sbuject = 'unknown Error: maybe wrong ad_accountID';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['error'];

                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('duplicate', $result))
                    {
                        //if found more than one adGroup exists, then do not continue
                        $sbuject = 'Duplicate Error: duplicate adGroup';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['duplicate'];

                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('empty', $result))
                    {
                        //as Expected for a new create product, go ahead and create an adGroup for it.
                        //adGroupName is like 10280-AA-NA_Canon EOS 600D with 18-55mm f/3.5-5.6 IS II Lens Kit
                        $adGroupName = $sku."_".$ad_content['keyword'][0];
                        $result = $this->add_AdGroups_v2($user, $campaingId, $adGroupName);

                        if(array_key_exists('error',$result))
                        {
                            $sbuject = 'unknown Error: adGroup create failed';
                            $ad_content['File'] = __FILE__;
                            $ad_content['Line'] = __LINE__;
                            $ad_content['error'] = $result['error'];

                            $this->mail_adcontent($ad_content,$sbuject);
                        }
                        else
                        {
                            //if success, then need to create adText and keywords
                            $sbuject = 'adGroup created successfully';
                            $this->mail_adcontent($ad_content,$sbuject);

                            $adGroupId = $result->id;
                            $result = $this->add_textAds_v2($user, $adGroupId, $ad_content);

                            if(array_key_exists('error', $result))
                            {
                                //if error, adText creation fail, so delete the adGroup as well
                                $sbuject = 'unknown Error: adText create failed';
                                $ad_content['File'] = __FILE__;
                                $ad_content['Line'] = __LINE__;
                                $ad_content['error'] = $result['error'];

                                $this->mail_adcontent($ad_content,$sbuject);

                                $delete_result = $this->deleteAdGroup($user, $adGroupId, $ad_content);

                                if(array_key_exists('error', $delete_result))
                                {
                                    $sbuject = 'unknown Error: adText delete failed';
                                    $ad_content['error'] = $delete_result['error'];

                                    $this->mail_adcontent($ad_content,$sbuject);
                                }
                                else
                                {
                                    $sbuject = 'adText delete successfully';
                                    $this->mail_adcontent($ad_content,$sbuject);
                                }
                            }
                            else
                            {

                                //adText create successfully, then create keywords
                                $sbuject = 'adText created successfully';
                                $this->mail_adcontent($ad_content,$sbuject);
                                $keyword_result = $this->add_keywords_v2($user, $adGroupId, $ad_content);

                                if(array_key_exists('error', $keyword_result))
                                {
                                    $sbuject = 'Error: keyword create failed';
                                    $ad_content['error'] = $keyword_result['error'];

                                    $delete_result = $this->deleteAdGroup($user, $adGroupId, $ad_content);
                                    $this->mail_adcontent($ad_content,$sbuject);
                                }
                                else
                                {
                                    $sbuject = $ad_content['sku']. ' - '. $ad_content['platform_id'].'ad created successfully';
                                    //$this->create_adwords_data($ad_content['sku'], $ad_content['platform_id']);
                                    $this->mail_adcontent($ad_content,$sbuject);
                                }
                            }

                        }
                    }
                    else
                    {
                        //mean this sku adGroup already exists here, so what to do next?? delete or what.
                        $subject = "adGroup ".$ad_content["sku"].'-'.$ad_content["platform_id"].' already exists';
                        $ad_content['error'] = "adGroup already exists";
                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                }
            }
        }

        if(isset($ad_content['error']))
        {
            $this->api_request_result_update($sku, $platform_id, 0, $ad_content['error']);
        }
        else
        {
            $this->api_request_result_update($sku, $platform_id, 1, "");
        }
    }

public function pause_or_resume_adGroup_v2($sku, $platform_id, $stock_update)
{
    $ad_content = $this->process_data($sku, $platform_id);

    if(!$result = $this->is_valid_ad_content($ad_content))
    {
        $sbuject = 'pause ad: invalid ad content';
        $ad_content['error'] = "invalid ad content";
        $this->mail_adcontent($ad_content,$sbuject);
    }
    else
    {
        if($user = $this->init_account($ad_content["ad_accountId"]))
        {
            $result = $this->get_campaign_by_name_v2($user, $ad_content["cat_name"]);
            if(array_key_exists('error', $result))
            {
                $sbuject = 'Pause ad Error';
                $ad_content['File'] = __FILE__;
                $ad_content['Line'] = __LINE__;
                $ad_content['error'] = $result['error'];

                $this->mail_adcontent($ad_content,$sbuject);
            }
            elseif(array_key_exists('duplicate', $result))
            {
                $sbuject = 'Update campaign Duplicate Error: duplicate campaign';
                $ad_content['File'] = __FILE__;
                $ad_content['Line'] = __LINE__;
                $ad_content['error'] = $result['duplicate'];

                $this->mail_adcontent($ad_content,$sbuject);
            }
            elseif(array_key_exists('empty', $result))
            {
                $sbuject = 'pause ad warning: campaign does not exist';
                $ad_content['error'] = $sbuject;
                $this->mail_adcontent($ad_content,$sbuject);
            }
            else
            {
                $campaingId = $result->id;
                $adGroupName = $sku;
                $result = $this->get_adGroups_by_name_v2($user, $campaingId, $adGroupName);
                if(array_key_exists('error', $result))
                {
                    $sbuject = 'Pause Ad Error';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['error'];


                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('duplicate', $result))
                {
                    $sbuject = 'Pause Ad Error: duplicate adGroup';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['duplicate'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('empty', $result))
                {
                    //if not adGroup found, then do nothing
                    $ad_content['error'] = "pause ad warning: adGroup no Exists";
                }
                else
                {
                    $adGroupId = $result->id;
                    $result = $this->update_adGroup_handler_v2($user, $adGroupId, $stock_update);
                    if(array_key_exists('error',$result))
                    {
                        $sbuject = 'Pause Ad Error';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['error'];

                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    else
                    {
                        $ad_content['success'] = $result['success'];
                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                }
            }
        }
    }

    if(isset($ad_content['error']))
    {
        $this->api_request_result_update($sku, $platform_id, 0, $ad_content['error']);
    }
    else
    {
        $this->api_request_result_update($sku, $platform_id, 1, "");
    }
}


    public function cache_api_exec()
    {
        $where = $option = array();
        $where["api"] = "AD";
        $where["exec"] = 0;
        $option["limit"] = -1;

        $debug = $this->debug = false;
        if(strpos($_SERVER["HTTP_HOST"], "dev") != FALSE)
        {
            $debug = $this->debug = TRUE;
        }

        if($config_vo = $this->config_dao->get(array("variable"=>"adwords_api_at_job")))
        {
            $config_vo->set_value(0);
            $this->config_dao->update($config_vo);
        }

        if($cache_api_list = $this->cache_api_request_dao->get_list($where, $option))
        {
            foreach($cache_api_list as $api_obj)
            {
                $api_obj->set_exec(1);
                if(!$debug)
                {
                    $this->cache_api_request_dao->update($api_obj);
                }
            }


            foreach($cache_api_list as $api_obj)
            {
                $sku = $api_obj->get_sku();
                $platform_id = $api_obj->get_platform_id();
                $stock_update = $api_obj->get_stock_update();
                $price_update = $api_obj->get_price_update();
                $is_item_create = $api_obj->get_item_create();


                $platform_biz_obj = $this->get_platform_biz_var_service()->get(array("selling_platform_id"=>$platform_id));
                $country_id = $platform_biz_obj->get_platform_country_id();

                if($account_id = $this->get_accountId_from_country_id($country_id))
                {
                    if($is_item_create == "Y")
                    {
                        $this->start_point_v2($sku, $platform_id);
                        if($stock_update == "PAUSED")
                        {
                            $this->pause_or_resume_adGroup_v2($sku, $platform_id, $stock_update);
                        }
                    }
                    elseif(($stock_update != "N") && in_array($stock_update, array("PAUSED", "ENABLED")))
                    {
                        $this->pause_or_resume_adGroup_v2($sku, $platform_id, $stock_update);
                        if($price_update != "N" && $stock_update == "ENABLED")
                        {
                            $this->update_ad_price_v2($sku, $platform_id, $stock_update);
                        }
                    }
                    elseif($price_update != "N")
                    {
                        $this->update_ad_price_v2($sku, $platform_id, $stock_update);
                    }
                }
            }
        }
    }


    function get_campaign_by_name_v2($user, $campaing_name="")
    {
        try
        {
            $campaignService = $user->GetService('CampaignService', ADWORDS_VERSION);
            $selector = new Selector();
            $selector->fields = array('Id', 'Name');
            $selector->predicates[] = new Predicate('Name','EQUALS', $campaing_name);
            $selector->predicates[] =
                new Predicate('Status', 'IN', array('ENABLED','PAUSED'));
            $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
            $result = array();

            do{
                $page = $campaignService->get($selector);
                if($page->totalNumEntries >1)
                {
                    return array("duplicate"=>"more than one Campaign $campaing_name Found");
                }
                else
                {
                    if (isset($page->entries)) {
                        return $page->entries[0];
                    } else {
                        return array("empty"=>"No Campaign Result Found");
                    }
                }
                $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
            }while ($page->totalNumEntries > $selector->paging->startIndex);
        }catch(Exception $e){
            return $result = array('error' => "An error has occurred: ". $e->getMessage());
        }
    }


    function get_adGroups_by_name_v2(AdWordsUser $user, $campaignId, $adGroupName)
    {
        try{
            // Get the service, which loads the required classes.
            $adGroupService = $user->GetService('AdGroupService', ADWORDS_VERSION);

            // Create selector.
            $selector = new Selector();
            $selector->fields = array('Id', 'Name');
            $selector->ordering[] = new OrderBy('Name', 'ASCENDING');

            // Create predicates.
            $selector->predicates[] =
            new Predicate('CampaignId', 'EQUALS', $campaignId);

            $selector->predicates[] =
            new Predicate('Name','STARTS_WITH', $adGroupName);

            $selector->predicates[] =
                new Predicate('Status', 'IN', array('ENABLED','PAUSED'));

            // Create paging controls.
            $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);

            // Make the get request.
            $page = $adGroupService->get($selector);

            // Display results.
            if($page->totalNumEntries >1)
            {
                return array("duplicate"=>"more than one adGroup $adGroupName Found in CampaingID: ".$campaingId);
            }
            else
            {
                if (isset($page->entries))
                {
                    return $page->entries[0];
                } else {
                    return array("empty"=>"No adGroup Result Found in campaignID: ".$campaingId);
                }
            }
        }catch(Exception $e){
            return $result = array('error' => "An error has occurred: ". $e->getMessage());
        }
    }


    function update_adGroup_handler_v2($user, $adGroupId, $status='PAUSED')
    {
        try{
            $adGroupService = $user->GetService('AdGroupService', ADWORDS_VERSION);
            $adGroup = new AdGroup();
            $adGroup->id = $adGroupId;

            $adGroup->status = $status;
            $operation = new AdGroupOperation();
            $operation->operand = $adGroup;
            $operation->operator = 'SET';

            $operations = array($operation);
            $result = $adGroupService->mutate($operations);

            if($adGroup = $result->value[0])
            {
                return array('success'=>$adGroup->name." $status successfully");
            }
            else
            {
                return $result = array('error' => "$status adGroup Fail");
            }
        }catch(Exception $e){
            return $result= array('error' => "$status adGroup Error: ". $e->getMessage());
        }
    }

    public function update_ad_price_v2($sku, $platform_id)
    {
         //need first to check if this adGroup -- sku exists or NOT
        $ad_content = $this->process_data($sku, $platform_id);
        if(!$result = $this->is_valid_ad_content($ad_content))
        {
            $sbuject = 'Ad price update: invalid ad content ';
            $ad_content["error"] = $sbuject;
            $this->mail_adcontent($ad_content,$sbuject);
        }
        else
        {
            if($user = $this->init_account($ad_content["ad_accountId"]))
            {
                $result = $this->get_campaign_by_name_v2($user, $ad_content["cat_name"]);
                if(array_key_exists('error', $result))
                {
                    //this error most probably due to wrong ad_accoundId
                    $sbuject = 'Update Price Error: maybe wrong ad_accountID';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['error'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('duplicate', $result))
                {
                    //if found more than one campaign exists, then do not continue
                    $sbuject = 'Update Price Duplicate Error: duplicate campaign';
                    $ad_content['File'] = __FILE__;
                    $ad_content['Line'] = __LINE__;
                    $ad_content['error'] = $result['duplicate'];

                    $this->mail_adcontent($ad_content,$sbuject);
                }
                elseif(array_key_exists('empty', $result))
                {
                    $sbuject = 'Warning: no campaign'. $ad_content['cat_name'].'-'.$ad_content['platform_id'].'-'.$ad_content['sku'];
                    $this->mail_adcontent($ad_content,$sbuject);
                }
                else
                {
                    //campaign found, return the campaign ID back
                    $campaingId = $result->id;
                    $adGroupName = $sku;

                    $result = $this->get_adGroups_by_name_v2($user, $campaingId, $adGroupName);
                    if(array_key_exists('error', $result))
                    {
                        $sbuject = 'Update Price Error: maybe wrong ad_accountID';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['error'];
                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('duplicate', $result))
                    {
                        //if found more than one adGroup exists, then do not continue
                        $sbuject = 'Duplicate Error: duplicate adGroup';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] = $result['duplicate'];

                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    elseif(array_key_exists('empty', $result))
                    {
                        $sbuject = 'adGroup not found '.$ad_content['sku'].'-'.$ad_content['platform_id'];
                        $ad_content["error"] = $sbuject;
                        $this->mail_adcontent($ad_content,$sbuject);
                    }
                    else
                    {
                        //get the adGroup, return back the adGroup ID and continue
                        //so get all the keywords back
                        $adGroupId = $result->id;
                        $result = $this->get_specific_keyword_v2($user, $adGroupId);
                        if(array_key_exists('error', $result))
                        {
                            $sbuject = 'Update Price Error';
                            $ad_content['File'] = __FILE__;
                            $ad_content['Line'] = __LINE__;
                            $ad_content['error'] = $result['error'];
                            $this->mail_adcontent($ad_content,$sbuject);
                        }
                        else
                        {
                            //good here, $result is an array with keywords ID
                            $keywordId_list = $result;

                            $result = $this->add_paramter_to_keyword_v2($user, $adGroupId, $keywordId_list, $ad_content);
                            if(array_key_exists('error', $result))
                            {
                                $sbuject = $ad_content['sku'].' - '.$ad_content['platform_id'].' Price Update Error';
                                $ad_content['File'] = __FILE__;
                                $ad_content['Line'] = __LINE__;
                                $ad_content['error'] = $result['error'];
                                $this->mail_adcontent($ad_content,$sbuject);
                            }
                            else
                            {
                                $sbuject = $ad_content['sku'].' - '.$ad_content['platform_id']." Price Update Successfully";
                                $this->mail_adcontent($ad_content,$sbuject);
                            }
                        }
                    }
                }
            }
            else
            {
                $subject = "Account no Exists.";
                $ad_content['error'] = $subject;
                $this->mail_adcontent($ad_content,$sbuject);
            }
        }

        if(isset($ad_content['error']))
        {
            $this->api_request_result_update($sku, $platform_id, 0, $ad_content['error']);
        }
        else
        {
            $this->api_request_result_update($sku, $platform_id, 1, "");
        }
    }

    function get_specific_keyword_v2($user, $adGroupId = '', $text = false)
    {
        # set $text as true if you want the keyword text & id
        try
        {
            $adGroupCriterionService = $user->GetService('AdGroupCriterionService', ADWORDS_VERSION);
            $selector = new Selector();
            $selector->fields = array('KeywordText', 'Id');
            $selector->predicates[] = new Predicate('AdGroupId', 'EQUALS', $adGroupId);
            $selector->predicates[] =
                new Predicate('CriteriaType', 'IN', array('KEYWORD'));
            $selector->predicates[] =
                new Predicate('Status', 'IN', array('ENABLED','PAUSED'));
            //$selector->ordering[] = new OrderBy('KeywordText', 'ASCENDING');
            $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
            $result = array();
            $page = $adGroupCriterionService->get($selector);

            $result = array();
            do {
                // Make the get request.
                $page = $adGroupCriterionService->get($selector);

                // Display results.
                if (isset($page->entries))
                {
                    foreach ($page->entries as $adGroupCriterion)
                    {
                        if($text === false)
                        {
                            //return the keywords ID
                            $result[] = $adGroupCriterion->criterion->id;
                        }
                        else
                        {
                            // return keyword text with keyword_id as array key
                            $id = $adGroupCriterion->criterion->id;
                            $result[$id] = $adGroupCriterion->criterion->text;
                        }
                    }
                }
                else
                {
                    return array("empty"=>"No keyword Result Found in adGroupId: ".$adGroupId);
                }

                // Advance the paging index.
                $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
            } while ($page->totalNumEntries > $selector->paging->startIndex);
        }catch(Exception $e){
            return $result = array('error' => "An error has occurred: ". $e->getMessage());
        }
        return $result;
    }

    function add_paramter_to_keyword_v2($user, $adGroupId, $keywordId_list = array(), $ad_content = array())
    {
        try{
            $adParamService = $user->GetService('AdParamService', ADWORDS_VERSION);

            $operation = array();
            $n = 0;

            //avoid the max operation per minute, need to improve, since this cut off the keyword
            $reduced_keyword_list = array_slice($keywordId_list,0, 5000);

            $chunked_keyword_list = array_chunk($reduced_keyword_list, 2000);
            foreach($chunked_keyword_list as $small_keyword_list)
            {
                foreach($small_keyword_list as $keywordId)
                {
                    $adParamOperation = new AdParamOperation();
                    $price = $ad_content['price'];
                    //insertionText in paramIndex 1 only, as there is one param -- price only
                    $adParam = new AdParam($adGroupId, $keywordId, $price, 1);
                    $adParamOperation->operand = $adParam;
                    $adParamOperation->operator = 'SET';
                    $operations[] = $adParamOperation;
                }

                $adParams = $adParamService->mutate($operations);

                $result = array();
                foreach ($adParams as $adParam) {
                    $result[$adParam->paramIndex] = $adParam->insertionText;
                }
            }
                return $result;
        }catch(Exception $e){
            return $result= array('error' => "An error has occurred: ". $e->getMessage());
        }
    }

}

?>