<?php
    $scopes = 'user-read-playback-state';
    header('Location: https://accounts.spotify.com/authorize?response_type=code&client_id=1f90a9208bf748d0a6960e24e6d9d784&scope=' . $scopes . '&redirect_uri=' . urlencode('https://f0103958.ngrok.io/spotify_app/index.php'));
