<?php
    /**
     * Extend this class to your Models
     * All models must have property $table = '<table name>';
     */
    namespace Models;

    use \DB\DB;
    abstract class CRUD extends DB {
        public static function create( $data_arr = []) {
            if( empty( $data_arr) || ! is_array( $data_arr)) return 'missing data';
            
            // Prepare values
            $data_values = array_values( $data_arr);
            $values = '';
            
            foreach( $data_values as $i => $value) {
                if( $value !== null) {
                    if( is_string( $value)) {
                        $values .= '"'. $data_values[ $i] .'",';
                    } else {
                        $values .= $data_values[ $i] .',';
                    }
                }
            }

            // Prepare query
            $columns = implode(',', array_keys( $data_arr));
            $values  = trim( $values, ',');

            // Execute
            return DB::execute_and_return_row_id("INSERT INTO ". static::$table ." (". $columns .") VALUES (". $values .")");
        }

        public static function getRows( $data_arr = []) {
            if( empty( $data_arr) || ! is_array( $data_arr)) return 'missing data';
            $where  = ""; $limit = "";

            // Paging
            if( isset( $data_arr['limit'])) {
                $limit = 'LIMIT '. $data_arr['limit'];
                unset( $data_arr['limit']);
            }

            if( ! empty( $data_arr)) $where .= " WHERE ". DB::buildWhere( $data_arr);
            return DB::rawQuery("SELECT * FROM ". static::$table ." $where ORDER BY id $limit");
        }

        public static function getRowById( $id) {
            if( empty( $id) || intval( $id) <= 0) return 'bad id';
            return DB::rawQuery("SELECT * FROM ". static::$table ." WHERE id = $id LIMIT 1");
        }

        public static function update( $id, $data_arr = []) {
            if( empty( $data_arr) || ! is_array( $data_arr)) return 'missing data';

            // Accepting arrays only
            if( empty( $data_arr) || ! is_array( $data_arr)) return 'missing data';
            // Last update time
            if( ! isset( $data_arr['last_update'])) $data_arr['last_update'] = date('Y-m-d H:i:s');
            
            // Prepare values
            $data_values = array_values( $data_arr);
            $data_keys   = array_keys( $data_arr);

            // Prepare query
            $i = 0; $set = "";

            foreach( $data_keys as $col) {
                $set .= "$col = \"". $data_values[ $i] .'"';
                if( $i < count( $data_keys)) $set .= ',';
                $i++;
            }
            $set = rtrim( $set, ',');

            // Execute
            return DB::execute("UPDATE ". static::$table ." SET $set WHERE id = $id");
        }

        public static function delete( $id) {
            if( empty( $id) || intval( $id) <= 0) return 'bad id';
            return DB::execute("DELETE FROM ". static::$table ." WHERE id = $id");
        }
    }
