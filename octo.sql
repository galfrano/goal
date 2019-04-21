drop table if exists users;
create table users(
  curp varchar(255) not null,
  email varchar(255) not null,
  passwd varchar(255) not null,
  clearance int(1),
  id int(11) unsigned not null auto_increment,
  primary key(id));

drop table if exists products;
create table products(
  name varchar(255) not null,
  price_no_dph float(9,2) not null,
  price_dph float(9,2) not null,
  id int(11) unsigned not null auto_increment,
  primary key(id));

drop table if exists customers;
create table customers(
  name varchar(511) not null,
  address varchar(1023) not null,
  dic varchar(31) not null,
  ic varchar(31) not null,
  id int(11) unsigned not null auto_increment,
  primary key(id));

drop table if exists invoices;
create table invoices(
  invoice_number varchar(15) not null,
  creation_date date not null,
  transaction_date date not null,
  payment_date date not null,
  customer int(11) unsigned not null,
  discount int(2) null,
  id int(11) unsigned not null auto_increment,
  primary key(id),
  foreign key(customer) references customers(id));

drop table if exists invoice_products;
create table invoice_products(
  invoice int(11) unsigned not null,
  product int(11) unsigned not null,
  quantity int(3) not null,
  price_no_dph float(9,2) not null,
  id int(11) unsigned not null auto_increment,
  primary key(id),
  foreign key(invoice) references invoices(id),
  foreign key(product) references products(id));


