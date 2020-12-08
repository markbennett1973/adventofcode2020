<?php

class Computer {
    /** @var array|Instruction[] */
    private array $instructions = [];

    private int $accumulator;
    private int $currentInstruction;
    private array $executedInstructions = [];

    /**
     * Computer constructor.
     * @param array $instructionsStrings
     */
    public function __construct(array $instructionsStrings)
    {
        foreach ($instructionsStrings as $instructionsString) {
            $this->instructions[] = new Instruction($instructionsString);
        }
    }

    /**
     * @return int
     * @throws Exception
     */
    public function execute(): int
    {
        $this->accumulator = $this->currentInstruction = 0;
        $exitInstruction = count($this->instructions);

        while ($this->currentInstruction < $exitInstruction) {
            if (in_array($this->currentInstruction, $this->executedInstructions)) {
                throw new Exception('Loop detected', $this->accumulator);
            }
            $this->executedInstructions[] = $this->currentInstruction;
            $this->executeCurrentInstruction();
        }

        return $this->accumulator;
    }

    private function executeCurrentInstruction()
    {
        $instruction = $this->instructions[$this->currentInstruction];
        switch ($instruction->instruction) {
            case 'nop':
                $this->currentInstruction++;
                break;

            case 'acc':
                $this->accumulator += $instruction->value;
                $this->currentInstruction++;
                break;

            case 'jmp':
                $this->currentInstruction += $instruction->value;
                break;
        }
    }
}

class Instruction {
    public string $instruction;
    public int $value;

    public function __construct(string $instructionString)
    {
        $parts = explode(' ', $instructionString);
        $this->instruction = $parts[0];
        $this->value = $parts[1];
    }
}
