<?php

include('common.php');
include('computer.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day8-input.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $computer = new Computer($data);
    try {
        $computer->execute();
    } catch (Exception $ex) {
        return $ex->getCode();
    }

    return null;
}

function part2(array $data): ?int
{
    $count = count($data);
    for ($i = 0; $i < $count; $i++) {
        $computer = new Computer(switchJmpToNop($data, $i));
        try {
            return $computer->execute();
        } catch (Exception $ex) {
            // nothing - try the next instruction
        }

        $computer = new Computer(switchNopToJmp($data, $i));
        try {
            return $computer->execute();
        } catch (Exception $ex) {
            // nothing - try the next instruction
        }
    }

    return null;
}

function switchJmpToNop(array $data, int $instruction): array
{
    $data[$instruction] = str_replace('jmp', 'nop', $data[$instruction]);
    return $data;
}

function switchNopToJmp(array $data, int $instruction): array
{
    $data[$instruction] = str_replace('nop', 'jmp', $data[$instruction]);
    return $data;
}
