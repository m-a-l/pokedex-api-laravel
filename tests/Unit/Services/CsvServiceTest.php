<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CsvService;
use InvalidArgumentException;

class CsvServiceTest extends TestCase
{
    /**
     * csv is created on disk with good content
     * @test
     * @return void
     */
    public function csvIsCreatedOnDiskWithGoodContent()
    {
        $csvService = new CsvService();
        $users = [
            [
                'name' => 'Billy',
                'role' => 'admin'
            ],
            [
                'role' => 'user',
                'name' => 'Gérad',
                'favorite-ice-cream' => 'vanilla'
            ]
        ];
        $csvService->create('csv-test', ['name', 'role'], $users);
        $this->assertFileExists(storage_path() . '/app/csv/csv-test.csv');
        $this->assertEquals(file_get_contents(storage_path() . '/app/csv/csv-test.csv'), "name,role\nBilly,admin\nGérad,user\n");
        unlink(storage_path() . '/app/csv/csv-test.csv');
    }
    
    /**
     * ignore extension in name if passed to create function
     * @test
     * @return void
     */
    public function ignoreExtensionInNameIfPassedToCreateFunction()
    {
        $csvService = new CsvService();
        $users = [
            [
                'name' => 'Billy',
                'role' => 'admin'
            ]
        ];
        $csvService->create('csv-test.csv', ['name', 'role'], $users);
        $this->assertFileExists(storage_path() . '/app/csv/csv-test.csv');
        unlink(storage_path() . '/app/csv/csv-test.csv');
    }

    /**
     * throw error at creation if $content first element is not array of array
     * @test
     * @return void
     */
    public function throwErrorAtCreationIfContentFirstElementIsNotArrayOfArray()
    {
        $this->expectException(InvalidArgumentException::class);
        $csvService = new CsvService();
        $users = [
            'name' => 'Billy',
            'role' => 'admin'
        ];
        $csvService->create('csv-test.csv', ['name', 'role'], $users);
        unlink(storage_path() . '/app/csv/csv-test.csv');
    }

    /**
     * throw error at creation if $headers is empty or does not contain strings
     * @test
     * @return void
     */
    public function throwErrorAtCreationIfHeadersIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $csvService = new CsvService();
        $users = [
            [
                'name' => 'Billy',
                'role' => 'admin'
            ]
        ];
        $csvService->create('csv-test.csv', [], $users);
        unlink(storage_path() . '/app/csv/csv-test.csv');
    }
}
