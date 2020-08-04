<?php

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DBHelper');
        $this->load->library('form_validation');
        $this->output->set_content_type('application/json');
    }

    public function getMessage()
    {
        $messages = $this->DBHelper->getList();
        $this->output->set_output(json_encode($messages));
    }

    public function create()
    {
        $this->form_validation->set_rules('name', '', 'required');
        $this->form_validation->set_rules('content', '', 'required');
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $content = $this->input->post('content');
            $id = $this->DBHelper->insert($name, $content);
            $data = $this->DBHelper->getOne($id);
            $this->output->set_output(json_encode((object) [
                'status' => 'success',
                'message' => '新增成功',
                'data' => $data,
            ]));
        } else {
            $this->output->set_output(json_encode((object) [
                'status' => 'failed',
                'message' => validation_errors(),
            ]));
        }
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $result = $this->DBHelper->delete($id);
        if ($result == 1) {
            $this->output->set_output(json_encode((object) [
                'status' => 'success',
                'message' => '成功刪除',
            ]));
        } else {
            $this->output->set_output(json_encode((object) [
                'status' => 'failed',
                'message' => '刪除失敗',
            ]));
        }
    }

    public function getOne()
    {
        $id = $this->input->post('id');
        $message = $this->DBHelper->getOne($id);
        $this->output->set_output(json_encode($message));
    }

    public function update()
    {
        $this->form_validation->set_rules('name', '', 'required');
        $this->form_validation->set_rules('content', '', 'required');
        if ($this->form_validation->run()) {
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $content = $this->input->post('content');
            $result = $this->DBHelper->update($id, $name, $content);
            if ($result == 1) {
                $data = $this->DBHelper->getOne($id);
                $this->output->set_output(json_encode((object) [
                    'status' => 'success',
                    'message' => '修改成功',
                    'data' => $data,
                ]));
            } else {
                $this->output->set_output(json_encode((object) [
                    'status' => 'failed',
                    'message' => '修改失敗',
                ]));
            }
        } else {
            $this->output->set_output(json_encode((object) [
                'status' => 'failed',
                'message' => validation_errors(),
            ]));
        }
    }
}
