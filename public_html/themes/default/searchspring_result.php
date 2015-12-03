<?php $this->load->view('header');

    $siteobj = \PUB_Controller::$siteInfo;
    $platCountryId = $siteobj->getPlatformCountryId();;
    switch (strtolower($platCountryId)) {
        case 'gb' :
            $searchspring_site_id = 'jdajtq';
            break;

        default   :
            $searchspring_site_id = '';
    }
?>


<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">

<div class="main-columns container">
  <div id="content" style="margin: 20px auto">
    <aside id="sidebar-right" class="col-md-3"></aside>

    <div class="products-block  col-lg-9 col-sm-9 col-xs-12"></div>
<!-- START V3 MOCKUP -->

<!-- Slideout Template -->
<script type="text/ss-template" slideout>
  <a href="" slideout><div class="searchspring-slideout_button"></div></a>
  <div id="searchspring-slideout_facets" class="Left"></div>
</script>

<!-- Facets Template -->
<script type="text/ss-template" target="#sidebar-right, #searchspring-slideout_facets">
  <div id="column-right" class="sidebar" ng-if="pagination.totalResults">
    <div class="panel panel-default nopadding">
      <div class="panel-body tree-menu">
        <ul class="box-category list-group accordion">
          <li class="list-group-item accordion-group">
            <p>Refine Search</p>
          </li>
          <li class="list-group-item accordion-group" ng-repeat="facet in facets">
            <a class="active list-group-item-title">{{ facet.label }}</a>
            <ul class="collapse accordion-body in" ng-class="{ 'ss-hierarchy': facet.type == 'hierarchy' }">
              <li ng-repeat="value in facet.values | limitTo:facet.overflow.limit" ng-class="{ 'ss-filtered_current': facet.type == 'hierarchy' && value.active, 'ss-filtered_link': value.history && !value.active }">
                <a href="{{ value.url }}" ng-class="{ active: value.active }" ng-if="facet.type != 'hierarchy' || !value.active">
                  {{ value.label }} <span ng-if="value.count">({{ value.count }})</span>
                </a>
                <span ng-if="facet.type == 'hierarchy' && value.active">{{ value.label }}</span>
              </li>
              <li class="show-more" ng-click="facet.overflow.toggle()" ng-if="facet.overflow.set(facet.type == 'hierarchy' ? 100 : 5).count">
                <div class="show-more">
                  Show {{ facet.overflow.remaining ? 'more' : 'less' }}
                  <span data-toggle="collapse"  data-target="#accordiondata" class="bg collapsed">
                    <i class="fa fa-angle-{{ facet.overflow.remaining ? 'down' : 'up' }}"></i>
                  </span>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div ss-merchandising="left"></div>
</script>

