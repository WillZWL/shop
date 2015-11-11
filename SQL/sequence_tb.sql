CREATE TABLE sequence_tb (
  seq_name varchar(64) not null,
  cur_value bigint unsigned not null,
  increment_value tinyint not null default 1,
  primary key (seq_name)
) ENGINE = InnoDB;


insert sequence_tb values('so_no', 0, 1);
insert sequence_tb values('sku', 0, 1);
