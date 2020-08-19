<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MessageModel;
use CodeIgniter\API\ResponseTrait;

class Api extends Controller
{
    use ResponseTrait;

    public function getMessage()
    {
        $MessageModel = new MessageModel();
        $messages = $MessageModel->findAll();
        return $this->response->setJSON(json_encode($messages));
    }

    public function create()
    {
        helper('form');
        $MessageModel = new MessageModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'content' => $this->request->getPost('content')
        ];

        if (!$this->validate([
            'name' => 'required',
            'content'  => 'required',
        ])) {
            return $this->response->setJSON( [
                'status' => 'failed',
                'message' => '錯誤！暱稱或內容不可空白',
            ]);
        } else {
            $MessageModel->insert($data);
            $id = $MessageModel->insertID();
            if ($id != 0) {
                $data = $MessageModel->find($id);
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => '新增成功',
                    'data' => $data,
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'failed',
                    'message' => '新增失敗',
                ]);
            }
        }
    }

    public function delete()
    {
        $MessageModel = new MessageModel();
        $id = $this->request->getPost('id');
        $MessageModel->delete($id);
        $result = $MessageModel->affectedRows();
        if ($result == 1) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '成功刪除',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'failed',
                'message' => '刪除失敗',
            ]);
        }
    }

    public function getOne()
    {
        $MessageModel = new MessageModel();
        $id = $this->request->getPost('id');
        $message = $MessageModel->find($id);
        return $this->response->setJSON(json_encode($message));
    }

    public function update()
    {
        helper('form');
        $MessageModel = new MessageModel();
        $id = $this->request->getPost('id');
        $data = [
            'name' => $this->request->getPost('name'),
            'content' => $this->request->getPost('content'),
        ];

        if (!$this->validate([
            'name' => 'required',
            'content'  => 'required'
        ])) {
            return $this->response->setJSON([
                'status' => 'failed',
                'message' => '錯誤！暱稱或內容不可空白',
            ]);
        } else {
            $MessageModel->update($id, $data);
            $result = $MessageModel->affectedRows();
            if ($result == 1) {
                $data = $MessageModel->find($id);
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => '修改成功',
                    'data' => $data,
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'failed',
                    'message' => '修改失敗',
                ]);
            }
        }
    }
}
