<?php namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{

    protected $table = 'message_board';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'content'];
    protected $validationRules = [
        'name' => 'required',
        'content' => 'required',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => '暱稱不可以空白'
        ],
        'content' => [
            'required' => '內容不可以空白'
        ],
    ];

    /**
     * 取得資料表中符合欄位的資料
     *
     * @return array
     */
    public function getList()
    {
        return $this->findAll();
    }

    /**
     * 新增一筆留言
     *
     * @param array $data
     *
     * @return int
     */
    public function create($data)
    {
        $this->insert($data);
        return $this->insertID();
    }

    /**
     * 刪除一筆留言
     *
     * @param int $id
     *
     * @return int
     */
    public function destroy($id)
    {
        $this->delete($id);
        return $this->affectedRows();
    }

    /**
     * 查詢一筆資料提供編輯
     *
     * @param int $id
     *
     * @return object
     */
    public function getOne($id)
    {
        return $this->find($id);
    }

    /**
     * 更新資料
     *
     * @param int $id
     * @param array $data
     *
     * @return int
     */
    public function renew($id, $data)
    {
        $this->update($id, $data);
        return $this->affectedRows();
    }
}