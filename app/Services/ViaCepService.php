<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\DTO\AddressDTO;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ViaCepService
{
    public const VIACEP_URL = 'https://viacep.com.br';

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // verificação de certificado SSL
        ]);
    }

    public function getAddressByCep($cep)
    {
        $cacheKey = 'cep_' . $cep;

        if (Cache::has($cacheKey)) {
            Log::info('Cache hit for CEP: ' . $cep);

            return Cache::get($cacheKey);
        }

        $cacheTTL = now()->addHours(24);

        return Cache::remember($cacheKey, $cacheTTL, function () use ($cep) {
            try {
                $uri = sprintf("%s/ws/%s/json/", self::VIACEP_URL, $cep);
                $response = $this->client->get($uri);
                $data = json_decode($response->getBody(), true);

                if (isset($data['erro'])) {
                    Log::error('Error fetching address data for CEP: ' . $cep, [$data]);

                    return null;
                }

                return new AddressDTO(
                    preg_replace('/\D/', '', $data['cep']),
                    $data['logradouro'],
                    $data['complemento'],
                    $data['bairro'],
                    $data['localidade'],
                    $data['uf'],
                    $data['ibge'],
                    $data['gia'],
                    $data['ddd'],
                    $data['siafi']
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());

                dd($e->getMessage());

                return null;
            }
        });
    }
}
