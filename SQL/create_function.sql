DELIMITER  //
CREATE FUNCTION current_value(i_seq_name VARCHAR(64)) RETURNS bigint
BEGIN
    DECLARE return_value bigint;
    SET return_value = 0;
    SELECT cur_value INTO return_value FROM sequence_tb WHERE seq_name = i_seq_name;
    RETURN return_value;
END//

DELIMITER  //
CREATE FUNCTION next_value(i_seq_name VARCHAR(64)) RETURNS bigint
BEGIN
    UPDATE sequence_tb SET cur_value = cur_value + increment_value WHERE seq_name = i_seq_name;
    RETURN current_value(i_seq_name);
END //
