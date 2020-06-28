<?php
namespace DanAbrey\DynastyProcess;

use DanAbrey\DynastyProcess\Models\DynastyProcessPlayerValue;
use DanAbrey\MFLApi\Models\MFLPlayer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Client
{
    private const IDS_CSV_URL = 'https://github.com/tanho63/dynastyprocess/raw/master/files/db_playerids.csv';
    private const VALUES_CSV_URL = 'https://github.com/tanho63/dynastyprocess/raw/master/files/values.csv';

    protected function getIdsCSV()
    {
        $csv = file_get_contents(self::IDS_CSV_URL);
        return $csv;
    }

    protected function getValuesCSV()
    {
        $csv = file_get_contents(self::VALUES_CSV_URL);
        return $csv;
    }

    /**
     * @return array|DynastyProcessPlayerValue[]
     */
    public function getValues(): array
    {
        $parser = new CSVParser();
        $parsed = $parser->parseIdsAndValues($this->getIdsCSV(), $this->getValuesCSV());
        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers);
        return $serializer->denormalize($parsed, DynastyProcessPlayerValue::class . '[]');
    }
}