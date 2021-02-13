<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class PostModel extends Model
{
    protected $table = 'post';
    protected $allowedFields = [
        'userId',
        'title',
        'body'
    ];
    protected $updatedField = 'updated_at';

    public function findPostById($id)
    {
        $post = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$post) throw new Exception('Could not find post for specified ID');

        return $post;
    }
}