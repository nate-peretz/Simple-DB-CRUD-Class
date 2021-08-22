<?php
    namespace DB;

    class DB {
        private static $host;
        private static $dbname;
        private static $user;
        private static $password;
        private static $charset;
        private static $port;
        private static $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        private static $pdo;
        
        public static function ConnectDB($host, $dbname, $user, $password, $charset, $port) {
            // Singleton
            if(self::$pdo instanceof \PDO) return 'connected';

            // "Construct"
            self::$host     = $host;
            self::$dbname   = $dbname;
            self::$user     = $user;
            self::$password = $password;
            self::$charset  = $charset;
            self::$port     = $port;

            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset;port=$port";

            try {
                self::$pdo = new \PDO($dsn, $user, $password, self::$options);
                if( ! empty(self::$pdo)) {
                    return 'connected';
                } else {
                    return 'failed';
                }
            } catch (\PDOException $e) {
                return new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        public static function rawQuery($query) {
            try {
                $query = self::$pdo->query($query);
                return $query->fetchAll();
            } catch (\PDOException $e) {
                return new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        public static function buildWhere($data) {
            $output = '';
    
            $i = 0;
            foreach( $data as $key => $value) {
                if( $i == count( $data) -1) {
                    $output .= $key .' = "'. $value .'"';
                } else {
                    $output .= $key .' = "'. $value .'" AND ';
                }
                $i++;
            }
    
            return $output;
        }

        public static function execute($query) {
            return self::$pdo->prepare($query)->execute();
        }

        public static function execute_and_return_row_id($query) {
            self::$pdo->prepare($query)->execute();
            $last_id = self::$pdo->lastInsertId();
            return $last_id;
        }

        public static function backupQuery() {
            self::$pdo->beginTransaction();
        }

        public static function rollbackQuery() {
            self::$pdo->rollBack();
        }

        public static function commitQuery() {
            self::$pdo->commit();
        }
    }
