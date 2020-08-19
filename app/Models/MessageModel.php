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
}