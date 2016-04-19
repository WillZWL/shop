/**
 * Need remove panther view; If have by view v_prod_overview_wo_shiptype or v_prod_w_platform_biz_var, You can direct by down sql to overwrite the two view
 */

/**
 * rewrite v_prod_overview_wo_shiptype start //
 */

SELECT p.sku AS sku,
       p.prod_grp_cd AS prod_grp_cd,
       p.version_id AS version_id,
       p.colour_id AS colour_id,
       p.name AS prod_name,
       pbv.selling_platform_id AS platform_id,
       pbv.platform_region_id AS platform_region_id,
       pbv.platform_country_id AS platform_country_id,
       pbv.vat_percent AS vat_percent,
       pbv.payment_charge_percent AS payment_charge_percent,
       coalesce(fc.declared_pcent,100) AS declared_pcent,
       coalesce(cc.duty_pcent,0) AS duty_pcent,
       cc.code AS cc_code,
       cc.description AS cc_desc,
       coalesce(pbv.admin_fee,0) AS admin_fee,
       0 AS freight_cost,
       0 AS delivery_cost,
       coalesce((sp.cost * sper.rate),0) AS supplier_cost,
       sp.cost AS item_cost,
       sp.modify_on AS purchaser_updated_date,
       0 AS delivery_charge,
       fc.weight AS prod_weight,
       pbv.free_delivery_limit AS free_delivery_limit,
       p.quantity AS quantity,
       p.clearance AS clearance,
       p.website_quantity AS website_quantity,
       px.ext_qty AS ext_qty,
       p.proc_status AS proc_status,
       p.website_status AS website_status,
       p.sourcing_status AS sourcing_status,
       p.cat_id AS cat_id,
       p.sub_cat_id AS sub_cat_id,
       p.sub_sub_cat_id AS sub_sub_cat_id,
       p.brand_id AS brand_id,
       p.image AS image,
       sp.supplier_id AS supplier_id,
       p.freight_cat_id AS freight_cat_id,
       p.ean AS ean,
       p.mpn AS mpn,
       p.upc AS upc,
       p.status AS prod_status,
       p.display_quantity AS display_quantity,
       p.youtube_id AS youtube_id,
       p.ex_demo AS ex_demo,
       scpv.platform_commission_percent AS platform_commission,
       scpv.fixed_fee AS listing_fee,
       scpv.profit_margin AS sub_cat_margin,
       pbv.platform_currency_id AS platform_currency_id,
       pbv.language_id AS language_id,
       pbv.forex_fee_percent AS forex_fee_percent,
       px.ext_item_id AS ext_item_id,
       px.handling_time AS handling_time,

        if((pr.price > 0),pr.price,round((dp.price * er.rate),2)) AS price,
       pr.price AS current_platform_price,
       round((dp.price * er.rate),2) AS default_platform_converted_price,
       pr.platform_code AS platform_code,
       pr.listing_status AS listing_status,
       pr.auto_price AS auto_price

FROM  product p
LEFT JOIN bundle b ON p.sku = b.prod_sku
LEFT JOIN freight_category fc ON fc.id = p.freight_cat_id
LEFT JOIN (
   supplier_prod sp
   JOIN supplier s
   JOIN exchange_rate sper
   JOIN platform_biz_var pbv
) ON p.sku = sp.prod_sku
   AND sp.supplier_id = s.id
   AND sp.currency_id = sper.from_currency_id
   AND pbv.platform_currency_id = sper.to_currency_id
   AND sp.order_default = 1
LEFT JOIN sub_cat_platform_var scpv ON p.sub_cat_id = scpv.sub_cat_id AND pbv.selling_platform_id = scpv.platform_id
LEFT JOIN product_custom_classification cc ON cc.sku = p.sku AND cc.country_id = pbv.platform_country_id
LEFT JOIN price_extend px ON px.sku = p.sku AND px.platform_id = pbv.selling_platform_id

