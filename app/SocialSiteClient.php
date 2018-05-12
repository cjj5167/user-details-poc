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
            CURLOPT_URL => $url
        ];
        $curl = curl_init();
        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);
        if ($response === false) {
            return [];
        }
        return json_decode($response, true)["posts"];
    }
}
