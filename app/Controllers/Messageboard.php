<?php namespace App\Controllers;

use App\Models\MessageModel;
use CodeIgniter\Controller;

class Messageboard extends Controller
{

    /**
     * 留言板主畫面
     */
    public function index()
    {
        echo view('header');
        return view('message/messageboard');
    }
}