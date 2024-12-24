<?php

namespace App\Services;

use App\Models\BankAccount;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class NordigenService
{
    private $baseUrl;
    private $secretId;
    private $secretKey;
    private $token;

    public function __construct()
    {
        $this->baseUrl = config('services.nordigen.url');
        $this->secretId = config('services.nordigen.secret_id');
        $this->secretKey = config('services.nordigen.secret_key');
    }

    private function getToken()
    {
        if ($this->token && $this->token['expires_at'] > now()) {
            return $this->token['access'];
        }

        $response = Http::post($this->baseUrl . '/token/new/', [
            'secret_id' => $this->secretId,
            'secret_key' => $this->secretKey,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->token = [
                'access' => $data['access'],
                'expires_at' => now()->addSeconds($data['access_expires'])
            ];
            return $this->token['access'];
        }

        throw new \Exception('Erreur lors de la récupération du token: ' . $response->body());
    }

    public function getBanks($country = 'FR')
    {
        $response = Http::withToken($this->getToken())
            ->get($this->baseUrl . '/institutions/', [
                'country' => $country
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erreur lors de la récupération des banques: ' . $response->body());
    }

    public function createRequisition($bankId, $redirectUrl)
    {
        $response = Http::withToken($this->getToken())
            ->post($this->baseUrl . '/requisitions/', [
                'redirect' => $redirectUrl,
                'institution_id' => $bankId,
                'reference' => uniqid()
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erreur lors de la création de la réquisition: ' . $response->body());
    }

    public function getAccounts($requisitionId)
    {
        $response = Http::withToken($this->getToken())
            ->get($this->baseUrl . "/requisitions/{$requisitionId}/");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erreur lors de la récupération des comptes: ' . $response->body());
    }

    public function getAccountDetails($accountId)
    {
        $response = Http::withToken($this->getToken())
            ->get($this->baseUrl . "/accounts/{$accountId}/details/");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erreur lors de la récupération des détails du compte: ' . $response->body());
    }

    public function getAccountBalances($accountId)
    {
        $response = Http::withToken($this->getToken())
            ->get($this->baseUrl . "/accounts/{$accountId}/balances/");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erreur lors de la récupération des soldes: ' . $response->body());
    }

    public function getAccountTransactions($accountId, $dateFrom = null, $dateTo = null)
    {
        $response = Http::withToken($this->getToken())
            ->get($this->baseUrl . "/accounts/{$accountId}/transactions/", [
                'date_from' => $dateFrom ? $dateFrom->format('Y-m-d') : null,
                'date_to' => $dateTo ? $dateTo->format('Y-m-d') : null
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erreur lors de la récupération des transactions: ' . $response->body());
    }
}
