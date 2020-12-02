<?php

function getInput(string $filepath): array
{
    return array_filter(explode("\n", file_get_contents($filepath)), function ($a) {
        return trim($a) !== '';
    });
}
