<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CsvService;
use InvalidArgumentException;

class CsvServiceTest extends TestCase
{
    /**
     * create creates csv on disk with good content
     * @test
     * @return void
     */
    public function createCreatesCsvOnDiskWithGoodContent()
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
     * create ignores extension in name if passed
     * @test
     * @return void
     */
    public function createIgnoresExtensionInNameIfPassed()
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
     * create throws error if content first element is not array of array
     * @test
     * @return void
     */
    public function createThrowsErrorIfContentFirstElementIsNotArrayOfArray()
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
     * create throws error if headers is empty
     * @test
     * @return void
     */
    public function createThrowsErrorIfHeadersIsEmpty()
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

    /**
     * read returns an associative array with good values
     * @test
     * @return void
     */
    public function readReturnsAnAssociativeArrayWithGoodValues()
    {
        $csvService = new CsvService();
        $users = [
            [
                'name' => 'Billy',
                'role' => 'admin'
            ]
        ];
        $csvService->create('csv-test', ['name', 'role'], $users);
        $values = $csvService->read('csv-test');
        $this->assertEquals($values, $users); // must return the exact input of create's last argument
        unlink(storage_path() . '/app/csv/csv-test.csv');
    }

    /**
     * read throws error if file is not found
     * @test
     * @return void
     */
    public function readThrowsErrorIfFileIsNotFound()
    {
        $this->expectException(InvalidArgumentException::class);
        $csvService = new CsvService();
        $users = [
            [
                'name' => 'Billy',
                'role' => 'admin'
            ]
        ];
        $csvService->create('csv-test-coucou.csv', ['name', 'role'], $users);
        $values = $csvService->read('csv-test');
        unlink(storage_path() . '/app/csv/csv-test-coucou.csv');
    }
}
