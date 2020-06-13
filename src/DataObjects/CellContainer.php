<?php


namespace App\DataObjects;


class CellContainer
{

    /**
     * @var CellValue[]
     */
    private array $cells = [];

    public function store(CellValue $cellValue): void
    {
        if (in_array($cellValue, $this->cells, true)) {
            return;
        }

        $this->cells[] = $cellValue;
    }

    public function storeList(iterable $cellValues): void
    {
        foreach ($cellValues as $cellValue) {
            $this->store($cellValue);
        }
    }

    public function count(): int
    {
        return count($this->cells);
    }

    public function validate(): bool
    {
        $currentValues = [];
        foreach ($this->cells as $cell) {
            $value = $cell->getValue();
            if (null === $value) {
                continue;
            }

            if (in_array($value, $currentValues, true)) {
                return false;
            }
            $currentValues[] = $value;
        }

        return true;
    }


}
