<?php
namespace DanAbrey\DynastyProcess;


use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Tightenco\Collect\Support\Collection;

class CSVParser
{
    public function parseIds(string $idsCsv)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        return $serializer->decode($idsCsv, 'csv');
    }

    /**
     * Given the IDs and Values CSVs from Dynasty Process, combine them into one collection including MFL/FP IDs and values/ECR
     * @param string $idsCsv
     * @param string $valuesCsv
     * @return array
     */
    public function parseIdsAndValues(string $idsCsv, string $valuesCsv): array
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $decodedIdsCsv = new Collection($serializer->decode($idsCsv, 'csv'));
        $decodedValuesCsv = new Collection($serializer->decode($valuesCsv, 'csv'));
        $ids = $decodedIdsCsv
            ->filter(function($el) {
                return $el['fantasypros_id'] !== 'NA';
            })
            ->map(function($el) {
                return [
                    'mfl_id' => $el['mfl_id'],
                    'fp_id' => $el['fantasypros_id'],
                    'fantasy_data_id' => $el['fantasy_data_id'],
                ];
            })
            ->keyBy('fp_id');
        $values = $decodedValuesCsv
            ->filter(function($el) {
                // Filter out draft picks
                return $el['fp_id'] !== 'NA';
            })
            ->map(function($el) use ($ids) {
                if (!isset($ids[$el['fp_id']])) {
                    return false;
                }
                $mflId = $ids[$el['fp_id']]['mfl_id'];
                $el['mfl_id'] = $mflId;

                $fantasyDataId = $ids[$el['fp_id']]['fantasy_data_id'];
                $el['fantasy_data_id'] = $fantasyDataId;
                return $el;
            })
            // Reject all 'false' - which are players in the values CSV that have FP ids that are not in the ids database (this happens sometimes for some reason, e.g. Cam Newton)
            ->reject(function ($value) {
                return $value === false;
            });

        return $values->toArray();
    }
}