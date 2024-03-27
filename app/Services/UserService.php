<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;


class UserService
{

    private $client;

    public function __construct()
    {
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://61f07509732d93001778ea7d.mockapi.io',
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);
        ;
    }

    public function getUsers($page = 1, $limit = 10)
    {
        try {
            $res = $this->client->get(
                "/api/v1/user/users?page=$page&limit=$limit"
            )->getBody()->getContents();

            $users = json_decode($res);

            return $users;
        } catch (Exception $exception) {
            dd($exception);
        }
    }

}