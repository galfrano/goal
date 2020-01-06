create table products(
  name varchar(127) not null,
  id int(11) not null auto_increment,
    primary key(id)
);

create table categories(
  name varchar(127) not null,
  id int(11) not null auto_increment,
    primary key(id)
);

create table users(
  email varchar(31) not null,
    unique key(email),
  passwd varchar(255) not null,
  role enum('Sales Representative', 'Administrator') not null default 'Sales Representative',
  id int(11) not null auto_increment,
    primary key(id)
);

create table customers(
  name varchar(127) not null,
  address text,
  dic varchar(127) null,
  ic varchar(127) null,
  category int(11) not null,
    foreign key(category) references categories(id),
  user int(11) not null,
    foreign key(user) references users(id),
  id int(11) not null auto_increment,
    primary key(id)
);

create table prices(
  product int(11) not null,
    foreign key(product) references products(id),
  category int(11) not null,
    foreign key(category) references categories(id),
  price float(7,2) not null,
  id int(11) not null auto_increment,
    primary key(id)
);

create table warehouses(
  name varchar(127) not null,
    id int(11) not null auto_increment,
    primary key(id)
);


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
);



create table invoices(
  occurence timestamp not null default current_timestamp,
  user int(11) not null,
    foreign key(user) references users(id),
  customer int(11) not null,
    foreign key(customer) references customers(id),
  warehouse int(11) not null,
   foreign key(warehouse) references warehouses(id),
  id int(11) not null auto_increment,
    primary key(id)
);


create table invoice_lines(
  invoice int(11) not null,
    foreign key(invoice) references invoices(id),
  product int(11) not null,
    foreign key(product) references products(id),
  id int(11) not null auto_increment,
    primary key(id)
);

create table  invoice_prices(
  price float(7,2) null,
  invoice_line int(11) not null,
    foreign key(invoice_line) references invoice_lines(id),
  id int(11) not null auto_increment,
    primary key(id)
);



create trigger invoice after insert on invoice_lines
for each row
  insert into invoice_prices (price, invoice_line)
    (select p.price, l.id from invoice_lines l, invoices i, customers c, prices p
      where l.invoice=i.invoice
      and i.customer=c.id
      and p.product=l.product
      and c.category=p.category
      order by l.id desc limit 1);

create user 'stock'@'localhost' identified by 'stock';
grant all privileges on stock.* TO 'stock'@'localhost';


insert into users (email, passwd, role) values ('galfrano@gmail.com', '$2y$10$8qkRTbwzdI/gWHIKwW6gBO1Xr4R.SXZ07aulG87g4f1JNzOHpihbS', 'Administrator');

