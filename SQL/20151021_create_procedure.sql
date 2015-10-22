-- Create syntax for PROCEDURE 'add_feature_right'
DELIMITER ;;
CREATE DEFINER=`panther`@`localhost` PROCEDURE `add_feature_right`(IN in_app_id VARCHAR(20), IN last_id INT, IN in_role_id VARCHAR(20))
begin
    insert into application_feature_right (app_id, app_feature_id, role_id, status, create_on, create_at, create_by, modify_at, modify_by)
    values(in_app_id, last_id, in_role_id, 1, now(), '2130706433', 'system', '2130706433', 'system');
end;;
DELIMITER ;

-- Create syntax for PROCEDURE 'add_feature_right_by_name'
DELIMITER ;;
CREATE DEFINER=`panther`@`localhost` PROCEDURE `add_feature_right_by_name`(IN in_app_id VARCHAR(20), IN in_feature_name VARCHAR(128), IN in_role_id VARCHAR(20))
begin
    CALL get_app_feature_id_by_name(in_feature_name, @feature_id);
    insert into application_feature_right (app_id, app_feature_id, role_id, status, create_on, create_at, create_by, modify_at, modify_by)
    values(in_app_id, @feature_id, in_role_id, 1, now(), '2130706433', 'system', '2130706433', 'system');
end;;
DELIMITER ;

-- Create syntax for PROCEDURE 'add_role_right'
DELIMITER ;;
CREATE DEFINER=`panther`@`localhost` PROCEDURE `add_role_right`(IN in_app_id VARCHAR(64), IN in_role_id VARCHAR(64))
BEGIN
DECLARE l_right_id INT DEFAULT 0;
    select distinct r.id into l_right_id from rights r
                                        inner join role_rights rr on rr.rights_id=r.id
                                        where r.app_id=in_app_id and r.rights='' limit 1;
    IF not l_right_id then
        insert into rights (app_id, rights, status, create_on, create_at, create_by, modify_at, modify_by)
        values (in_app_id, '', 1, now(), '2130706433', 'system', '2130706433', 'system');
        select LAST_INSERT_ID() into l_right_id;
    end IF;
    insert into role_rights (role_id, rights_id, create_on, create_at, create_by, modify_at, modify_by)
    values(in_role_id, l_right_id, now(), '2130706433', 'system', '2130706433', 'system');
end;;
DELIMITER ;

-- Create syntax for PROCEDURE 'create_feature_id'
DELIMITER ;;
CREATE DEFINER=`panther`@`localhost` PROCEDURE `create_feature_id`(IN new_feature VARCHAR(128), OUT last_id INT)
BEGIN
/* insert */
insert into application_feature (feature_name, status, create_on, create_at, create_by, modify_at, modify_by)
values(new_feature, 1, now(), '2130706433', 'system', '2130706433', 'system');
/* get last id*/
    select LAST_INSERT_ID() into last_id;
/* create right linkage */
end;;
DELIMITER ;


-- Create syntax for PROCEDURE 'find_application_id'
DELIMITER ;;
CREATE DEFINER=`panther`@`localhost` PROCEDURE `find_application_id`(IN in_app_id VARCHAR(10), IN in_user_name VARCHAR(32))
BEGIN
    if in_user_name = '' then
        select distinct a.* from application a
        inner join rights r on a.id=r.app_id and r.rights='' and a.status=1 and r.status=1
        inner join role_rights rr on rr.rights_id=r.id
        inner join user_role ur on ur.role_id=rr.role_id
        where a.id like in_app_id and a.status=1 order by a.display_order;
    ELSE
        select distinct a.* from application a
        inner join rights r on a.id=r.app_id and r.rights='' and a.status=1 and r.status=1
        inner join role_rights rr on rr.rights_id=r.id
        inner join user_role ur on ur.role_id=rr.role_id and ur.user_id=in_user_name
        where a.id like in_app_id and a.status=1 order by a.display_order;
    END IF;
end;;
DELIMITER ;

-- Create syntax for PROCEDURE 'find_role_in_app'
DELIMITER ;;
CREATE DEFINER=`panther`@`localhost` PROCEDURE `find_role_in_app`(IN in_app_id VARCHAR(64))
BEGIN
        select r.app_id, rr.role_id, r.id from rights r
        inner join role_rights rr on rr.rights_id=r.id
        where r.app_id=in_app_id;
end;;
DELIMITER ;

-- Create syntax for PROCEDURE 'get_app_feature_id_by_name'
DELIMITER ;;
CREATE DEFINER=`panther`@`localhost` PROCEDURE `get_app_feature_id_by_name`(IN new_feature VARCHAR(128), OUT out_app_feature_id INT)
BEGIN
select app_feature_id into out_app_feature_id from application_feature where feature_name=new_feature;
end;;
DELIMITER ;


-- Create syntax for FUNCTION 'current_value'
DELIMITER ;;
CREATE DEFINER=`panther`@`localhost` FUNCTION `current_value`(i_seq_name VARCHAR(64)) RETURNS bigint(20)
BEGIN
    DECLARE return_value bigint;
    SET return_value = 0;
    SELECT cur_value INTO return_value FROM sequence_tb WHERE seq_name = i_seq_name;
    RETURN return_value;
END;;
DELIMITER ;

-- Create syntax for FUNCTION 'next_value'
DELIMITER ;;
CREATE DEFINER=`panther`@`localhost` FUNCTION `next_value`(i_seq_name VARCHAR(64)) RETURNS bigint(20)
BEGIN
    UPDATE sequence_tb SET cur_value = cur_value + increment_value WHERE seq_name = i_seq_name;
    RETURN current_value(i_seq_name);
END;;
DELIMITER ;
