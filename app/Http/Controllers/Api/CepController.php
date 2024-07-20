<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Services\ViaCepService;
use Illuminate\Http\Response;

class CepController extends BaseController
{
    public const MAX_CEPS_ALLOWED = 20;

    protected $viaCepService;

    public function __construct(ViaCepService $viaCepService)
    {
        $this->viaCepService = $viaCepService;
    }

    public function search(mixed $ceps = null)
    {
        $cepList = explode(',', $ceps);

        if (count($cepList) > self::MAX_CEPS_ALLOWED) {
            return response()->json([
                'error' => sprintf('Max %s CEPs allowed', self::MAX_CEPS_ALLOWED),
            ], Response::HTTP_BAD_REQUEST);
        }

        $cepList = array_map(fn ($cep) => preg_replace('/\D/', '', $cep), $cepList);

        if (!$ceps || empty($cepList)) {
            return response()->json([
                'error' => 'use /00000000 or /00000000,00000-000,...',
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(array_reverse($this->processCepList($cepList)));
    }

    public function processCepList(array $cepList): array
    {
        $addresses = [];
        foreach ($cepList as $cep) {
            if (empty($cep)) {
                continue;
            }

            if (!preg_match('/^\d{8}$/', $cep)) {
                $addresses[] = [
                    'cep' => $cep,
                    'error' => 'Invalid CEP format',
                ];

                continue;
            }

            $address = $this->viaCepService->getAddressByCep($cep);

            if (!$address) {
                $addresses[] = [
                    'cep' => $cep,
                    'error' => 'Address not found',
                ];

                continue;
            }

            $addresses[] = $address->toArray();
        }

        return $addresses;
    }
}
