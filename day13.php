<?php

/**
 * Code is correct, and gives correct answers, but part 2 will take 27 hours to complete
 * Need something more efficient...
 */
include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day13-input.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $startTime = $data[0];
    $buses = array_filter(explode(',', $data[1]), function (string $a) {
        return $a !== 'x';
    });

    $departureTimes = [];
    foreach ($buses as $bus) {
        $departureTimes[$bus] = ceil($startTime / $bus) * $bus;
    }

    asort($departureTimes);
    $firstBus = array_key_first($departureTimes);
    $waitTime = $departureTimes[$firstBus] - $startTime;
    return $firstBus * $waitTime;
}

function part2(array $data): ?int
{
    $buses = [];
    foreach (explode(',', $data[1]) as $index => $bus) {
        if ($bus !== 'x') {
            $buses[$bus] = $index;
        }
    }

    krsort($buses);
    $interval = 1;
    while (true) {
        if (intervalsAlign($interval, $buses)) {
            return getTimestamp($interval, $buses);
        }

        $interval++;

        if ($interval % 1000000 === 0) {
            print "Current start timestamp = " . number_format(($interval * 883) - $buses[883]) . "\n";
        }
    }
}

function intervalsAlign(int $interval, array $buses): bool
{
    $firstBus = array_key_first($buses);
    $baseTime = ($interval * $firstBus) - $buses[$firstBus];

    foreach ($buses as $bus => $offset) {
        if ($bus === $firstBus) {
            continue;
        }

        $time = $baseTime + $offset;
        if ($time % $bus !== 0) {
            return false;
        }
    }

    return true;
}

function getTimestamp(int $interval, array $buses): int
{
    $firstBus = array_key_first($buses);
    $baseTime = ($interval * $firstBus) - $buses[$firstBus];
    return $baseTime;
}
