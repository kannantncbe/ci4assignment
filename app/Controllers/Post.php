<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Firebase\JWT\JWT;
use CodeIgniter\HTTP\IncomingRequest;
use Exception;

class Post extends BaseController
{
    /**
     * Get all Posts
     * @return Response
     */
    public function index()
    {
        $model = new PostModel();
        return $this->getResponse(
                $model->select("userId, id, title, body")->findAll()
        );
    }

    /**
     * Create a new Client
     */
    public function store()
    {
        $rules = [
            'title' => 'required',
            'body' => 'required'
        ];

        $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $srequest = service('request');
        $authenticationHeader = $srequest->getServer('HTTP_AUTHORIZATION');
        helper('jwt');
        $encodedToken = getJWTFromRequest($authenticationHeader);
        $key = Services::getSecretKey();
        $decodedToken = JWT::decode($encodedToken, $key, ['HS256']);
        $userModel = new UserModel();
        $uinfo = $userModel->findUserByEmailAddress($decodedToken->email);

        $model = new PostModel();
        $newPost = array();
        $newPost['userId'] = $uinfo['id'];
        $newPost['title'] = $input['title'];
        $newPost['body'] = $input['body'];
        $model->save($newPost);

        $post = $model->where('userId', $uinfo['id'])->first();

        return $this->getResponse(
            [
                'message' => 'Post added successfully',
                'post' => $post
            ]
        );
    }

    /**
     * Get a single client by ID
     */
    public function show($id)
    {
        try {

            $model = new PostModel();
            $post = $model->findClientById($id);

            return $this->getResponse(
                [
                    'message' => 'Post retrieved successfully',
                    'post' => $post
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find post for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
}
