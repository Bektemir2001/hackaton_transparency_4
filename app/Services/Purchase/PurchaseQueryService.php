<?php

namespace App\Services\Purchase;

use GuzzleHttp\Client;

class PurchaseQueryService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getPurchases(int $page): array
    {
        $response = $this->client->get('https://api.zakupki.kg/api/front/v1/purchases?page='.$page);
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);
            return ['data' => $data['data']];
        } else {
            return [];
        }
    }

    public function getPurchase(int $id): array
    {
        try{
            $response = $this->client->get('https://api.zakupki.kg/api/front/v1/purchases/competitive/'.$id);
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return ['data' => $data];
            } else {
                return [];
            }
        }
        catch (\Exception $e)
        {
            if(strpos($e->getMessage(), '404') != false){
                return ['error' => 404];
            }
            else{
                return [];
            }
        }

    }
}
