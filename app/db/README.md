JSONDatabase class written by Mitchell Urgero <info@urgero.org>
===============================================================
- GitHub: https://github.com/mitchellurgero/jsondatabase


### This DB class supports the following functions:

- init("DATABASE_NAME", "DATABASE_LOCATION" = null); //Load or create new database, then select it. (Optionally give a location to store the databse)
- insert("TABLE_NAME", '{"data":"in","JSON":"format"}', int = null);//Insert or add a new row into given table (Optional 3rd option: replace given row number) 
- select("TABLE_NAME", "WHERE" = null, "EQUALS" = null);//get data from selected row
- create_table("TABLE_NAME");//Create a new table with the given name.
- delete_table("TABLE_NAME");//Delete the given table.
- dump_tables();//Dump all tables AND their data (Mostly for backup purposes.)
- check_table("TABLE_NAME");//Check if a table exists. Returns number of rows if table exists.
- list_tables();//List all available tables in selected database.
- import("JSON_STRING_OF_DB_BACKUP");//import a database backup and restore into the given database.

Things to note:
===============
- row_id is the row number. This is not written to the db BUT generated on the fly. and always exists as a part of the row data
- dump_tables() will always take a little bit of time because it dumps ALL THE TABLES TO AN ARRAY.

How To Use
==========
include('db.php');

$db = new JSONDatabase("DATABASE_NAME");

$db->functionName(options);


Install 
=========

Either git clone this repository, or use `composer require mitchellurgero/jsondatabase` to install via composer.
