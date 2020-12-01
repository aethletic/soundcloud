<?php

namespace Soundcloud;

use Exception;

class Api
{
    private $clientId;

    private const API_URL = 'https://api.soundcloud.com/';

    public function __construct($clientId = null)
    {
        if (!$clientId) {
            new Exception('Please, pass your `client_id` to Client constructor as first argument.');
        }

        $this->clientId = $clientId;
    }

    /**
     * Get track ID by track url.
     *
     * @param string $url Track url
     * @return array|bool
     */
    public function getTrackId($url)
    {
        $response = $this->resolve($url);
        return (int) $response['id'] ?? false;
    }

    /**
     * Get playlist.
     *
     * @param string $url Playlist url
     * @return array|bool
     */
    public function getPlaylist($url)
    {
        $response = $this->resolve($url);
        return $response;
    }

    /**
     * This request will resolve and redirect to the API resource URL 
     * for the track https://soundcloud.com/matas/hobnotropic. 
     * Just follow the redirect and you will get the representation you want. 
     * 
     * The resolver supports URLs for:
     * - users
     * - tracks
     * - playlists (sets)
     *
     * @param string $url
     * @return array|bool
     */
    public function resolve($url)
    {
        return $this->api(['resolve'], [
            'url' => $url,
        ]);
    }

    /**
     * Get mp3 links by track ID.
     *
     * @param string|int $trackId Track ID
     * @return array|bool
     */
    public function getStreamByTrackId($trackId)
    {
        return $this->api(['i1', 'tracks', $trackId, 'streams']);
    }

    /**
     * Get mp3 links by track url.
     *
     * @param string $url Track url
     * @return array|bool
     */
    public function getStreamByTrackUrl(string $url)
    {
        $trackId = $this->getTrackId($url);

        if (!$trackId) {
            return false;
        }

        return $this->getStreamByTrackId($trackId);
    }

    /**
     * Universal methods for call Soundcloud API.
     *
     * @param array $methods Array of api methods
     * @param array $params Array of query params
     * @return array|bool
     */
    public function api(array $methods = [], array $params = [])
    {
        $response = file_get_contents(
            self::API_URL .  implode('/', $methods) . '/?' . http_build_query(array_merge(
                $params,
                ['client_id' => $this->clientId]
            ))
        );

        if (!$response) {
            return false;
        }

        return json_decode($response, true);
    }

    /**
     * Create m3u playlist with mp3 128 kbps.
     *
     * @param array $playlist Array of result by `getPlaylist` method.
     * @return string|bool
     */
    public function playlistToM3U(array $playlist)
    {
        $list = array_map(function ($item) {
            $duration = round($item['duration'] / 1000);
            $title = strpos($item['title'], '-') !== false ? $item['title'] : "{$item['user']['username']} - {$item['title']}";
            $url = @$this->getStreamByTrackId($item['id'])['http_mp3_128_url'];
            return $url ? implode("\n", [
                "#EXTINF:{$duration},{$title}",
                $url,
            ]) : false;
        }, $playlist['tracks']);

        $list = array_filter($list);

        return count($list) > 0 ? "#EXTM3U\n\n" . implode("\n\n", $list) : false;
    }
}
