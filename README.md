# goal
Actively developed, this was my attempt to handle relational databases and forms in an abstract way.

While most functionality is generic, this particular implementation (i.e. db and few custom files), is about a simple stock and invoicing system.

From commit on 2020-01-16 set up should be relatively easy: only editing public/index.php, Configuration/Constants.php along with setting up apache or nginx (example files on root) are the requirements.

#features

- Table relationships detection based on information_schema.
- Object based HTML templating system with tree access functionality and raw file consumption.
- CRUD functionality with automatic inclusion of catalogs (parents) and optional children.
- User system based in limited granularity
- Multilanguage capabilities.
