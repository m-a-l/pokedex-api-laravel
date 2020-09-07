<?php

namespace App\Services;

use Storage;
use InvalidArgumentException;

/**
* A service to create and read csvs (made to convert easily csv to collections laravel can import)
* Reads and writes in /storage/app/csv
*/
class CsvService
{
    /**
    * Create a csv from an array of arrays
    * @param $fileName, the fileneme without extension
    * @param $headers, the headers of the csv (column names) eg. ['name', 'role']
    * @param $content, the actual data, with keys being column name eg. ['name' => 'billy', 'role' => 'user']
    *
    * @throws InvalidArgumentException
    * @return void
    */
    public function create(string $fileName, array $headers, array $content)
    {
        // Verify and secure arguments
        $fileName = explode('.', $fileName)[0];
        if (empty($fileName)) {
            $fileName = (string) uniqid();
        }
        if (empty($content) || empty($content[0]) || !is_array($content[0])) {
            throw new InvalidArgumentException(
                "third parameter cannot be empty and must contain arrays"
            );
        }
        if (empty($headers) || !is_string($headers[0])) {
            throw new InvalidArgumentException(
                "second paramaters cannot be empty and must contain strings"
            );
        }

        $path =  "csv/$fileName.csv";
        \Storage::put($path, '');
        $csvInsert = [
            $headers
        ];
        // Make the data in a csv row,
        // meaning go from ['name' => 'billy', 'role' -> 'user'] to ['billy', 'user']
        // while being assured order is matching headers'order
        foreach ($content as $model) {
            $row = [];
            foreach ($model as $field => $value) {
                $index = array_search($field, $headers);
                if ($index !== false) {
                    if (is_bool($value)) {
                        $value = ($value == false ? 'false' : 'true');
                    }
                    $row[$index] = $value;
                } else {
                    throw new InvalidArgumentException(
                        "Key $field not found in headers"
                    );
                }
            }
            $rowBackUp = $row;
            ksort($row);
            if ($row == false) {
                $row = $rowBackUp;
            }
            $csvInsert[] = $row;
        }
        $file = fopen(storage_path() . "/app/$path", 'w');
        foreach ($csvInsert as $row) {
            fputcsv($file, $row);
        }
    }

    /**
    * Reads a csv.
    * Returns an array of arrays containing field => value, exact same as create's' argument $content
    * @param $fileName, the filename without extension. Will read /storage/app/csv/filename.csv
    *
    * @return array
    */
    public function read(string $fileName)
    {
        $file = $this->getFile($fileName);
        $header = null;
        $csv = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 100000, ",")) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $csv[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $csv;
    }

    /**
    * Get headers from a csv file
    * @param $fileName, the filename without extension. Will read /storage/app/csv/filename.csv
    * @return array
    */
    public function getHeaders(string $fileName)
    {
        $file = $this->getFile($fileName);
        if (($handle = fopen($file, 'r')) !== false) {
            $headers = fgetcsv($handle, 100000, ",");
            fclose($handle);
        }
        return $headers;
    }

    /**
    * Checks if file exists and returns full path
    * @param $fileName, the filename without extension. Will read /storage/app/csv/filename.csv
    */
    private function getFile(string $fileName)
    {
        $file = storage_path() . "/app/csv/$fileName.csv";
        if (!file_exists($file) || !is_readable($file)) {
            throw new InvalidArgumentException(
                "The file storage/app/csv/$fileName.csv does not exists"
            );
        }

        return $file;
    }
}
