<?php $this->load->view('header');

    $siteobj = \PUB_Controller::$siteInfo;
    $platCountryId = $siteobj->getPlatformCountryId();;
    switch (strtolower($platCountryId)) {
        case 'gb' :
            $searchspring_site_id = 'jdajtq';
            break;
        case 'nz' :
            $searchspring_site_id = '61jj96';
            break;
        case 'au' :
            $searchspring_site_id = 'dkow9j';
            break;
        case 'es' :
            $searchspring_site_id = '7g2sk7';
            break;
        case 'it' :
            $searchspring_site_id = '1eq9mh';
            break;
        case 'fr' :
            $searchspring_site_id = 'rtkr86';
            break;
        case 'be' :
            $searchspring_site_id = 'm15dls';
            break;
        case 'pl' :
            $searchspring_site_id = 'yf45du';
            break;
        default   :
            $searchspring_site_id = '';
    }
?>


<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">

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

<div class="main-columns container">
  <div id="content" style="margin: 20px auto">
    <aside id="sidebar-right" class="col-md-3"></aside>

    <div class="products-block  col-lg-9 col-sm-9 col-xs-12"></div>

    <?php
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



    </div>
</div>

<div class="clearfix"></div>
<?php $this->load->view('footer') ?>
