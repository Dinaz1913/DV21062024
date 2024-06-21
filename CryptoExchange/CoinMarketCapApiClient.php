<?php

namespace Reelz222z\CryptoExchange;

use GuzzleHttp\Client;

class CoinMarketCapApiClient implements ApiClientInterface
{
    private Client $client;
    private string $apiUrl;
    private string $apiKey;

    public function __construct(Client $client, string $apiUrl, string $apiKey)
    {
        $this->client = $client;
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function fetchTopCryptocurrencies(): array
    {
        $response = $this->client->request('GET', $this->apiUrl, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $this->apiKey,
                'Accept' => 'application/json'
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $cryptocurrencies = [];

        foreach ($data['data'] as $cryptoData) {
            $quote = new Quote(
                $cryptoData['quote']['USD']['price'],
                $cryptoData['quote']['USD']['volume_24h'],
                $cryptoData['quote']['USD']['market_cap'],
                $cryptoData['quote']['USD']['market_cap_dominance'],
                $cryptoData['quote']['USD']['fully_diluted_market_cap'],
                $cryptoData['quote']['USD']['last_updated'] ?? 'N/A'
            );

            $cryptocurrencies[] = new Cryptocurrency(
                $cryptoData['id'],
                $cryptoData['name'],
                $cryptoData['symbol'],
                $quote
            );
        }

        return $cryptocurrencies;
    }

    public function getCryptocurrencyBySymbol(string $symbol): ?Cryptocurrency
    {
        foreach ($this->fetchTopCryptocurrencies() as $crypto) {
            if (trim(strtolower($crypto->getSymbol())) === trim(strtolower($symbol))) {
                return $crypto;
            }
        }
        return null;
    }

    public function getCryptocurrencyBySymbolForUser(string $symbol, User $user): ?Cryptocurrency
    {
        foreach ($user->getPortfolio() as $portfolioItem) {
            if ($portfolioItem['symbol'] === $symbol) {
                return new Cryptocurrency(
                    0, // ID not relevant for local portfolio
                    $portfolioItem['asset'],
                    $portfolioItem['symbol'],
                    new Quote(
                        $portfolioItem['price'],
                        0, // Volume not relevant for local portfolio
                        0, // Market Cap not relevant for local portfolio
                        0, // Market Cap Dominance not relevant for local portfolio
                        0, // Fully Diluted Market Cap not relevant for local portfolio
                        $portfolioItem['last_updated']
                    )
                );
            }
        }
        return null;
    }
}

