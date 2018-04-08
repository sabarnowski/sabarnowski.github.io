<?php

namespace CarriotsDoorForm {

    function IsValidForm($formData)
    {
        return is_array($formData)
            && isset($formData['username']) && $formData['username'] != ''
            && isset($formData['password']) && $formData['password'] != ''
        ;
    }
}

namespace CarriotsDoorApi {

    function getClient()
    {
        return new GuzzleHttpClient(
            [
                'base_uri' => 'https://api.carriots.com',
                'timeout' => 10,
                'headers' => ['carriots.apikey' => getenv('APIKEY_CARRIOTS')],
            ]
        );
    };

    function getApiStream($data)
    {
        return [
            'protocol' => 'v2',
            'at' => 'now',
            'device' => getenv('DEVICE_CARRIOTS'),
            'data' => [
                'operation' => 'open',
                'username' => $data['username'],
                'password' => sha1($data['password']),
                'device' => $data['puerta'].'@'.getenv('CUSTOMER_CARRIOTS'),
            ],
        ];
    }

    function sendData($formData)
    {
        getClient()->post('/streams', [
            'json' => getApiStream($formData),
        ]);
    }
}
