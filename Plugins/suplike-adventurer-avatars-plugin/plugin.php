<?php

namespace Bethropolis\PluginSystem\IconzPlugin;

use Bethropolis\PluginSystem\Plugin;

class Load extends Plugin
{
    public function initialize()
    {
        $this->linkHook('signup_hook', [$this, 'profilepic']);
    }

    public function profilepic($userData)
    {
        $userId = $userData['user_id'];
        $username = $userData['username'];

        $prof = $this->saveSvgImage($username);

        if (!$prof) {
            return;
        }

        $this->updateProfilePicture($userId, $prof);

        return $prof;
    }

    private function saveSvgImage($searchTerm)
    {
        $dir = __DIR__ . "/../../../img/";
        $filename = "{$searchTerm}.svg";
        $filepath = $dir . $filename;

        if (file_exists($filepath)) {
            return $filename;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.dicebear.com/6.x/adventurer/svg?seed={$searchTerm}&backgroundColor=c0aede",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: image/svg+xml"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->error("Failed to get Icon: {$err}", "WARN");
            return;
        }

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($filepath, $response);

        return $filename;
    }

    private function updateProfilePicture($userId, $prof)
    {
        global $conn;

        $escapedProf = $conn->real_escape_string($prof);
        $query = "UPDATE users SET `profile_picture` = '$escapedProf' WHERE idusers = '$userId'";
        $conn->query($query);
    }
}
