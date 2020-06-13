<?php


namespace App\DataObjects;


class Puzzle
{
    /**
     * @var CellContainer[]
     */
    protected array $all = [];

    /**
     * @var CellContainer[]
     */
    protected array $rows = [];

    /**
     * @var CellContainer[]
     */
    protected array $columns = [];

    /**
     * @var CellContainer[]
     */
    protected array $blocks = [];

    /**
     * @return CellContainer[]
     */
    public function getAll(): array
    {
        return $this->all;
    }

    /**
     * @return CellContainer[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @return CellContainer[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return CellContainer[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    public function addRow(CellContainer $cellContainer): void
    {
        $this->rows[] = $cellContainer;
        $this->all[]  = $cellContainer;
    }

    public function countRows(): int
    {
        return count($this->rows);
    }

    public function addColumn(CellContainer $cellContainer): void
    {
        $this->columns[] = $cellContainer;
        $this->all[]     = $cellContainer;
    }

    public function countColumns(): int
    {
        return count($this->columns);
    }

    public function addBlock(CellContainer $cellContainer): void
    {
        $this->blocks[] = $cellContainer;
        $this->all[]    = $cellContainer;
    }

    public function countBlocks(): int
    {
        return count($this->blocks);
    }

    public function validate(): bool
    {
        foreach ($this->all as $cellContainer) {
            if (!$cellContainer->validate()) {
                return false;
            }
        }

        return true;
    }
}
