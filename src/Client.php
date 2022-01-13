<?php
namespace DanAbrey\DynastyProcess;

use DanAbrey\DynastyProcess\Models\DynastyProcessPlayer;
use DanAbrey\DynastyProcess\Models\DynastyProcessPlayerValue;
use DanAbrey\DynastyProcess\Models\DynastyProcessValue;
use DanAbrey\MFLApi\Models\MFLPlayer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Tightenco\Collect\Support\Collection;

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
     * @return array|DynastyProcessPlayer[]
     */
    public function getPlayers(): array
    {
        $parser = new CSVParser();
        $players = $parser->parseIds($this->getIdsCSV());
        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers);
        return $serializer->denormalize($players, DynastyProcessPlayer::class . '[]');
    }

    /**
     * @return array|DynastyProcessPlayerValue[]
     * @deprecated 1.6 Will be replaced by more accurately named getPlayerValues() in version 2.0.
     */
    public function getValues(): array
    {
        $parser = new CSVParser();
        $parsed = $parser->parseIdsAndValues($this->getIdsCSV(), $this->getValuesCSV());
        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers);
        return $serializer->denormalize($parsed, DynastyProcessPlayerValue::class . '[]');
    }

    /**
     * @return array|DynastyProcessPlayerValue[]
     */
    public function getPlayerValues(): array
    {
        return $this->getValues();
    }

    /**
     * @return array|DynastyProcessPlayerValue[]
     */
    public function getAllValues(): array
    {
        $parser = new CSVParser();
        $parsed = $parser->parseValues($this->getValuesCSV());
        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers);
        return $serializer->denormalize($parsed, DynastyProcessValue::class . '[]');
    }
}