<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 資料庫相關類別
 */
class DBHelper extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 取得資料表中符合欄位的資料
     *
     * @return array
     */
    public function getList()
    {
        $this->db->select('id, name, content, time');
        $result = $this->db->get('message_board');

        return $result->result();
    }

    /**
     * 新增一筆留言
     *
     * @param string $name 留言暱稱
     * @param string $content 留言內容
     *
     * @return int
     */
    public function insert($name, $content)
    {
        $data = [
            'name' => $name,
            'content' => $content
        ];

        $this->db->insert('message_board', $data);
        return $this->db->insert_id();
    }

    /**
     * 刪除一筆留言
     *
     * @param int $id 流水號
     *
     * @return int
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('message_board');
        return $this->db->affected_rows();
    }

    /**
     * 查詢一筆資料提供編輯
     *
     * @param int $id 流水號
     *
     * @return object
     */
    public function getOne($id)
    {
        $this->db->select('id, name, content, time');
        $this->db->where('id', $id);
        $result = $this->db->get('message_board');
        return $result->result();
    }

    /**
     * 更新資料
     *
     * @param int $id 流水號
     * @param string $name 留言暱稱
     * @param string $content 留言內容
     *
     * @return int
     */
    public function update($id, $name, $content)
    {
        $data = [
            'name' => $name,
            'content' => $content
        ];

        $this->db->where('id', $id);
        $this->db->update('message_board', $data);
        return $this->db->affected_rows();
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
