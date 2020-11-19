create table products(
  name varchar(127) not null,
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;

create table categories(
  name varchar(127) not null,
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;


create table customers(
  name varchar(127) not null,
  business_name varchar(127) null,
  phone_number varchar(127) null,
  email varchar(127) null,
  webpage varchar(127) null,
  social_media varchar(127) null,
  address text,
  dic varchar(127) null,
  ic varchar(127) null,
  category int(11) not null,
    foreign key(category) references categories(id),
  user int(11) not null,
    foreign key(user) references users(id),
  comments text null,
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;

create table prices(
  product int(11) not null,
    foreign key(product) references products(id),
  category int(11) not null,
    foreign key(category) references categories(id),
    unique (category, product),
  price float(7,2) not null,
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;

create table warehouses(
  name varchar(127) not null,
    id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;


create table inbound(
  user int(11) not null,
    foreign key(user) references users(id),
  product int(11) not null,
    foreign key(product) references products(id),
  warehouse int(11) not null,
   foreign key(warehouse) references warehouses(id),
  amount int(6) not null,
  occurence date null,
  unitary_price float(7,2) null,
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;



create table invoices(
  occurence timestamp not null default current_timestamp,
  user int(11) not null,
    foreign key(user) references users(id),
  customer int(11) not null,
    foreign key(customer) references customers(id),
  payment_method enum('cash', 'transfer') not null,
  paid enum('yes', 'no') not null,
  cancelled enum('no', 'yes') not null default 'no',
  warehouse int(11) not null,
   foreign key(warehouse) references warehouses(id),
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;


create table invoice_lines(
  invoice int(11) not null,
    foreign key(invoice) references invoices(id),
  product int(11) not null,
    foreign key(product) references products(id),
  amount int(4) not null default 1,
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;

create table  invoice_prices(
  price float(7,2) null,
  invoice_line int(11) not null,
    foreign key(invoice_line) references invoice_lines(id),
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;



create table product_relocation(
  from_warehouse int(11) not null,
    foreign key(from_warehouse) references warehouses(id),
  to_warehouse int(11) not null,
    foreign key(to_warehouse) references warehouses(id),
  product int(11) not null,
    foreign key(product) references products(id),
  qty int(11) not null,
  user int(11) not null,
    foreign key(user) references users(id),
  occurrence timestamp not null default current_timestamp,
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;

create table outbound(
  warehouse int(11) not null,
    foreign key(warehouse) references warehouses(id),
  product int(11) not null,
    foreign key(product) references products(id),
  qty int(11) not null,
  user int(11) not null,
    foreign key(user) references users(id),
  occurrence timestamp not null default current_timestamp,
  id int(11) not null auto_increment,
    primary key(id)
) engine=innodb default charset=utf8;

CREATE TABLE `users` (
  `email` varchar(31) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `role` enum('sales_rep','admin') NOT NULL DEFAULT 'sales_rep',
  `language` enum('es','en','cs') NOT NULL DEFAULT 'es',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

create table paths(
  
);
