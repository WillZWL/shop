SELECT cat.id cat_id, cat.name cat_name, sc.id sub_cat_id, sc.name sub_cat_name, ccm.country_id, cc.code, cc.description, cc.duty_pcent, ccm.create_on, ccm.create_at, ccm.create_by, ccm.modify_on, ccm.modify_at, ccm.modify_by FROM category sc INNER JOIN  category cat
ON cat.id = sc.`parent_cat_id`
LEFT JOIN custom_classification_mapping ccm ON sc.id = ccm.`sub_cat_id`
LEFT JOIN custom_classification cc ON cc.id = ccm.custom_class_id
WHERE sc.level = 2 AND cat.level = 1;
