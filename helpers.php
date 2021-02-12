<?php

/**
 * Cache where is user
 * @param $telegram
 * @param $step
 */
function cacheStep($telegram, $step)
{
    $fileName = $telegram->ChatID();
    $file = fopen("cache/" . $fileName . ".txt", "w");

    fwrite($file, $step);
    fclose($file);
}

/**
 * Decode cache files
 * @param $data
 * @return false|string[]
 */
function decodeCache($data)
{
    return explode("|", $data);
}

/**
 * Open and get where is user
 * @param $telegram
 * @return false|string
 */
function getStep($telegram)
{
    $fileName = "cache/" . $telegram->ChatID() . ".txt";
    $file = fopen($fileName, "r");

    $data = fread($file, filesize($fileName));
    fclose($file);

    return decodeCache($data);
}

/**
 * Check user is superUser or not
 * @param $user
 * @return bool
 */
function isSuperuser($user): bool
{
    return $user->is_superuser;
}
