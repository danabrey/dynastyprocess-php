<?php
namespace DanAbrey\DynastyProcess\Models;

class DynastyProcessPlayer
{
    public string $name;
    public string $position;
    public string $merge_name;
    public string $mfl_id;
    public string $sportradar_id;
    public string $gsis_id;
    public string $fantasypros_id;
    public string $ffcalculator_id;
    public string $pfr_id;
    public string $cfbref_id;
    public string $sleeper_id;
    public string $espn_id;
    public string $stats_global_id;
    public string $fantasy_data_id;
    public string $team;
    /**
     * @deprecated Replaced by $birthdate in May 2021 of https://raw.githubusercontent.com/dynastyprocess/data/master/files/db_playerids.csv
     * Age still exists in the values.csv sheet
     */
    public ?string $age = null;
    public string $birthdate;
    public string $draft_year;
    public string $draft_round;
    public string $draft_pick;
}