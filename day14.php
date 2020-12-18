<?php

include('common.php');

//const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day14-input.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $mask = '';
    $mem = [];
    foreach ($data as $line) {
        if (substr($line, 0, 4) === 'mask') {
            $mask = substr($line, 7);
        } else {
            preg_match('/mem\[(\d+)\] = (\d+)/', $line, $matches);
            $address = $matches[1];
            $value = $matches[2];
            $mem[$address] = applyMaskToValue($mask, $value);
        }
    }

    return array_sum($mem);
}

function part2(array $data): ?int
{
    $mask = '';
    $mem = [];
    foreach ($data as $line) {
        if (substr($line, 0, 4) === 'mask') {
            $mask = substr($line, 7);
        } else {
            preg_match('/mem\[(\d+)\] = (\d+)/', $line, $matches);
            $address = $matches[1];
            $value = $matches[2];
            foreach (applyMaskToAddress($mask, $address) as $maskedAddress) {
                $mem[$maskedAddress] = $value;
            }
        }
    }

    return array_sum($mem);
}

function applyMaskToValue(string $mask, int $value): int
{
    $maskLength = strlen($mask);
    $binValue = (string) decbin($value);
    $output = [];
    // Pad binary value with leading zeros
    $paddedBinValue = substr(str_repeat('0', 36) . $binValue, -36);
    for ($i = 0; $i < $maskLength; $i++) {
        switch ($mask[$i]) {
            case '0':
                $output[$i] = '0';
                break;
            case '1':
                $output[$i] = '1';
                break;
            default:
                $output[$i] = $paddedBinValue[$i];
        }
    }

    return bindec(implode('', $output));
}

function applyMaskToAddress(string $mask, int $address): array
{
    $maskLength = strlen($mask);
    $binAddress = (string) decbin($address);
    $floatingBits = $output = [];
    // Pad binary value with leading zeros
    $paddedBinValue = substr(str_repeat('0', 36) . $binAddress, -36);
    for ($i = 0; $i < $maskLength; $i++) {
        switch ($mask[$i]) {
            case '0':
                $output[$i] = $paddedBinValue[$i];
                break;
            case '1':
                $output[$i] = '1';
                break;
            default:
                $output[$i] = 'X';
                $floatingBits[] = $i;
        }
    }

    $addresses = [];
    $floatingBitsCount = count($floatingBits);
    $floatingBitLimit = pow(2, $floatingBitsCount);
    for ($i = 0; $i < $floatingBitLimit; $i++) {
        $floatingBitsBinValue = (string) decbin($i);
        // pad to required length
        $paddedFloatingBits = substr(str_repeat('0', $floatingBitsCount) . $floatingBitsBinValue, -$floatingBitsCount);
        for ($j = 0; $j < $floatingBitsCount; $j++) {
            $outputIndex = $floatingBits[$j];
            $output[$outputIndex] = substr($paddedFloatingBits, $j, 1);
        }

        $addresses[] = bindec(implode('', $output));
    }

    return $addresses;
}
