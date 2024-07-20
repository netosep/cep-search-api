<?php

namespace App\DTO;

class AddressDTO
{
    public $cep;
    public $logradouro;
    public $complemento;
    public $bairro;
    public $localidade;
    public $uf;
    public $ibge;
    public $gia;
    public $ddd;
    public $siafi;

    public function __construct(
        $cep,
        $logradouro,
        $complemento,
        $bairro,
        $localidade,
        $uf,
        $ibge,
        $gia,
        $ddd,
        $siafi
    ) {
        $this->cep = $cep;
        $this->logradouro = $logradouro;
        $this->complemento = $complemento;
        $this->bairro = $bairro;
        $this->localidade = $localidade;
        $this->uf = $uf;
        $this->ibge = $ibge;
        $this->gia = $gia;
        $this->ddd = $ddd;
        $this->siafi = $siafi;
    }

    public function toArray()
    {
        return [
            'cep' => $this->cep,
            'label' => sprintf("%s, %s", $this->logradouro, $this->localidade),
            'logradouro' => $this->logradouro,
            'complemento' => $this->complemento,
            'bairro' => $this->bairro,
            'localidade' => $this->localidade,
            'uf' => $this->uf,
            'ibge' => $this->ibge,
            'gia' => $this->gia,
            'ddd' => $this->ddd,
            'siafi' => $this->siafi,
        ];
    }
}