<!-- Main Template -->
<script type="text/ss-template" target=".products-block">
  <div class="category_title"><h3>Search Results <span ng-if="q">for '{{ q }}'</span></h3></div>

  <div ng-if="pagination.totalResults" ss-merchandising="banner"></div>
  <div ng-if="pagination.totalResults" ss-merchandising="header"></div>

  <div class="searchspring-slideout_button" slideout ng-if="pagination.totalResults && facets.length > 0">
    <span class="searchspring-slideout_button_icon"></span>
    <span class="searchspring-slideout_button_text">Filter Options</span>
  </div>

  <div class="product-filter no-shadow" style="margin:20px auto" ng-if="pagination.totalResults">
    <div class="inner clearfix">
      <div class="display">
        <div class="btn-group group-switch">
          <button data-original-title="List" type="button" id="list-view" class="btn btn-switch" data-toggle="tooltip" ng-click="setLayout('list')" ng-class="{ active: layout == 'list' }"><i class="fa fa-th-list"></i></button>
          <button data-original-title="Grid" type="button" id="grid-view" class="btn btn-switch" data-toggle="tooltip" ng-click="setLayout('grid')" ng-class="{ active: layout == 'grid' }"><i class="fa fa-th"></i></button>
        </div>
      </div>
      <div class="filter-right"></div>
      <div class="sort pull-right">
        <span for="input-sort">Sort By:</span>
        <select id="input-sort" class="form-control" ng-model="sorting.current" ng-options="option.label for option in sorting.options"></select>
      </div>
      <div class="limit pull-right">
        <span for="input-limit">Display:</span>
        <select id="input-limit" class="form-control" ng-model="pagination.perPage" ng-options="page for page in [12, 25, 50, 75, 100]"></select>
      </div>
    </div>
  </div>

  <div id="products" ng-class="{ 'product-list': layout == 'list', 'product-grid': layout == 'grid' }" ng-if="pagination.totalResults">
    <div class="row products-row"></div>
  </div>

  <div class="no-results" role="alert" ng-if="pagination.totalResults === 0">
    <span ng-if="didYouMean.query" class="ss-did-you-mean">Did you mean <a href="?search_query={{ didYouMean.query }}"><b>{{ didYouMean.query }}</b></a>?</span>
    <br>
    <h5>Sorry, No Results Were Found</h5>
    Search was unable to find any results for "{{ q }}". You may have typed your word incorrectly, or are being too specific.<br>
    Please try using a broader search phrase.
    <br><br><br>
  </div>

  <div class="pagination paging clearfix pull-right" ng-if="pagination.totalResults">
    <ul class="pagination" style="margin:0">
      <li><a href="{{ pagination.previous.url }}" ng-if="pagination.previous">&lt;&lt;</a></li>
      <li ng-repeat="page in pagination.getPages(5)" ng-class="{ active: page.active }">
        <span ng-if="page.active">{{ page.number }}</span>
        <a href="{{ page.url }}" ng-if="!page.active"> {{ page.number }}</a>
      </li>
      <li><a href="{{ pagination.next.url }}" ng-if="pagination.next">&gt;&gt;</a></li>
    </ul>
  </div>

  <div ng-if="pagination.totalResults" ss-merchandising="footer"></div>
</script>

<!-- Item Template -->
<script type="text/ss-template" target="#products .products-row">
  <div class="col-lg-3 col-sm-3 col-xs-12 product-col border" ng-repeat="item in results">
    <div class="product-block">
      <div class="image">
        <div class="product-img img">
          <a class="img" title="{{ item.name }}" href="{{ item.url }}" intellisuggest>
            <img class="img-responsive" ng-src="{{ item.imageUrl }}" title="{{ item.name }}" alt="{{ item.name }}"  onerror="this.onerror=null;this.src='//cdn.searchspring.net/ajax_search/img/missing-image-75x75.gif';">
          </a>
          <div class="quickview hidden-xs">
            <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="{{ item.url }}" title="Quick View"><i class="fa fa-eye"></i></a>
          </div>
          <div class="zoom hidden-xs">
            <a data-toggle="tooltip" data-placement="top" href="/images/product/{{ item.uid }}_l.jpg" class="product-zoom info-view colorbox cboxElement" title="{{ item.name }}"><i class="fa fa-search-plus"></i></a>
          </div>
        </div>
      </div>
      <div class="product-meta">
        <div class="left">
          <h6 class="name"><a href="{{ item.url }}" intellisuggest>{{ item.name }}</a></h6>
          <p class="description"></p>
          <div class="price">
            <span class="price-old" ng-if="item.msrp > item.price"><font class="list_price">List Price :  </font>{{ item.msrp | currency:'£':2 }}</span>
            <span class="price-new"><font class="pay_price">You Pay :  </font>{{ item.price | currency:'£':2 }}</span>
          </div>
          <div class="save_alter"><span ng-if="item.msrp > item.price">{{ item.discount_text }}</span></div>
        </div>
        <div class="right">
          <div class="action">
            <div class="cart">
              <button data-loading-text="Loading..." class="btn btn-primary" type="button" ng-click="addCart(item.uid)">
                <i class="fa fa-shopping-cart"></i>
                <span class="add-to-cart">Add to Cart</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</script>


