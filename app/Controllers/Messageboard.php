<?php namespace App\Controllers;

class Messageboard extends BaseController
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