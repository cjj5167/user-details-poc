<?php

namespace App\Http\Controllers;

class UserDetailsController extends Controller
{
    private $social_site_client;

    public function __construct(\App\SocialSiteClient $client)
    {
        $this->social_site_client = $client;
    }

    public function show($id): array
    {
        $user = \App\User::findOrFail($id);
        $user_attribs = $user->only(["id", "username", "last_login", "handle"]);
        if ($user->handle) {
            $posts = $this->social_site_client->recentPostsForUser($user->handle);
        } else {
            $posts = [];
        }
        return $user_attribs + ["recent_posts" => $posts];
    }
}
