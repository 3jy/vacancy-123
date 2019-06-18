<?php

namespace application;

/**
 * Class Loader
 * @package application
 */
class Loader
{
    /**
     * @param string $file
     */
    public function load($file)
    {
        Logger::getInstance()->info("Starting to load file $file");

        if (!file_exists($file)) {
            throw new \InvalidArgumentException('File ' . $file . ' doesen\'t exist');
        }

        $handle = fopen($file, "r");

        $fileContent = [];
        while (($data = fgetcsv($handle, "1000", ",")) !== false) {
            $fileContent[] = $data;
        }

        unset($fileContent[0]);
        $this->parse($fileContent);

        Logger::getInstance()->info("File load is finished");
    }

    /**
     * @param array $content
     */
    private function parse($content)
    {
        Logger::getInstance()->info("Starting to parse file");
        $needleFields = [0, 1, 5];
        array_walk($content, function ($entry) use ($needleFields) {
            $fieldsToInsert = [];
            array_walk($entry, function ($entryField, $index) use ($fieldsToInsert, $needleFields) {
                if (in_array($index, $needleFields) && !empty($entryField)) {
                    $fieldsToInsert[] = $entryField;
                }
            });

            $fieldsToInsert[] = date("Y-m-d");
            $query = "INSERT INTO `market_data` (id_value, price, is_noon, update_date) VALUES (?, ?, ?, ?)";
            Adapter::getInstance()->exec($query, $fieldsToInsert);
        });

        Logger::getInstance()->info("File parsing is finished");
    }
}
