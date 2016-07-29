<?php $this->load->view('header');?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
<link href="/themes/default/asset/css/new-searchspring.css" rel="stylesheet" />
<style type="text/css">#searchspring-powered_by{display: none}</style>
  <div id="content" style="margin: 20px auto">
    <aside id="sidebar-right" class="col-md-3">
       <div id="column-right" class="hidden-xs sidebar">
            <div class="panel panel-default nopadding">
                <!--<div class="panel-heading"><h4>Categories</h4></div>-->
                <div class="panel-body tree-menu">
                    <div class="searchspring-facets_container"></div>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    var active = $('.collapse.in').attr('id');
                    //$('span[data-target=#accordiondata]').html("<i class='fa fa-angle-down'></i>");
                    $('span[data-target=#'+active+']').html("<i class='fa fa-angle-down'></i>");

                    $('.collapse').on('hide.bs.collapse', function () {
                        $('span[data-target=#'+$(this).attr('id')+']').html("<i class='fa fa-angle-down'></i>");
                        if ($(this).attr('id')=='accordiondata')
                        {
                            $('#lblmore').removeClass('hide');
                            $('#lblless').addClass('hide');
                        }
                        else
                        {
                            $('#lblmore1').removeClass('hide');
                            $('#lblless1').addClass('hide');
                        }
                    });
                    $('.collapse').on('show.bs.collapse', function () {
                        $('span[data-target=#'+$(this).attr('id')+']').html("<i class='fa fa-angle-right'></i>");
                        if ($(this).attr('id')=='accordiondata')
                        {
                            $('#lblless').removeClass('hide');
                            $('#lblmore').addClass('hide');
                        }
                        else
                        {
                            $('#lblless1').removeClass('hide');
                            $('#lblmore1').addClass('hide');
                        }
                    });
                });
            </script>
        </div>
    </aside>

    <div class="products-block  col-lg-9 col-sm-9 col-xs-12">
        <div class="searchspring-results_container"></div>
    </div>
    </div>

<script type="text/javascript" src="//cdn.searchspring.net/ajax_search/js/searchspring-catalog.min.js"></script>
<script type="text/javascript" src="//cdn.searchspring.net/ajax_search/sites/<?=$searchSpringSiteId?>/js/<?=$searchSpringSiteId?>.js"></script>
<script type='text/javascript'>SearchSpring.Catalog.init({
    results_per_page: 24,
    maxFacetOptions: 6,
    sortType: 'dropdown',
    loadCSS: false,
    resultsPerPageType: 'dropdown',
    layout:'top',
    expandedText : '<?=$lang_text['collapse']?>',
    collapsedText : '<?=$lang_text['expand']?>',
    showSearchHistory: true,
    showSummary: true,
    result_layout:'grid',
    forwardSingle: false,
    filterText: 'Refine Search',
    summaryText: 'Summary',
    resultsPerPageOptions: [12,25,50,75,100],
    filterData: function(d) {
      for(var i = 0; i < d.facets.length; i++){
        d.facets[i].collapse = 1;
      }
      console.log(d);
      return d;
    },
    afterResultsChange: function() {

      SearchSpring.jQuery("#searchspring .panther-item .name").each(function() {
        var desc = SearchSpring.jQuery(this).text()
        if(desc.length > 300) {
          SearchSpring.jQuery(this).text(desc.substr(0,300) + '...');
        }
      });

      var skus = [];
      SearchSpring.jQuery('.panther-item').each(function() {
        skus.push(SearchSpring.jQuery(this).attr('id'));
      });

      skus = skus.join(',');

      SearchSpring.jQuery.ajax({
            url:'/search/ssLivePrice/<?=PLATFORM?>?sku=' + skus,
            dataType : 'json',
            success : function(data) {
              for(var sku in data) {
                var price = data[sku];
                SearchSpring.jQuery('#before_'+sku).text(price[0]);
                SearchSpring.jQuery('#price_'+sku).text(price[1]);
                SearchSpring.jQuery('#discount_'+sku).text(price[2]);
                SearchSpring.jQuery('#stock_'+sku).text(price[3]);
              }
            }
          }
      );
      var gird_layout=SearchSpring.jQuery("#searchspring-grid_result_layout");
      var list_layout=SearchSpring.jQuery("#searchspring-list_result_layout");
      if(gird_layout.has("i").length ==0){
        gird_layout.append('<i class="fa fa-th"></i>');
      }
      if(list_layout.has("i").length ==0){
        list_layout.append('<i class="fa fa-th-list"></i>');
      }

     /* SearchSpring.jQuery('.panther-item a').each(function() {
        var a = SearchSpring.jQuery(this).attr("href");
        SearchSpring.jQuery(this).attr("href", a.replace(/www.valuebasket\.(.+?)(\/<?=str_replace('/', '', $lang_country_pair)?>)?\//, 'm.valuebasket.com<?=($lang_country_pair == '' ? '/' : $lang_country_pair)?>'));
      });*/

      if(!SearchSpring.jQuery('#searchspring-search_results').hasClass("row")){
        SearchSpring.jQuery('#searchspring-search_results').addClass("row");
      }


      if(SearchSpring.jQuery('#searchspring-summary li').length > 0) {
        SearchSpring.jQuery('#searchspring-summary_container').show();
      } else {
        SearchSpring.jQuery('#searchspring-summary_container').hide();
      }

      if(SearchSpring.jQuery('#searchspring .no-results').length > 0) {
        SearchSpring.jQuery('#search_sidebar').hide();
      } else {
        SearchSpring.jQuery('#search_sidebar').show();
      }

      var dym = SearchSpring.jQuery('#searchspring-did_you_mean:visible');
      if(dym.length > 0) {
        var dym_text = dym.find('a').text();
        var dym_link = SearchSpring.jQuery('<a />').text(dym_text).click(function() {
          SearchSpring.jQuery.address.parameter('q', dym_text);
          SearchSpring.jQuery.address.parameter('page', 1);
          SearchSpring.jQuery.address.update();
        });
        dym.empty().append('<?=$lang_text['did_you_mean']?>').append(dym_link).append('?');
      }
    }
  });</script>

<div class="clearfix"></div>
<?php $this->load->view('footer') ?>
