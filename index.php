<?php

include('partials/header.php');

if (empty($url[2])) {
    include('pages/home.php');
} else {
    if (file_exists('pages/' . $url[2] . '.php')) {
        include('pages/' . $url[2] . '.php');
    } else {
        include('pages/404.php');
    }
}

include('partials/footer.php');
