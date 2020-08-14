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
        $messages = $MessageModel->getList();
        return $this->response->setJSON(json_encode($messages));
    }

    public function create()
    {
        $MessageModel = new MessageModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'content' => $this->request->getPost('content')
        ];

        $id = $MessageModel->create($data);
        if($id != 0){
            $data = $MessageModel->getOne($id);
            return $this->response->setJSON(json_encode((object) [
                'status' => 'success',
                'message' => '新增成功',
                'data' => $data,
            ]));
        }else{
            return $this->response->setJSON(json_encode((object) [
                'status' => 'failed',
                'message' => '新增失敗',
            ]));
        }
    }

    public function delete()
    {
        $MessageModel = new MessageModel();
        $id = $this->request->getPost('id');
        $result = $MessageModel->destroy($id);
        if ($result == 1) {
            return $this->response->setJSON(json_encode((object) [
                'status' => 'success',
                'message' => '成功刪除',
            ]));
        } else {
            return $this->response->setJSON(json_encode((object) [
                'status' => 'failed',
                'message' => '刪除失敗',
            ]));
        }
    }

    public function getOne()
    {
        $MessageModel = new MessageModel();
        $id = $this->request->getPost('id');
        $message = $MessageModel->getOne($id);
        return $this->response->setJSON(json_encode($message));
    }

    public function update()
    {
        $MessageModel = new MessageModel();
        $id = $this->request->getPost('id');
        $data = [
            'name' => $this->request->getPost('name'),
            'content' => $this->request->getPost('content'),
        ];
        $result = $MessageModel->renew($id, $data);
            if ($result == 1) {
                $data = $MessageModel->getOne($id);
                return $this->response->setJSON(json_encode((object) [
                    'status' => 'success',
                    'message' => '修改成功',
                    'data' => $data,
                ]));
            } else {
                return $this->response->setJSON(json_encode((object) [
                    'status' => 'failed',
                    'message' => '修改失敗',
                ]));
            }
    }
}
