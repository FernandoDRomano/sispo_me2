<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['dsn']      The full DSN string describe a connection to the database.
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['encrypt']  Whether or not to use an encrypted connection.
|	['compress'] Whether or not to use client compression (MySQL only)
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|	['failover'] array - A array with 0 or more data for connections if the main should fail.
|	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/

$active_group = ENVIRONMENT;
$query_builder = TRUE;

$db['development'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'app_gestionpostal',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => TRUE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => FALSE
);

$db['testing'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'sispoc5_user',
	'password' => 'v#LB+aPk}_84',
	'database' => 'sispoc5_test',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => TRUE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);


$db['production'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'sispoc5_user',
	'password' => 'v#LB+aPk}_84',
	'database' => 'sispoc5_gestionpostal_me',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => FALSE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => TRUE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);



  use Illuminate\Database\Capsule\Manager as Capsule;
  $capsule = new Capsule;

  $capsule->addConnection(array(
      'driver' => in_array($db[ENVIRONMENT]['dbdriver'], array('mysql', 'mysqli')) ? 'mysql' : $db[ENVIRONMENT]['dbdriver'],
      'host' => $db[ENVIRONMENT]['hostname'],
      'database' => $db[ENVIRONMENT]['database'],
      'username' => $db[ENVIRONMENT]['username'],
      'password' => $db[ENVIRONMENT]['password'],
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => $db[ENVIRONMENT]['dbprefix'],
    )
  );

  $capsule->setAsGlobal();
  $capsule->bootEloquent();

  $events = new Illuminate\Events\Dispatcher;
  $events->listen('illuminate.query', function($query, $bindings, $time, $name) {

    // Format binding data for sql insertion

    foreach ($bindings as $i => $binding) {
      if ($binding instanceof \DateTime)  {
        $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
      } else if (is_string($binding)) {
        $bindings[$i] = "'$binding'";
      }
    }

    // Insert bindings into query
    $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
    $query = vsprintf($query, $bindings);

    // Add it into CodeIgniter
    $db =& get_instance()->db;
    $db->query_times[] = $time;
    $db->queries[] = $query;
  });

  $capsule->setEventDispatcher($events);
