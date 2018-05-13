<?php

namespace App\Http\Controllers;

use App\SocialSiteClientException;

class UserDetailsController extends Controller
{
    private $social_site_client;
    private $social_circuit_breaker;

    public function __construct(\App\SocialSiteClient $client)
    {
        $this->social_site_client = $client;
        $this->social_circuit_breaker = new \App\CircuitBreaker("social");
    }

    public function show($id): array
    {
        $user = \App\User::findOrFail($id);
        $user_attribs = $user->only(["id", "username", "last_login", "handle"]);
        $result = [];
        try {
            $posts = $this->getRecentPosts($user);
            $result["status"] = "complete";
            $result["details"] = $user_attribs + ["recent_posts" => $posts];
        } catch (SocialSiteClientException $e) {
            $result["status"] = "partial";
            $result["details"] = $user_attribs + ["recent_posts" => []];
        }
        return $result;
    }

    private function getRecentPosts(\App\User $user): array
    {
        if ($user->handle === null) {
            return [];
        }
        if ($this->social_circuit_breaker->isEnabled()) {
            try {
                $posts = $this->social_site_client->recentPostsForUser($user->handle);
                $this->social_circuit_breaker->recordSuccess();
                return $posts;
            } catch (\Exception $e) {
                error_log("Error retrieving posts: {$e->getMessage()}");
                $this->social_circuit_breaker->recordFailure($e);
                throw new SocialSiteClientException("Error retrieving posts", 0, $e);
            }
        } else {
            throw new SocialSiteClientException("Posts service unavailable");
        }
    }
}
