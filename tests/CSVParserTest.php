<?php

use DanAbrey\DynastyProcess\CSVParser;
use PHPUnit\Framework\TestCase;

class CSVParserTest extends TestCase
{
    private CSVParser $service;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->service = new CSVParser();
    }

    public function testPlayers()
    {
        $ids = file_get_contents(__DIR__ .'/_data/db_playerids.csv');
        $result = $this->service->parseIds($ids);

        $this->assertCount(10502, $result);
    }

    public function testParseIdsAndValues()
    {

        $ids = file_get_contents(__DIR__ .'/_data/db_playerids.csv');
        $values = file_get_contents(__DIR__ .'/_data/values.csv');

        $result = $this->service->parseIdsAndValues($ids, $values);

        $this->assertCount(365, $result);

        $player = $result[3];
        $this->assertEquals('Ezekiel Elliott', $player['player']);
        $this->assertEquals('RB', $player['pos']);
        $this->assertEquals('DAL', $player['team']);
        $this->assertEquals('24.9', $player['age']);
        $this->assertEquals('2016', $player['draft_year']);
        $this->assertEquals('5.1', $player['ecr_1qb']);
        $this->assertEquals('6.6', $player['ecr_2qb']);
        $this->assertEquals('3.7', $player['ecr_pos']);
        $this->assertEquals('9314', $player['value_1qb']);
        $this->assertEquals('8990', $player['value_2qb']);
        $this->assertEquals('2020-06-25', $player['scrape_date']);
        $this->assertEquals('15498', $player['fp_id']);
        $this->assertEquals('12625', $player['mfl_id']);
    }
}