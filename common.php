<?php

function getInput(string $filepath, bool $removeEmpty = true): array
{
    $data = explode("\n", file_get_contents($filepath));

    if ($removeEmpty) {
        $data = array_filter($data, function ($a) {
            return trim($a) !== '';
        });
    }

    return $data;
}
