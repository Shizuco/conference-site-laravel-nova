<?php

declare (strict_types = 1);

namespace App\Traits;

use App\Models\ZoomMeeting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Log;

/**
 * trait ZoomMeetingTrait
 */
trait ZoomMeetingTrait
{
    public $client;
    public $jwt;
    public $headers;

    public function __construct()
    {
        $this->client = new Client();
        $this->jwt = $this->generateZoomToken();
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->jwt,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
    public function generateZoomToken()
    {
        $key = config('app.zoom_api_key');
        $secret = config('app.zoom_api_secret');
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];
        return config('app.zoom_api_token');
    }

    private function retrieveZoomUrl()
    {
        return config('app.zoom_api_url');
    }

    public function toZoomTimeFormat(\DateTime $dateTime)
    {
        try {
            return $dateTime->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : ' . $e->getMessage());

            return '';
        }
    }

    public function create($data)
    {
        $path = 'users/me/meetings';
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->generateZoomToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                'topic' => $data['topic'],
                'type' => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration' => $data['duration'],
                'agenda' => (!empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone' => 'Europe/Kyiv',
                'settings' => [
                    'host_video' => ($data['host_video'] == "1") ? true : false,
                    'participant_video' => ($data['participant_video'] == "1") ? true : false,
                    'waiting_room' => true,
                ],
            ]),
        ];
        $response = $this->client->post($url . $path, $body);
        $meet = $this->get(json_decode($response->getBody()->getContents(), true)['id']);
        $start_url = $meet['data']['start_url'];
        $id = $meet['data']['id'];
        $join_url = $meet['data']['join_url'];
        $data = [
            'id' => $id,
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => $this->toZoomTimeFormat($data['start_time']),
            'duration' => $data['duration'],
            'agenda' => null,
            'timezone' => 'Europe/Kyiv',
            'start_url' => $start_url,
            'join_url' => $join_url,
        ];
        ZoomMeeting::create($data);

        return [
            'success' => $response->getStatusCode() === 201,
            'data' => $meet,
        ];
    }

    public function put($id, $data)
    {
        $path = 'meetings/' . $id;
        $url = $this->retrieveZoomUrl();
        $body = [
            'headers' => $this->headers,
            'body' => json_encode([
                'topic' => $data['topic'],
                'type' => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration' => $data['duration'],
                'agenda' => (!empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone' => 'Europe/Kyiv',
                'settings' => [
                    'host_video' => ($data['host_video'] == "1") ? true : false,
                    'participant_video' => ($data['participant_video'] == "1") ? true : false,
                    'waiting_room' => true,
                ],
            ]),
        ];
        $response = $this->client->patch($url . $path, $body);
        $data = [
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => $this->toZoomTimeFormat($data['start_time']),
            'duration' => $data['duration'],
            'agenda' => (!empty($data['agenda'])) ? $data['agenda'] : null,
            'timezone' => 'Europe/Kyiv'
        ];
        $model = ZoomMeeting::where('id', $id)->first();
        $model->update($data);
        return [
            'success' => $response->getStatusCode() === 204,
            'data' => json_decode($response->getBody()->getContents()),
        ];
    }

    public function get($id)
    {
        $path = 'meetings/' . $id;
        $url = $this->retrieveZoomUrl();
        $this->jwt = $this->generateZoomToken();
        $body = [
            'headers' => $this->headers,
            'body' => json_encode([]),
        ];

        $response = $this->client->get($url . $path, $body);
        return [
            'success' => $response->getStatusCode() === 200,
            'data' => json_decode($response->getBody()->getContents(), true),
        ];
    }

    public function getAll()
    {
        $path = 'users/me/meetings';
        $url = $this->retrieveZoomUrl();
        $this->jwt = $this->generateZoomToken();
        $body = [
            'headers' => $this->headers,
            'body' => json_encode([]),
        ];
        $response = $this->client->get($url . $path, $body);
        return json_decode($response->getBody()->getContents());
    }

    public function delete($id)
    {
        $path = 'meetings/' . $id;
        $url = $this->retrieveZoomUrl();
        $body = [
            'headers' => $this->headers,
            'body' => json_encode([]),
        ];

        $response = $this->client->delete($url . $path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
        ];
    }
}
