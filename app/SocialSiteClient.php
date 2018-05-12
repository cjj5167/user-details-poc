<?php

namespace App;

class SocialSiteClient
{
    public function recentPostsForUser(string $handle, int $max = 5): array
    {
        $url = "http://soci.al/api/users/" . urlencode($handle) . "?include=recent_posts&limit={$max}";
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_URL => $url,
            CURLOPT_CONNECT_TO => ["soci.al:80:proxy:23080"],
            CURLOPT_TIMEOUT => 10
        ];
        $curl = curl_init();
        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);
        if ($response === false) {
            throw new \Exception("cURL error: " . curl_error($curl));
        }
        return json_decode($response, true)["posts"];
    }
}
