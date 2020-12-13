<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day12-input.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $ship = new Ship();
    foreach ($data as $instruction) {
        $ship->move($instruction);
    }

    return $ship->getDistance();
}

function part2(array $data): ?int
{
    $ship = new Ship();
    foreach ($data as $instruction) {
        $ship->moveToWaypoint($instruction);
    }

    return $ship->getDistance();
}

class Ship
{
    private int $shipX = 0;
    private int $shipY = 0;
    private int $shipBearing = 90;
    private int $waypointX = 10;
    private int $waypointY = 1;

    public function move(string $instruction)
    {
        $move = substr($instruction, 0, 1);
        $amount = substr($instruction, 1);

        if ($move === 'F') {
            $move = $this->getDirectionOfCurrentBearing();
        }

        switch ($move) {
            case 'N':
                $this->shipY = $this->shipY + $amount;
                break;

            case 'S':
                $this->shipY = $this->shipY - $amount;
                break;

            case 'E':
                $this->shipX = $this->shipX + $amount;
                break;

            case 'W':
                $this->shipX = $this->shipX - $amount;
                break;

            case 'L':
                $this->shipBearing = $this->shipBearing - $amount;
                break;

            case 'R':
                $this->shipBearing = $this->shipBearing + $amount;
                break;
        }
    }

    private function getDirectionOfCurrentBearing(): string
    {
        $this->shipBearing = $this->normaliseBearing($this->shipBearing);
        switch ($this->shipBearing) {
            case 0:
                return 'N';
            case 90:
                return 'E';
            case 180:
                return 'S';
            case 270:
                return 'W';
        }
        return '';
    }

    public function moveToWaypoint(string $instruction)
    {
        $move = substr($instruction, 0, 1);
        $amount = substr($instruction, 1);

        switch ($move) {
            case 'N':
                $this->waypointY = $this->waypointY + $amount;
                break;

            case 'S':
                $this->waypointY = $this->waypointY - $amount;
                break;

            case 'E':
                $this->waypointX = $this->waypointX + $amount;
                break;

            case 'W':
                $this->waypointX = $this->waypointX - $amount;
                break;

            case 'L':
                $this->rotateWaypoint(-$amount);
                break;

            case 'R':
                $this->rotateWaypoint($amount);
                break;

            case 'F':
                $this->moveTowardsWaypoint($amount);
                break;
        }
    }

    public function getDistance(): int
    {
        return abs($this->shipX) + abs($this->shipY);
    }

    private function normaliseBearing(int $bearing): int
    {
        while ($bearing < 0) {
            $bearing += 360;
        }
        while ($bearing >= 360) {
            $bearing -= 360;
        }

        return $bearing;
    }

    private function rotateWaypoint(int $amount)
    {
        $amount = $this->normaliseBearing($amount);
        $oldX = $this->waypointX;
        $oldY = $this->waypointY;

        switch ($amount) {
            case 90:
                $this->waypointX = $oldY;
                $this->waypointY = -$oldX;
                break;

            case 180:
                $this->waypointX = -$oldX;
                $this->waypointY = -$oldY;
                break;

            case 270:
                $this->waypointX = -$oldY;
                $this->waypointY = $oldX;
        }
    }

    private function moveTowardsWaypoint(int $amount)
    {
        $distanceX = $amount * $this->waypointX;
        $this->shipX += $distanceX;

        $distanceY = $amount * $this->waypointY;
        $this->shipY += $distanceY;
    }
}
