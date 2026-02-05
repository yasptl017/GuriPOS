// app/Services/GooglePlacesService.php
<?php
namespace App\Services;

class GooglePlacesService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function autocomplete($input)
    {
        $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json';
        $params = [
            'input' => $input,
            'key' => $this->apiKey,
        ];

        $response = file_get_contents($url . '?' . http_build_query($params));
        return json_decode($response, true);
    }
}
