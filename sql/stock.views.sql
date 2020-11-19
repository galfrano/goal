create user 'stock'@'localhost' identified by 'stock';


grant all privileges on stock.* TO 'stock'@'localhost';


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
