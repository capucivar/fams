<?php

/**
 * 数据库基础操作
 */
class DBUtil {

    /**
     * 执行插入
     *
     * @param $db
     * @param $sql
     * @param $param
     * @return int 返回受影响的行数
     */
    public static function insertBySqlWithParam($db, $sql, $param) {
        $db->query($sql, $param);
        return $db->affected_rows();
    }

    /**
     * 获取总数
     *
     * @param $db
     * @param $sqlCount
     * @return int
     */
    public static function readCount($db, $sqlCount) {
        $query = $db->query($sqlCount);
        $data  = 0;
        $row   = $query->row_array();
        foreach ($row as $col) {
            $data = $col;
            break;
        }
        return $data;
    }

    /**
     * 判断是否存在
     *
     * @param $db  数据库
     * @param $sql 待执行的sql语句
     * @return bool 存在返回true，不存在返回false
     */
    public static function isExist($db, $sql) {
        $query = $db->query($sql);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取结果集中「第一行中第一列」的内容
     *
     * @param $db  数据库
     * @param $sql 待执行的sql语句
     * @return string 第一行第一列的内容，如果没有则返回null
     */
    public static function getFirstOne($db, $sql) {
        $query = $db->query($sql);
        if ($query->num_rows() > 0) {
            log_message('error', $sql);
            foreach ($query->result_array() as $row) {
                foreach ($row as $key => $value) {
                    log_message('error', $sql);
                    log_message('error', $key);
                    log_message('error', $value);
                    log_message('error', $row[$key]);
                    return $value;
                }
            }

            log_message('error', "0 data got: ");
        } else {
            log_message('error', "null data got: ");
            return null;
        }
    }

    /**
     * 获取结果集中「第一行」的内容
     *
     * @param $db  数据库
     * @param $sql 待执行的sql语句
     * @return array 结果数组，如果没有内容，返回null
     */
    public static function getFirstRow($db, $sql) {
        $query = $db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                return $row;
            }
        } else {
            return null;
        }
    }

    /**
     * 获取结果集，保存在数组中
     *
     * @param $db  数据库
     * @param $sql 待执行的sql语句
     * @return array 结果二维数组，如果没有内容，返回null
     */
    public static function getRows($db, $sql) {
        $query = $db->query($sql);
        if ($query->num_rows() > 0) {
            $query->result_array();
        } else {
            return null;
        }
    }
}