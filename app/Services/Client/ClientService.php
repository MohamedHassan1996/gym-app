<?php

namespace App\Services\Client;

use App\Filters\Client\FilterClient;
use App\Models\Client\Client;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ClientService{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function allClients()
    {
        $clients = QueryBuilder::for(Client::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterClient()), // Add a custom search filter
            ])
            ->with('user')
            ->get();

        return $clients;

    }

    public function createClient(array $clientData): Client
    {

        $client = $this->client::create([
            'description' => $clientData['description'],
            'date_of_birth' => $clientData['dateOfBirth'],
            'gender' => $clientData['gender'],
            'user_id' => $clientData['userId'],
        ]);

        return $client;

    }

    public function editClient(int $clientId)
    {
        return $this->client::with('user')->find($clientId);
    }

    public function updateClient(array $clientData): Client
    {


        $client = $this->client::find($clientData['clientId']);

        $client->description = $clientData['description'];
        $client->date_of_birth = $clientData['dateOfBirth'];
        $client->gender = $clientData['gender'];
        $client->user_id = $clientData['userId'];
        $client->save();

        return $client;

    }


    public function deleteClient(int $clientId)
    {

        $client = $this->client::find($clientId);

        if($client->user){
            $client->user->delete();
        }

        $client->delete();

    }


}
