create table users(
  username varchar(255) not null,
  passwd varchar(255) not null,
  id int(5) not null auto_increment,
  primary key(id)
);

create table associations(
  name varchar(127) not null,
  id int(5) not null auto_increment,
  primary key(id)
);

create table user_associations(
  user int(5) not null,
  association int(5) not null,
);

create table entities(
  name varchar(127) not null,
  id int(5) not null auto_increment,
  primary key(id)
);

create table acl(
  entity int(5) not null,
  association int(5) not null,
  permission int(4) not null,
)



