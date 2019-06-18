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

        foreach ($content as $row) {
            $idValue = $row[0];
            $price = $row[1];
            $isNoon = $row[5];
            $date = date("Y-m-d");

            $query = "INSERT INTO `market_data` (id_value, price, is_noon, update_date) VALUES (?, ?, ?, ?)";
            Adapter::getInstance()->exec($query, [$idValue, $price, $isNoon, $date]);
        }

        Logger::getInstance()->info("File parsing is finished");
    }
}
