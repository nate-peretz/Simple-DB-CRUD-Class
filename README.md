# Base DB/CRUD class
Base DB class extends CRUD (Using PDO). I'm intentionally using a data array. I find it more organized and easier to read. This means, SQL injection should be prevented before submitting the data.

```php
use \DB\DB;

// DB Settings
$host     = 'localhost';
$charset  = 'utf8';
$port     = 3306;
$dbname   = 'db_name';
$user     = 'db_user';
$password = 'db_pass';

// Test DB Connection
$db_connection = DB::ConnectDB( $host, $dbname, $user, $password, $charset, $port);
if( $db_connection !== 'connected') {
    echo '<pre>'; var_dump( $db_connection); die();
}
```
