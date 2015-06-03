<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport"  content="user-scalable=yes, width=280" />
		<title>Sales Trend Report</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.1/css/bootstrap.min.css">
		<!-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.0/css/jquery.dataTables.css"> -->
		<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.css">
		<link rel="stylesheet" href="/css/bootstrap-multiselect.css">

		<style>
			.my-group {
				margin: 15px 0;
			}

			.my-group > * {
				display: inline-block;
				width: auto;
			}

			.my-group > span {
				margin: 0 10px;
			}

			.my-group.top > * {
				vertical-align: top;
			}

			.my-group.bottom > * {
				vertical-align: bottom;
			}

			.multiselect-container>li>label.multiselect-group {
				padding: 3px 7px;
			}

			td.details-control, .multiselect-group, .multiselect-checkbox {
				cursor: pointer;
			}

			.dataTables_processing:after {
				content: url('/images/loading.gif');
			}

			.price_reset, .price_check {
				margin-left: 5px;
			}

			#result_container .pull-left, #result_container .pull-right {
				margin: 7px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-heading">Sales Trend Report</div>
				<form id="search" role="form" class="form panel-body">
					<div class="bottom my-group">
						<span>Order date<br>(Required)</span>
						<input type="date" class="form-control" id="order_date_from" name="order_date_from" required>
						<span>till</span>
						<input type="date" class="form-control" id="order_date_to" name="order_date_to" required>
					</div>

					<div class="top my-group">
						<span>Filter by<br>(only 1 will apply)</span>
						<input type="text" class="form-control" id="product_name" name="product_name" placeholder="Product Name">
						<span>or</span>
						<textarea class="form-control" name="ext_sku" placeholder="Master SKU, separated by new line" rows="3"></textarea>
						<span>or</span>
						<textarea class="form-control" name="prod_sku" placeholder="Local SKU, separated by new line" rows="3"></textarea>
					</div>

					<div class="row form-group">
						<div class="col-xs-3">
							<div class="radio">
								<label>
									<input type="radio" name="clearance" value="no" checked>
									Show <strong>all</strong> SKUs
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="clearance" value="yes">
									Show <strong>only clearance</strong> SKUs
								</label>
							</div>
						</div>
					</div>

					<div class="my-group">
						<input type="submit" class="form-control btn btn-primary" value="Search">
					</div>
				</form>
			</div>

			<div class="panel panel-default" id="result_container">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-10">
	  						<span>Results</span>
  						</div>
  						<div class="col-xs-2">
							<select id="platform" multiple name="platform">
								<optgroup label="MARKETPLACE">
									<option class="MARKETPLACE" value="FNACFR">FNACFR</option>
									<option class="MARKETPLACE" value="LAMY">LAMY</option>
									<option class="MARKETPLACE" value="QOO10SG">QOO10SG</option>
									<option class="MARKETPLACE" value="TMNZ">TMNZ</option>
									<option class="MARKETPLACE" value="RAKUES">RAKUES</option>						
								</optgroup>
								<optgroup label="EBAY">
									<option class="EBAY" value="EBAYAU">EBAYAU</option>
									<option class="EBAY" value="EBAYMY">EBAYMY</option>
									<option class="EBAY" value="EBAYSG">EBAYSG</option>
									<option class="EBAY" value="EBAYUK">EBAYUK</option>
									<option class="EBAY" value="EBAYUS">EBAYUS</option>								
								</optgroup>
								<optgroup label="WEB">
									<option class="WEB" value="WEBAU">WEBAU</option>
									<option class="WEB" value="WEBBE">WEBBE</option>
									<option class="WEB" value="WEBCH">WEBCH</option>
									<option class="WEB" value="WEBES">WEBES</option>
									<option class="WEB" value="WEBFI">WEBFI</option>
									<option class="WEB" value="WEBFR">WEBFR</option>
									<option class="WEB" value="WEBGB">WEBGB</option>
									<option class="WEB" value="WEBHK">WEBHK</option>
									<option class="WEB" value="WEBIE">WEBIE</option>
									<option class="WEB" value="WEBIT">WEBIT</option>
									<option class="WEB" value="WEBMT">WEBMT</option>
									<option class="WEB" value="WEBMY">WEBMY</option>
									<option class="WEB" value="WEBNZ">WEBNZ</option>
									<option class="WEB" value="WEBPH">WEBPH</option>
									<option class="WEB" value="WEBPL">WEBPL</option>
									<option class="WEB" value="WEBPT">WEBPT</option>
									<option class="WEB" value="WEBRU">WEBRU</option>
									<option class="WEB" value="WEBSG">WEBSG</option>
									<option class="WEB" value="WEBUS">WEBUS</option>
								</optgroup>
							</select>
						</div>
					</div>
				</div>
				<table id="results" class="table table-striped table-bordered table-condensed"></table>
			</div>
		</div>

		<!-- Placeholder platform HTML -->
		<div class="platform_group">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-4">
	  						<span>Competitors</span>
  						</div>
  						<div class="col-xs-8">
							<button class="btn btn-danger price_reset pull-right">Reset price</button>
							<button class="btn btn-primary price_check pull-right">Check price</button>
							<input type="text" name="supplier_cost" class="form-control text-right pull-right">
                      	</div>
                  	</div>
              	</div>
				<table class="nested table table-striped table-bordered table-condensed"></table>
			</div>
		</div>

		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.0/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/js/TableTools.min.js"></script>
		<script type="text/javascript" src="//cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.js"></script>
		<script type="text/javascript" src="/js/bootstrap-multiselect.js"></script>

		<script>
			var platform_group = $('.platform_group').clone();
			$('.platform_group').remove();

			var data_table = $('#results').DataTable({
				processing: true,
				serverSide: true,
				deferLoading: 0,
				searching: false,
				ordering: false,
				ajax: {
					url: "/report/sales_trend_report/get_sales",
					data: function(data) {
						return $.extend(get_payload($('#search')), {
							start: data.start,
							length: data.length
						});
					}
				},
				dom: '<<"pull-left"l><"pull-right"T>>frt<"row"<"col-xs-6"<"pull-left"i>><"col-xs-6"<"pull-right"p>>>', // Clusterfuck of bootstrap's API
				oTableTools: {
					sSwfPath: "//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/swf/copy_csv_xls.swf",
					aButtons: [
						{
							sExtends: "copy",
							bSelectedOnly: true,
							mColumns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
						},
						{
							sExtends: "csv",
							bSelectedOnly: true,
							mColumns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
						}
					]
				},
				columns: [
					{
						"class": 'details-control',
						data: null,
						defaultContent: '<span class="glyphicon glyphicon-align-justify"></span>'
					},
					{ 
						data: "ext_sku",
						title: "Master SKU",
						width: "75px"
					},
					{ 
						data: "name", 
						title: "Name"
					},
					{ 
						data: "clearance",
						title: "Clearance",
						className: "text-right"
					},
					{ 
						data: "supplier_name",
						title: "Current Supplier"
					},
					{ 
						data: "sourcing_status",
						title: "Source Status",
						className: "text-right"
					},
					{ 
						data: "inventory",
						title: "WMS STK",
						className: "text-right"
					},
					{ 
						data: "surplus_quantity",
						title: "Surplus Quantity",
						className: "text-right"
					},
					{ 
						data: "web",
						title: "Web Sales",
						className: "text-right"
					},
					{ 
						data: "nonweb",
						title: "Platform Sales",
						className: "text-right"
					},
					{ 
						data: "count",
						title: "Total Sales",
						className: "text-right"
					},
					{ 
						data: "prod_sku",
						title: "Local SKU",
						className: "text-right",
						width: "75px"
					}
				]
			});

			function create_nested_table(prod_sku) {
				var selector = '#results-' + prod_sku;
				$(selector).dataTable({
					processing: true,
					serverSide: true,
					searching: false,
					ordering: false,
					paging: false,
					info: false,
					ajax: {
						url: "/report/sales_trend_report/get_platforms",
						data: function(data) {
							// Apply filter parameters from the search form
							var search = get_payload($('#search'));
							return {
								order_date_from: search.order_date_from,
								order_date_to: search.order_date_to,
								clearance: search.clearance,
								prod_sku: prod_sku,
								supplier_cost: $(selector).parent().siblings().find('input[name=supplier_cost]').val() // Apply price check if applicable								
							};
						}
					},
					drawCallback: filter_platforms,
					createdRow: function(row, data) {
						$(row).addClass(data.platform_id + ' platform').data('platform', data.platform_id);
					},
					columns: [
						{ 
							data: "platform_id",
							title: "Platform",
						},
						{ 
							data: "Competitor", 
							title: "Competitor"
						},
						{ 
							data: "_shipping_cost",
							title: "Comp. Shipping Cost",
							className: "text-right"
						},
						{ 
							data: "platform_currency_id",
							title: "Currency",
							className: "text-right"
						},
						{ 
							data: "CompetitorPrice",
							title: "Theirs",
							className: "text-right"
						},
						{ 
							data: "OurPrice",
							title: "Ours",
							className: "text-right"
						},
						{ 
							data: "Difference",
							title: "Diff.",
							className: "text-right",
							createdCell: color_cell_positive_is_good
						},
						{ 
							data: "_margin",
							title: "Margin",
							className: "text-right",
							createdCell: color_cell_positive_is_good
						},
						{ 
							data: "_supplier_cost",
							title: "Supplier Cost",
							className: "text-right"
						},
						{ 
							data: "listing_status",
							title: "Status",
							className: "text-right",
							width: "75px"
						},
						{ 
							data: "OnlineOrders",
							title: "Online",
							className: "text-right",
							createdCell: function(cell, cellData) {
								$(cell).addClass('online').data('qty', parseInt(cellData));
							}
						},
						{ 
							data: "OfflineOrders",
							title: "Offline",
							className: "text-right",
							createdCell: function(cell, cellData) {
								$(cell).addClass('offline').data('qty', parseInt(cellData));
							}
						}
					]
				});
			}

			function color_cell_positive_is_good(cell, cellData) {
				var margin = parseFloat(cellData);
				if (margin < 0) {
					$(cell).addClass('danger');
				} else if (margin > 0) {
					$(cell).addClass('success');
				}
			}

			function get_payload(form) {
				// http://stackoverflow.com/a/169554/1097483
				var payload = {};
				$.each(form.serializeArray(), function(i, field) {
					payload[field.name] = field.value;
				});

				return payload;
			}

			// Add event listener for opening and closing details
			$(document).on('click', 'td.details-control', function () {
				var tr = $(this).parents('tr');
				var row = data_table.row(tr);

				if (row.child.isShown()) {
					// This row is already open - close it
					row.child.hide();
					tr.removeClass('shown');
				} else {
					var prod_sku = row.data().prod_sku;
					
					if ($('#results-' + prod_sku).length) {
						// Open this row
						row.child().show();
					} else {
						// Create nested table if not exists
						var new_platform_group = platform_group.clone();
						new_platform_group.find('button').data('sku', prod_sku);
						new_platform_group.find('table').attr('id', 'results-' + prod_sku);
						new_platform_group.find('span').html('Competitors for SKU #' + prod_sku);
						row.child(new_platform_group).show();

						// Init datatables
						create_nested_table(prod_sku);
					}

					tr.addClass('shown');
				}
			} );

			function filter_platforms() {
				// Platform filtering function
				var platforms = $('#platform').val();

				if (!platforms) {
					$('.platform').removeClass('hidden');
				} else {
					$('.platform').each(function() {
						if ($.inArray($(this).data('platform'), platforms) >= 0) {
							$(this).removeClass('hidden');
						} else {
							$(this).addClass('hidden');
						}
					});
				}

				$('.nested tbody').each(function() {
					display_totals($(this));
				});
			}

			function display_totals(tbody) {
				tbody.find('.total').remove();

				var online = 0, offline = 0;
				tbody.find('.online').each(function() {
					if ($(this).parent().hasClass('hidden')) {
						return;
					}
					online += $(this).data('qty');
				});

				tbody.find('.offline').each(function() {
					if ($(this).parent().hasClass('hidden')) {
						return;
					}
					offline += $(this).data('qty');
				});

				tbody.append('<tr class="total">'
					+ '<td colspan=10 class="text-right">Total Sales</td>'
					+ '<td class="text-right">' + online + '</td>'
					+ '<td class="text-right">' + offline + '</td>'
				+ '</tr>');
			}

			$(document).on('submit', '#search', function(e) {
				e.preventDefault();

				data_table.ajax.reload();
			});

			// Platform filters
			$('#platform').multiselect({
				nonSelectedText: 'All Platforms',
				includeSelectAllOption: true,
				includeSelectAllDivider: true,
				maxHeight: 400,
				buttonContainer: '<div class="btn-group pull-right">',
				onChange: filter_platforms,
				buttonText: function(options, select) {
					if (options.length == 0 || options.length == select.find('option').length) {
						return this.nonSelectedText + ' <b class="caret"></b>';
					} else {
						if (options.length > this.numberDisplayed) {
							return options.length + ' selected platforms' + ' <b class="caret"></b>';
						} else {
							var selected = '';
							options.each(function() {
								var label = ($(this).attr('label') !== undefined) ? $(this).attr('label') : $(this).html();

								selected += label + ', ';
							});
							return selected.substr(0, selected.length - 2) + ' <b class="caret"></b>';
						}
					}
				}
			});

			$('.multiselect-group').before('<input class="multiselect-checkbox" type="checkbox">');

			$(document).on('click', '.multiselect-checkbox', function(e) {
				e.stopPropagation();

				checkbox = $(this);
				text = $(this).siblings('.multiselect-group').text();

				var values = $('.' + text).map(function(){
					return $(this).val();
				}).get();

				if (checkbox.prop('checked')) {
					$('#platform').multiselect('select', values);
				} else {
					$('#platform').multiselect('deselect', values);
				}

				filter_platforms();
			});

			$(document).on('click', '.multiselect-group', function(e) {
				e.stopPropagation();

				$(this).siblings('.multiselect-checkbox').click();
			});

			// Price check
			$(document).on('click', '.price_check', function() {
				$('#results-' + $(this).data('sku')).DataTable().ajax.reload();
			})

			$(document).on('click', '.price_reset', function() {
				$(this).siblings('input').val("");
				$(this).siblings('.price_check').click();
			})
		</script>
	</body>
</html>