<style>
  body .searchspring-overlay {
    background-color: rgba(51, 51, 51, 0.72);
  }

  body #searchspring-slideout_container {
    background: #323232;
    box-shadow: -1px 0px 5px #000;
  }

  body #searchspring-slideout_container .searchspring-slideout_button, body .searchspring-slideout_button {
    background-color: #323232;
    color: #fff;
    -webkit-transition: all 250ms ease-in-out 0s;
    -moz-transition: all 250ms ease-in-out 0s;
    -ms-transition: all 250ms ease-in-out 0s;
    -o-transition: all 250ms ease-in-out 0s;
    transition: all 250ms ease-in-out 0s;
  }
  body #searchspring-slideout_container .searchspring-slideout_button:hover, body .searchspring-slideout_button:hover {
    background-color: #417F0B;
    color: #fff;
    cursor: pointer;
  }

  body #searchspring-slideout_container .searchspring-slideout_button {
    background-image: url("//cdn.searchspring.net/ajax_search/sites/a4dseg/img/back_white.png");
    background-size: 90% 90%;
    background-repeat: no-repeat;
    background-position: center;
    text-align: center;
    height: 42px;
    width: 42px;
    margin: 10px;
    float: right;
  }

  body #searchspring-slideout_container #searchspring-slideout_facets {
    clear: both;
    padding: 10px;
  }
  body .searchspring-slideout_button {
    display: none;
    text-align: center;
    height: 40px;
    width: 100%;
    margin: 0 0 20px;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    position: relative;
  }

  @media only screen and (max-width: 991px) {
    body #sidebar-right {
      display: none;
    }
    body .searchspring-slideout_button {
      display: block;
    }
    .col-sm-9 {
      width: 100%;
    }
  }
  body .searchspring-slideout_button .searchspring-slideout_button_icon {
    width: 30px;
    height: 20px;
    position: absolute;
    top: 10px;
    left: 10px;
    background: url("//cdn.searchspring.net/ajax_search/img/sidebar-menuicon.png") no-repeat 0px 0px;
  }
  body .searchspring-slideout_button .searchspring-slideout_button_text {
    line-height: 40px;
  }

  .ss-filtered_current ~ li:not(.show-more) {
    margin: 0 0 0 20px;
  }

  .ss-filtered_current {
    font-family: "Open Sans Semibold", sans-serif;
    line-height: 140%;
    color: #fff;
  }

  .ss-filtered_link a {
    color: #999 !important;
  }

  .accordion-body.ss-hierarchy {
    max-height: initial;
  }

  .accordion-body:not(.ss-hierarchy) a:before {
    content: " ";
    display: inline-block;
    width: 16px;
    height: 16px;
    margin: 0 3px 0 0;
    vertical-align: middle;
    background: url('//cdn.searchspring.net/ajax_search/img/nocheck.png') no-repeat center center;
  }

  .accordion-body:not(.ss-hierarchy) a:hover:before, .accordion-body:not(.ss-hierarchy) a.active:before {
    background: url('//cdn.searchspring.net/ajax_search/img/check.png') no-repeat center center;
  }

  .ss-filtered_link > a:before {
    content: "<";
  }

  .ss-did-you-mean {
    display: block;
    text-align: left;
  }

  .no-results {
    text-align: left;
  }

  .show-more:hover {
    cursor: pointer;
  }

  #column-right {
    min-height: 1080px;
    height: initial;
  }

  .accordion-body {
    max-height: 350px;
    overflow-y: auto;
  }

</style>

<!-- V3 Init script -->

    <?php
      echo "<script type='text/javascript' src='//s3.amazonaws.com/a.cdn.searchspring.net/ajax_search/sites/" . $searchspring_site_id . "/js/" . $searchspring_site_id . ".js'></script>";

      echo '<script src="//cdn.searchspring.net/search/v3/js/searchspring.catalog.js" searchspring-catalog="' . $searchspring_site_id . ':search:w">';
    ?>

  SearchSpring.Catalog.importer.external('slideout', '//a.cdn.searchspring.net/sandbox/js/slideout.js', { width: 991 });

  SearchSpring.Catalog.on('afterSearch', function($scope) {
    $scope.layout = $scope.layout || 'grid';
    $scope.setLayout = function(layout) {
      $scope.layout = layout;
    }

    $scope.addWishList = function(id) {
      wishlist.addwishlist(id);
    }

    $scope.addCart = function(id) {
      cart.addcart(id)
    }

  });

  SearchSpring.Catalog.on('domReady', function() {
    $('[data-toggle=\'tooltip\']').tooltip('destroy').tooltip({container: 'body'});
    $('.product-zoom').magnificPopup({
      type: 'image',
      closeOnContentClick: true,
      image: { verticalFit: true }
    });

    $('.iframe-link').magnificPopup({ type: 'iframe' });
  });
