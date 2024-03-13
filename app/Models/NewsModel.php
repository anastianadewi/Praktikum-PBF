<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Config\Factories;

class NewsModel extends Model
{
    protected $table = 'news';
    protected $allowedFields = ['title', 'slug', 'body'];
    public function getNews($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }

        return $this->where(['title' => $id])->first();
    }

    public function someFunction()
    {
        $users = Factories::models('UserModel');

        // ...
    }
}