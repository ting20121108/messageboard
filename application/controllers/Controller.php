<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Controller extends CI_Controller
{

    /**
     * 載入 Model 和 url 的輔助函式
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DBHelper');
        $this->load->helper('url');
    }

    /**
     * 留言板主畫面
     */
    public function index()
    {
        $this->load->view('messageboard');
    }
}