</script>

        <script type='text/javascript' src='https://d2r7ualogzlf1u.cloudfront.net/ajax_search/js/searchspring-catalog.min.js'></script>
        <script type='text/javascript'>SearchSpring.Catalog.init({
            results_per_page: 24,
            maxFacetOptions: 6,
            sortType: 'dropdown',
            resultsPerPageType: 'dropdown',
            expandedText : 'Expand',
            collapsedText : 'Collapse',
            showSearchHistory: true,
            showSummary: true,
            forwardSingle: false,
            afterResultsChange: function() {
                SearchSpring.jQuery("#searchspring .vb-item .info").each(function() {
                    var desc = SearchSpring.jQuery(this).text()
                    if(desc.length > 300) {
                        SearchSpring.jQuery(this).text(desc.substr(0,300) + '...');
                    }
                });

                var skus = [];
                SearchSpring.jQuery('.vb-item').each(function() {
                    skus.push(SearchSpring.jQuery(this).attr('id'));
                });

                skus = skus.join(',');
                SearchSpring.jQuery.ajax(
                        ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dev.digitaldiscount.co.uk:8000/search/ss-live-price/WEBGB?sku=' + skus,
                        {
                            dataType : 'json',
                            success : function(data) {
                                for(var sku in data) {
                                    var price = data[sku];
                                    SearchSpring.jQuery('#before_'+sku).text(price[0]);
                                    SearchSpring.jQuery('#price_'+sku).text(price[1]);
                                    SearchSpring.jQuery('#discount_'+sku).text(price[2]);
                                    SearchSpring.jQuery('#stock_'+sku).text(price[3]);

                                    if (price[4] == 'O' || price[4] == 'A') {
                                        SearchSpring.jQuery('#add_'+sku).hide();
                                    }
                                }
                            }
                        }
                );

                var i = 0;
                SearchSpring.jQuery('.per-page select option').each(function(){
                    i++;
                    if(i == 1) {
                        SearchSpring.jQuery(this).text('12');
                    } else if(i == 2) {
                        SearchSpring.jQuery(this).text('24');
                    } else {
                        SearchSpring.jQuery(this).text('48');
                    }
                });

                if(SearchSpring.jQuery('#searchspring-summary li').length > 0) {
                    SearchSpring.jQuery('#searchspring-summary_container').show();
                } else {
                    SearchSpring.jQuery('#searchspring-summary_container').hide();
                }

                if(SearchSpring.jQuery('#searchspring .no-results').length > 0) {
                    SearchSpring.jQuery('#search_sidebar').hide();
                    SearchSpring.jQuery('#searchspring').css('width', '970px');
                } else {
                    SearchSpring.jQuery('#search_sidebar').show();
                    SearchSpring.jQuery('#searchspring').css('width', '800px');
                }

                var dym = SearchSpring.jQuery('#searchspring-did_you_mean:visible');
                if(dym.length > 0) {
                    var dym_text = dym.find('a').text();
                    var dym_link = SearchSpring.jQuery('<a />').text(dym_text).click(function() {
                        SearchSpring.jQuery.address.parameter('q', dym_text);
                        SearchSpring.jQuery.address.parameter('page', 1);
                        SearchSpring.jQuery.address.update();
                    });
                    dym.empty().append('Did you mean ').append(dym_link).append('?');
                }
            }
        });</script>

    </div>
</div>

<div class="clearfix"></div>
<?php $this->load->view('footer') ?>
