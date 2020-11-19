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

create table permissions(
  
);



insert into users (email, passwd, role) values ('dariol@agavespirits.eu', '$2y$10$gL9mhjfZtpXfVhjXRNX2GeOlC2iVretqU8WgMX8MUmEc8NlpVZGE.', 'Administrator');
insert into users (email, passwd, role) values ('galfrano@gmail.com', '$2y$10$8qkRTbwzdI/gWHIKwW6gBO1Xr4R.SXZ07aulG87g4f1JNzOHpihbS', 'Administrator');

alter table users add language enum('es', 'en', 'cs') not null default 'es' after role;
alter table outbound add comments text null after qty;


create user 'stock'@'localhost' identified by 'stock';


grant all privileges on stock.* TO 'stock'@'localhost';
/* all sales */

create view sales as select w.id wid, w.name warehouse, p.id pid, p.name product, l.amount sales, i.occurence from warehouses w, products p, invoices i, invoice_lines l where l.invoice=i.id and p.id=l.product and i.warehouse=w.id and LOWER(i.cancelled)!='yes';


/* sales summary */
select w.id, w.name warehouse, p.name product, sum(l.amount) sales from warehouses w, products p, invoices i, invoice_lines l where l.invoice=i.id and p.id=l.product and i.warehouse=w.id group by w.id, p.id;

/* stock */
create view stock as 
select w.id, w.name warehouse, p.name product, sum(b.amount) inbound, sum(s.sales) sales, (sum(b.amount)-sum(s.sales)) stock from warehouses w, products p, inbound b, sales s where b.product=p.id and b.warehouse=w.id and s.wid=b.warehouse and s.pid=b.product group by w.id, b.product;


select w.id, w.name warehouse, p.name product, sum(b.amount) from warehouses w, products p, inbound b where b.product=p.id and b.warehouse=w.id group by w.id, b.product;

select w.id, w.name warehouse, p.name product, sum(b.amount) inbound, sum(s.sales) sales, (sum(b.amount)-sum(s.sales)) stock from warehouses w, products p, inbound b, sales s where b.product=p.id and b.warehouse=w.id and s.wid=b.warehouse and s.pid=b.product group by w.id, b.product;




select w.id, w.name warehouse, p.name product, sum(b.amount) inbound, sum(s.sales) sales, (sum(b.amount)-sum(s.sales)) stock from warehouses w, products p, inbound b left join sales s on (s.wid=b.warehouse and s.pid=b.product) where b.product=p.id and b.warehouse=w.id group by w.id, b.product;




/*
alter table customers add phone_number varchar(127) null after business_name,
  add email varchar(127) null after phone_number,
  add webpage varchar(127) null after email,
  add social_media varchar(127) null after webpage,
  add comments text null after user


delete from invoice_prices; delete from invoice_lines; delete from invoices;


| categories         |
| customers          |
| groups             |
| inbound            |
| invoice_lines      |
| invoice_prices     |
| invoices           |
| outbound           |
| prices             |
| product_relocation |
| products           |
| sales              |
| user_groups        |
| users              |
| warehouses


category id, customer id, invoice, occurrence,
*/
/*
create view sales3 as 
select product, sum(cash) total from (select product, product_id, (amount*price) cash from 
  (select invoices.occurence, users.email as user, customers.name as customer, invoices.payment_method, warehouses.name as warehouse, products.name as product, products.id as product_id, invoice_lines.amount, invoice_prices.price
from invoices
left join invoice_lines on invoices.id=invoice_lines.invoice
join users on users.id = invoices.user
join customers on customers.id=invoices.customer
join warehouses on warehouses.id=invoices.warehouse 
join products on products.id=invoice_lines.product
join invoice_prices on invoice_prices.invoice_line=invoice_lines.id
where invoices.cancelled='No') dt1) dt2 group by product_id;
*/
/*
create view sales2 as 
select invoices.occurence, users.email as user, customers.name as customer, invoices.payment_method, warehouses.name as warehouse, products.name as product, products.id as product_id, invoice_lines.amount, invoice_prices.price
from invoices
left join invoice_lines on invoices.id=invoice_lines.invoice
join users on users.id = invoices.user
join customers on customers.id=invoices.customer
join warehouses on warehouses.id=invoices.warehouse 
join products on products.id=invoice_lines.product
join invoice_prices on invoice_prices.invoice_line=invoice_lines.id
where invoices.cancelled='No';*/

create view sales3 as 
select occurence, product_id, product, amount, (amount*price) cash, warehouse_id  from 
  (select invoices.occurence, invoices.warehouse warehouse_id, users.email as user, customers.name as customer, invoices.payment_method, warehouses.name as warehouse, products.name as product, products.id as product_id, invoice_lines.amount, invoice_prices.price
from invoices
left join invoice_lines on invoices.id=invoice_lines.invoice
join users on users.id = invoices.user
join customers on customers.id=invoices.customer
join warehouses on warehouses.id=invoices.warehouse 
join products on products.id=invoice_lines.product
join invoice_prices on invoice_prices.invoice_line=invoice_lines.id
where invoices.cancelled='No') dt1;
/** /
create view sales2 as select invoices.occurence, invoices.warehouse warehouse_id, users.email as user, customers.name as customer, invoices.payment_method, warehouses.name as warehouse, products.name as product, products.id as product_id, invoice_lines.amount, invoice_prices.price
from invoices
left join invoice_lines on invoices.id=invoice_lines.invoice
join users on users.id = invoices.user
join customers on customers.id=invoices.customer
join warehouses on warehouses.id=invoices.warehouse 
join products on products.id=invoice_lines.product
join invoice_prices on invoice_prices.invoice_line=invoice_lines.id
where invoices.cancelled='No';
create view sales3 as 
select occurence, product_id, product, amount, (amount*price) cash, warehouse_id  from sales2;
/**/



create view stock as
select products.name product, warehouses.name warehouse, warehouses.id warehouse_id, sum(inbound.amount) total from inbound join products on inbound.product=products.id join warehouses on warehouses.id=inbound.warehouse group by inbound.product, inbound.warehouse order by inbound.warehouse;


delimiter $$
create trigger relocation after insert on product_relocation
for each row
  begin
  insert into inbound (user, product, warehouse, amount, unitary_price, occurence)
    (select user, product, from_warehouse, (qty*-1), 0, current_timestamp from product_relocation order by id desc limit 1);
  insert into inbound (user, product, warehouse, amount, unitary_price, occurence)
    (select user, product, to_warehouse, qty, 0, current_timestamp from product_relocation order by id desc limit 1);
end$$;

delimiter $$
create trigger free_bottles after insert on outbound
for each row
  begin
  insert into inbound (user, product, warehouse, amount, unitary_price, occurence)
    (select user, product, warehouse, (qty*-1), 0, current_timestamp from outbound order by id desc limit 1);
end$$;

delimiter $$
create trigger invoice after insert on invoice_lines
for each row
  begin
  insert into invoice_prices (price, invoice_line)
    (select p.price, l.id from invoice_lines l, invoices i, customers c, prices p
      where l.invoice=i.id
      and i.customer=c.id
      and p.product=l.product
      and c.category=p.category
      order by l.id desc limit 1);
  insert into inbound (user, product, warehouse, amount, unitary_price, occurence)
    (select invoices.user, l.product, invoices.warehouse, (l.amount*-1), 0, current_timestamp from invoice_lines l join invoices on invoices.id=l.invoice order by l.id desc limit 1);
end$$;
