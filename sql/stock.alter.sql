alter table users add language enum('es', 'en', 'cs') not null default 'es' after role;
alter table outbound add comments text null after qty;
