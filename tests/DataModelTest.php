<?php


use App\DataObjects\CellContainer;
use App\DataObjects\CellValue;
use App\DataObjects\Puzzle;
use App\PuzzleFactory;
use PHPUnit\Framework\TestCase;

class DataModelTest extends TestCase
{

    // A given row is valid (does not contain two numbers)
    // A given column is valid (does not contain two numbers)
    // A given box is valid (does not contain two numbers)

    // Objects
    // CellValue
    // - Contains a Single Value1
    // - Random Identifier (to ensure uniqueness)

    // CellCollection: Contains a group of CellValues
    // RowCollection: 9 CellValues
    // ColumnCollection: 9 CellValues
    // BoxCollection: 9 CellValues
    // PuzzleCollection: 81 CellValues, 9 RowCollections, 9 ColumnCollections, 9 BoxCollections


    // Concerns
    // - Factory to build all PuzzleCollections, BoxCollections, ColumnCollections, and RowCollections out of CellValues
    // - Box, Column, and Row Collections can all be the same object (or derivatives of the same base object)
    // - Need a way to generate solvable Sudoku puzzles?

    public function testCellValueHasIdWhenCreated(): void
    {
        $cellValue = new CellValue();
        $this->assertNotNull($cellValue->getId());
    }

    public function testCellValuesAreRandom(): void
    {
        $cellValue1 = new CellValue();
        $cellValue2 = new CellValue();
        $this->assertNotEquals($cellValue1->getId(), $cellValue2->getId());
    }

    public function testCellValueCanBeInitializedWithNumber(): void
    {
        $cellValue = new CellValue(5);
        $this->assertEquals(5, $cellValue->getValue());
    }

    public function testCellValueCanStoreNumber(): void
    {
        $cellValue = new CellValue();
        $cellValue->storeValue(5);
        $this->assertEquals(5, $cellValue->getValue());
    }

    public function testCellValueCannotStoreNegativeNumber(): void
    {
        $cellValue = new CellValue();
        $this->expectException(InvalidArgumentException::class);
        $cellValue->storeValue(-5);
    }

    public function testCellContainerCanStoreCells(): void
    {
        $cellValue     = new CellValue;
        $cellContainer = new CellContainer();

        $cellContainer->store($cellValue);
        $this->assertEquals(1, $cellContainer->count());
    }

    public function testCellContainerCannotContainTheSameCellTwice(): void
    {
        $cellValue     = new CellValue;
        $cellContainer = new CellContainer();

        $cellContainer->store($cellValue);
        $cellContainer->store($cellValue);
        $this->assertEquals(1, $cellContainer->count());
    }

    public function testCellContainerValidatesUniqueValues(): void
    {
        $cellValue     = new CellValue;
        $cellValue2    = new CellValue;
        $cellContainer = new CellContainer();

        $cellContainer->store($cellValue);
        $cellContainer->store($cellValue2);
        $this->assertEquals(2, $cellContainer->count());

        $cellValue->storeValue(1);
        $cellValue2->storeValue(2);

        $this->assertTrue($cellContainer->validate());
    }

    public function testCellContainerInvalidatesUniqueValues(): void
    {
        $cellValue     = new CellValue;
        $cellValue2    = new CellValue;
        $cellContainer = new CellContainer();

        $cellContainer->store($cellValue);
        $cellContainer->store($cellValue2);
        $this->assertEquals(2, $cellContainer->count());

        $cellValue->storeValue(1);
        $cellValue2->storeValue(1);

        $this->assertFalse($cellContainer->validate());
    }

    public function testCellContainerDoesNotValidateDuplicateNulls(): void
    {
        $cells = [
            new CellValue(),
            new CellValue(),
            new CellValue(),
            new CellValue(),
            new CellValue()
        ];

        $cellContainer = new CellContainer();
        $cellContainer->storeList($cells);

        $cells[0]->storeValue(1);
        $this->assertTrue($cellContainer->validate());
    }

    public function testCanAddRowToPuzzle(): void
    {
        $cellContainer = new CellContainer();

        $puzzle = new Puzzle();
        $puzzle->addRow($cellContainer);
        $this->assertEquals(1, $puzzle->countRows());
    }

    public function testCanAddColumnsToPuzzle(): void
    {
        $cellContainer = new CellContainer();

        $puzzle = new Puzzle();
        $puzzle->addColumn($cellContainer);
        $this->assertEquals(1, $puzzle->countColumns());
    }

    public function testCanAddBlocksToPuzzle(): void
    {
        $cellContainer = new CellContainer();

        $puzzle = new Puzzle();
        $puzzle->addBlock($cellContainer);
        $this->assertEquals(1, $puzzle->countBlocks());
    }

    public function testCanValidatePuzzle(): void
    {
        $cellContainer = new CellContainer();

        $puzzle = new Puzzle();
        $puzzle->addBlock($cellContainer);
        $this->assertTrue($puzzle->validate());
    }

    public function testCanInvalidatePuzzle(): void
    {
        $cellContainer = new CellContainer();
        $cellContainer->storeList([
            new CellValue(1),
            new CellValue(1)
        ]);

        $puzzle = new Puzzle();
        $puzzle->addBlock($cellContainer);
        $this->assertFalse($puzzle->validate());
    }

    public function testFactoryMakesPuzzlesWithNineRows(): void
    {
        $factory = new PuzzleFactory();
        $puzzle  = $factory->create();

        $this->assertEquals(9, $puzzle->countRows());
    }

    public function testFactoryMakesPuzzlesWithNineColumns(): void
    {
        $factory = new PuzzleFactory();
        $puzzle  = $factory->create();

        $this->assertEquals(9, $puzzle->countColumns());
    }

    public function testFactoryMakesPuzzlesWithNineBlocks(): void
    {
        $factory = new PuzzleFactory();
        $puzzle  = $factory->create();

        $this->assertEquals(9, $puzzle->countBlocks());
    }

    public function testFactoryMakesPuzzlesWithNineCellsInEachRow(): void
    {
        $factory = new PuzzleFactory();
        $puzzle  = $factory->create();

        foreach($puzzle->getRows() as $row){
            $this->assertEquals(9, $row->count());
        }
    }

    public function testFactoryMakesPuzzlesWithNineCellsInEachColumns(): void
    {
        $factory = new PuzzleFactory();
        $puzzle  = $factory->create();

        foreach($puzzle->getColumns() as $column){
            $this->assertEquals(9, $column->count());
        }
    }

    public function testFactoryMakesPuzzlesWithNineCellsInEachBlock(): void
    {
        $factory = new PuzzleFactory();
        $puzzle  = $factory->create();

        foreach($puzzle->getBlocks() as $block){
            $this->assertEquals(9, $block->count());
        }
    }

}
