<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Api\CepController;
use App\Services\ViaCepService;
use Illuminate\Http\Response;
use Tests\TestCase;

class CepControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new CepController(new ViaCepService());
    }

    public function testSearchInvalidCepFormat()
    {
        $invalidCep = '123456789';
        $response = $this->controller->search($invalidCep);

        $expected = [
            [
                'cep' => '123456789',
                'error' => 'Invalid CEP format',
            ],
        ];

        $this->assertEquals(array_reverse($expected), $response->getData(true));
    }

    public function testSearchInvalidCepFormatMultipleCeps()
    {
        $invalidCeps = '123456789, 987654321';
        $response = $this->controller->search($invalidCeps);

        $expected = [
            [
                'cep' => '123456789',
                'error' => 'Invalid CEP format',
            ],
            [
                'cep' => '987654321',
                'error' => 'Invalid CEP format',
            ],
        ];

        $this->assertEquals(array_reverse($expected), $response->getData(true));
    }

    public function testSearchInvalidCepFormatWithValidCep()
    {
        $validCep = '12345678';
        $invalidCep = '123456789';
        $response = $this->controller->search($validCep . ',' . $invalidCep);

        $expected = [
            [
                'cep' => '12345678',
                'error' => 'Address not found',
            ],
            [
                'cep' => '123456789',
                'error' => 'Invalid CEP format',
            ],
        ];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(array_reverse($expected), $response->getData(true));
    }

    public function testSearchInvalidCepFormatWithValidCepAndMultipleInvalidCeps()
    {
        $validCep = '17560-246';
        $invalidCeps = '123456789,987654321';
        $response = $this->controller->search($validCep . ',' . $invalidCeps);

        $expected = [
            [
                'cep' => '17560246',
                'label' => 'Avenida Paulista, Vera Cruz',
                'logradouro' => 'Avenida Paulista',
                'complemento' => 'de 1600/1601 a 1698/1699',
                'bairro' => 'CECAP',
                'localidade' => 'Vera Cruz',
                'uf' => 'SP',
                'ibge' => '3556602',
                'gia' => '7134',
                'ddd' => '14',
                'siafi' => '7235'
            ],
            [
                'cep' => '123456789',
                'error' => 'Invalid CEP format',
            ],
            [
                'cep' => '987654321',
                'error' => 'Invalid CEP format',
            ],
        ];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(array_reverse($expected), $response->getData(true));
    }

    public function testSearchInvalidCepFormatWithMultipleInvalidCepsAndValidCep()
    {
        $validCep = '01001000';
        $invalidCeps = '123456789, 987654321';
        $response = $this->controller->search($invalidCeps . ',' . $validCep);

        $expected = [
            [
                'cep' => '123456789',
                'error' => 'Invalid CEP format',
            ],
            [
                'cep' => '987654321',
                'error' => 'Invalid CEP format',
            ],
            [
                'cep' => '01001000',
                'label' => 'Praça da Sé, São Paulo',
                'logradouro' => 'Praça da Sé',
                'complemento' => 'lado ímpar',
                'bairro' => 'Sé',
                'localidade' => 'São Paulo',
                'uf' => 'SP',
                'ibge' => '3550308',
                'gia' => '1004',
                'ddd' => '11',
                'siafi' => '7107'
            ],
        ];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(array_reverse($expected), $response->getData(true));
    }

    public function testProcessCepListHandlesEmptyCepCorrectly()
    {
        $cepList = ['12345678', '', '98765432'];
        $result = $this->controller->processCepList($cepList);

        $this->assertCount(2, $result);
        $this->assertEquals(['cep' => '12345678', 'error' => 'Address not found'], $result[0]);
        $this->assertEquals(['cep' => '98765432', 'error' => 'Address not found'], $result[1]);
    }
}
