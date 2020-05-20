<?php
    function setToken()
    {
        $curl = curl_init('https://accounts.spotify.com/api/token');
        curl_setopt($curl, CURLOPT_POST, true);
        if(!empty($_COOKIE['refresh_token']))
        {
            $arguments = 'grant_type=refresh_token&refresh_token=' . urlencode($_COOKIE['refresh_token']);
        }
        else
        {
            $arguments = 'grant_type=authorization_code&code=' . urlencode($_GET['code']) . '&redirect_uri=' . urlencode('https://f0103958.ngrok.io/spotify_app/index.php');
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $arguments);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . base64_encode("1f90a9208bf748d0a6960e24e6d9d784:e32d8a31e7d14d4fbde4164ae2ff9a2d")));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        $array = json_decode($result, true);
        setcookie('token', $array['access_token'], time() + $array['expires_in']);
        if($array['refresh_token'])
        {
            setcookie("refresh_token", $array['refresh_token']);
        }
    }

    if(!empty($_GET['code']) && empty($_COOKIE['token']))
    {
        setToken();
    }
    if (!empty($_COOKIE['token']))
    {
        $curl = curl_init('https://api.spotify.com/v1/me/player');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . urlencode($_COOKIE['token'])));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        $array = json_decode($result, true);
        $name = $array['item']['name'];
        $artist_name = $array['item']['artists'][0]['name'];

        $curl = curl_init('https://orion.apiseeds.com/api/music/lyric/' . $artist_name . '/' . $name .
            '?apikey=EfRU6IeQLTK42H4InjdyxKoRErTAjonXvqjFMDQ58CT8MsPehEWieEciiWlgAoYL');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        $array = json_decode($result, true);
        $text = $array['result']['track']['text'];

    }
    else
    {
        header("Location: ./login.php");
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Spotify app</title>

</head>
<body>
    <h1> <?php echo $name . " " .  $artist_name ?></h1>
    <?php echo nl2br($text) ?>
</body>
</html>