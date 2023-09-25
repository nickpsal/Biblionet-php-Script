<?php
namespace biblionetApp\Core;
use Exception; // Import the global Exception class
trait Model
{
    use Database;
    public $errors = [];

    public function find_all()
    {
        $query = "SELECT * FROM $this->db_table ORDER BY $this->order_col $this->order_type limit $this->limit offset $this->offset";
        $result =  $this->query($query);
        if (!$result) {
            return false;
        }else {
            return $result;
        }
    }

    public function checkifTableexists()
    {
        try {
            $query = "SHOW TABLES LIKE '$this->db_table'";
            $result = $this->query($query);
            if (!empty($result)){
                return true;
            }
            return false;
        }catch (Exception $exp) {
            return false;
        }
    }

    public function getLastDate()
    {
        $query = "SELECT * FROM $this->db_table ORDER BY $this->order_col $this->order_type limit 1";
        return $this->query($query);
    }

    public function getMax($column_name)
    {
        $query = "SELECT MAX($column_name) AS max_value FROM $this->db_table;";
        return $this->query($query);
    }

    public function where($data, $data_not  =  [])
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT * FROM $this->db_table  where ";
        foreach ($keys as $key) {
            $query .= $key . " = :" . $key .  " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . "  != :" . $key .  " && ";
        }
        $query = trim($query, " && ");
        $query .= " order by $this->order_col $this->order_type limit $this->limit offset $this->offset";
        $data = array_merge($data, $data_not);
        $result =  $this->query($query, $data);
        if (!$result) {
            return false;
        }else {
            return $result;
        }
    }

    public function get_first_from_db($data, $data_not  =  [])
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "SELECT * FROM $this->db_table  where ";
        foreach ($keys as $key) {
            $query .= $key . " = :" . $key .  " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . "  != :" . $key .  " && ";
        }
        $query = trim($query, " && ");
        $query .= " limit $this->limit offset $this->offset";
        $data = array_merge($data, $data_not);
        $result =  $this->query($query, $data);
        if ($result) {
            return $result[0];
        }
        return false;
    }

    public function createTable()
    {
        if (!empty($this->insertAllowedColumns)) {
            $columns = [];
            foreach ($this->insertAllowedColumns as $column => $type) {
                $columns[] = "$column $type";
            }
            // Combine column definitions into the SQL statement
            $columns_sql = implode(", ", $columns);
            // Define the SQL statement for table creation
            $query = "CREATE TABLE IF NOT EXISTS $this->db_table ( $columns_sql )";
            $result = $this->query($query);
            return true;
        } else {
            return false;
        }
    }

    public function insert($data)
    {
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }
        $keys = array_keys($data);
        $query = "INSERT IGNORE INTO $this->db_table (" . implode(",", $keys) . ") VALUES (:" . implode(",:", $keys) . ") ";
        $res = $this->query($query, $data);
        if ($res != false) {
            return true;
        }
        return false;
    }

    public function update($user_id, $data, $id_column = 'id')
    {
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }
        $keys = array_keys($data);
        $query = "UPDATE $this->db_table SET ";
        foreach ($keys as $key) {
            $query .= $key . " = :" . $key .  ", ";
        }
        $query = trim($query, ", ");
        $data[$id_column] = $user_id;
        $query .= " where $id_column = :$id_column";
        $result = $this->query($query, $data);
        if (!$result) {
            return false;
        }else {
            return true;
        }
    }

    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "DELETE FROM $this->db_table where $id_column = :$id_column";
        $this->query($query, $data);
        return false;
    }
}
