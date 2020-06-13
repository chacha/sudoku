<?php


namespace App;


use App\DataObjects\CellContainer;
use App\DataObjects\CellValue;
use App\DataObjects\Puzzle;

class PuzzleFactory
{

    public function create(): Puzzle
    {
        $puzzle = new Puzzle();

        $rows = $this->getCellContainers();
        $this->addRows($puzzle, $rows);

        $columns = $this->getCellContainers();
        $this->addColumns($puzzle, $columns);

        $blocks = $this->getCellContainers();
        $this->addBlocks($puzzle, $blocks);
        echo "\n";

        /** @var CellContainer $row */
        foreach ($rows as $rowNumber => $row) {

            /** @var CellContainer $column */
            foreach ($columns as $columnNumber => $column) {

                $cell = new CellValue();
                $column->store($cell);
                $row->store($cell);

                $blockNumber = $this->getBlockNumber($rowNumber, $columnNumber);
                $blocks[$blockNumber - 1]->store($cell);
            }
            echo "\n";

        }

        return $puzzle;
    }

    protected function addRows(Puzzle $puzzle, array $cells): void
    {
        foreach ($cells as $cell) {
            $puzzle->addRow($cell);
        }
    }

    protected function addColumns(Puzzle $puzzle, array $cells): void
    {
        foreach ($cells as $cell) {
            $puzzle->addColumn($cell);
        }
    }

    protected function addBlocks(Puzzle $puzzle, array $cells): void
    {
        foreach ($cells as $cell) {
            $puzzle->addBlock($cell);
        }
    }

    protected function getCellContainers(int $count = 9): array
    {
        $result = [];
        foreach (range(1, $count) as $number) {
            $result[] = new CellContainer();
        }
        return $result;
    }

    private function getBlockNumber(int $rowNumber, int $columnNumber): int
    {
        $blockRow    = ceil(++$rowNumber / 3);
        $blockColumn = ceil(++$columnNumber / 3);

        return (($blockRow - 1) * 3) + $blockColumn;
    }

}