LEFT JOIN price pr on pr.sku = p.sku AND pbv.selling_platform_id = pr.platform_id
LEFT JOIN (
   price dp
   JOIN exchange_rate er
   JOIN config cf on cf.variable = "default_platform_id"
) on p.sku = dp.sku
   AND dp.platform_id = cf.value
   AND er.from_currency_id = 'HKD'
   AND er.to_currency_id = pbv.platform_currency_id
WHERE isnull(b.prod_sku)

/**
 * rewrite v_prod_overview_wo_shiptype // end;
 */


/**
 * rewrite v_prod_w_platform_biz_var start //
 */

SELECT p.sku AS sku,
       p.prod_grp_cd AS prod_grp_cd,
       p.version_id AS version_id,
       p.colour_id AS colour_id,
       p.name AS prod_name,
       pbv.selling_platform_id AS platform_id,
       pbv.platform_region_id AS platform_region_id,
       pbv.platform_country_id AS platform_country_id,
       pbv.vat_percent AS vat_percent,
       pbv.payment_charge_percent AS payment_charge_percent,
       coalesce(fc.declared_pcent,100) AS declared_pcent,
       coalesce(cc.duty_pcent,0) AS duty_pcent,
       cc.code AS cc_code,
       cc.description AS cc_desc,
       coalesce(pbv.admin_fee,0) AS admin_fee,
       0 AS freight_cost,
       0 AS delivery_cost,
       coalesce((sp.cost * sper.rate),0) AS supplier_cost,
       sp.cost AS item_cost,
       sp.modify_on AS purchaser_updated_date,
       0 AS delivery_charge,
       fc.weight AS prod_weight,
       pbv.free_delivery_limit AS free_delivery_limit,
       p.quantity AS quantity,
       p.clearance AS clearance,
       p.website_quantity AS website_quantity,
       px.ext_qty AS ext_qty,
       p.proc_status AS proc_status,
       p.website_status AS website_status,
       p.sourcing_status AS sourcing_status,
       p.cat_id AS cat_id,
       p.sub_cat_id AS sub_cat_id,
       p.sub_sub_cat_id AS sub_sub_cat_id,
       p.brand_id AS brand_id,
       p.image AS image,
       sp.supplier_id AS supplier_id,
       p.freight_cat_id AS freight_cat_id,
       p.ean AS ean,
       p.mpn AS mpn,
       p.upc AS upc,
       p.status AS prod_status,
       p.display_quantity AS display_quantity,
       p.youtube_id AS youtube_id,
       p.ex_demo AS ex_demo,
       scpv.platform_commission_percent AS platform_commission,
       scpv.fixed_fee AS listing_fee,
       scpv.profit_margin AS sub_cat_margin,
       pbv.platform_currency_id AS platform_currency_id,
       pbv.language_id AS language_id,
       pbv.forex_fee_percent AS forex_fee_percent,
       px.ext_item_id AS ext_item_id,
       px.handling_time AS handling_time
FROM  product p
LEFT JOIN bundle b ON p.sku = b.prod_sku
LEFT JOIN freight_category fc ON fc.id = p.freight_cat_id
LEFT JOIN (
   supplier_prod sp
   JOIN supplier s
   JOIN exchange_rate sper
   JOIN platform_biz_var pbv
) ON p.sku = sp.prod_sku
   AND sp.supplier_id = s.id
   AND sp.currency_id = sper.from_currency_id
   AND pbv.platform_currency_id = sper.to_currency_id
   AND sp.order_default = 1
LEFT JOIN sub_cat_platform_var scpv ON p.sub_cat_id = scpv.sub_cat_id AND pbv.selling_platform_id = scpv.platform_id
LEFT JOIN product_custom_classification cc ON cc.sku = p.sku AND cc.country_id = pbv.platform_country_id
LEFT JOIN price_extend px ON px.sku = p.sku AND px.platform_id = pbv.selling_platform_id
WHERE isnull(b.prod_sku)

/**
 * rewrite v_prod_w_platform_biz_var // end;
 */