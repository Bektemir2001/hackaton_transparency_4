<?php

namespace App\Services\Organization;

use GuzzleHttp\Client;

class OrganizationQueryService
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getOrganizations(int $page): array
    {
        $response = $this->client->get('https://api.zakupki.kg/api/front/v1/organizations?page='.$page);
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);
            return ['data' => $data['data']];
        } else {
            return [];
        }
    }

    public function getOrganization(int $id): array
    {
        $response = $this->client->get('https://api.zakupki.kg/api/front/v1/organizations/'.$id);
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);
            return ['data' => $data];
        } else {
            return [];
        }
    }
}
