<?php

namespace Dwikipeddos\LaravelGoogleDistance\Service;

use Dwikipeddos\LaravelGoogleDistance\Data\GoogleDistanceResultData;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GoogleDistance
{
    public string $requestUrl = 'https://maps.googleapis.com/maps/api/distancematrix/';
    private string $apiKey;
    public string $units;
    public Response $result;

    public function __construct()
    {
        $this->apiKey = config('google-distance.api-key', '');
        $this->units = config('google-distance.units', 'matrix');
        $this->requestUrl .= config('google-distance.format', 'json');
    }

    public function calculate(string $origin, string $destination): GoogleDistanceResultData
    {
        $response = Http::get($this->requestUrl, [
            'units' => $this->units,
            'origins' => $origin,
            'destinations' => $destination,
            'key' => $this->apiKey,
        ]);
        $this->result = $response;

        throw_if($response->status() != 200, new HttpException($response->status(), $response->body()));
        $responseData = json_decode($response->body());
        throw_if(!isset($responseData->rows[0]->elements[0]->distance), new Exception($responseData->rows[0]->elements[0]->status));
        $result = GoogleDistanceResultData::from([
            'distance_text' => $responseData->rows[0]->elements[0]->distance->text,
            'distance_value' => $responseData->rows[0]->elements[0]->distance->value,
            'duration_text' => $responseData->rows[0]->elements[0]->duration->text,
            'duration_value' => $responseData->rows[0]->elements[0]->duration->value,
        ]);
        return $result; //$responseData->rows[0]->elements[0]->distance;
    }

    public function toArray()
    {
        return [
            'request_url' => $this->requestUrl,
            'response' => $this->result ?? null,
            'units' => $this->units,
            'format' => config('google-distance.format', 'json'),
        ];
    }
}
