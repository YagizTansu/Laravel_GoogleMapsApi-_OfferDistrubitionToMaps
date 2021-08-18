<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = array(
            array('code'=>'AX','id' => 1,'name' => 'Aland Islands'),
            array('code'=>'AL','id' => 2,'name' => 'Albania'),
            array('code'=>'AD','id' => 3,'name' => 'Andorra'),
            array('code'=>'AT','id' => 4,'name' => 'Austria'),
            array('code'=>'BY','id' => 5,'name' => 'Belarus'),
            array('code'=>'BE','id' => 6,'name' => 'Belgium'),
            array('code'=>'BA','id' => 7,'name' => 'Bosnia and Herzegovina'),
            array('code'=>'BG','id' => 8,'name' => 'Bulgaria'),
            array('code'=>'HR','id' => 9,'name' => 'Croatia'),
            array('code'=>'CZ','id' => 10,'name' => 'Czech Republic'),
            array('code'=>'DK','id' => 11,'name' => 'Denmark'),
            array('code'=>'EE','id' => 12,'name' => 'Estonia'),
            array('code'=>'FO','id' => 13,'name' => 'Faroe Islands'),
            array('code'=>'FI','id' => 14,'name' => 'Finland'),
            array('code'=>'FR','id' => 15,'name' => 'France'),
            array('code'=>'DE','id' => 16,'name' => 'Germany'),
            array('code'=>'GI','id' => 17,'name' => 'Gibraltar'),
            array('code'=>'GR','id' => 18,'name' => 'Greece'),
            array('code'=>'GG','id' => 19,'name' => 'Guernsey'),
            array('code'=>'VA','id' => 20,'name' => 'Holy See (Vatican City State)'),
            array('code'=>'HU','id' => 21,'name' => 'Hungary'),
            array('code'=>'IS','id' => 22,'name' => 'Iceland'),
            array('code'=>'IE','id' => 23,'name' => 'Ireland'),
            array('code'=>'IM','id' => 24,'name' => 'Isle of Man'),
            array('code'=>'IT','id' => 25,'name' => 'Italy'),
            array('code'=>'JE','id' => 26,'name' => 'Jersey'),
            array('code'=>'XK','id' => 27,'name' => 'Kosovo'),
            array('code'=>'LV','id' => 28,'name' => 'Latvia'),
            array('code'=>'LI','id' => 29,'name' => 'Liechtenstein'),
            array('code'=>'LT','id' => 30,'name' => 'Lithuania'),
            array('code'=>'LU','id' => 31,'name' => 'Luxembourg'),
            array('code'=>'MK','id' => 32,'name' => 'Macedonia, the Former Yugoslav Republic of'),
            array('code'=>'MT','id' => 33,'name' => 'Malta'),
            array('code'=>'MD','id' => 34,'name' => 'Moldova, Republic of'),
            array('code'=>'MC','id' => 35,'name' => 'Monaco'),
            array('code'=>'ME','id' => 36,'name' => 'Montenegro'),
            array('code'=>'NL','id' => 37,'name' => 'Netherlands'),
            array('code'=>'NO','id' => 38,'name' => 'Norway'),
            array('code'=>'PL','id' => 39,'name' => 'Poland'),
            array('code'=>'PT','id' => 40,'name' => 'Portugal'),
            array('code'=>'RO','id' => 41,'name' => 'Romania'),
            array('code'=>'SM','id' => 42,'name' => 'San Marino'),
            array('code'=>'RS','id' => 43,'name' => 'Serbia'),
            array('code'=>'CS','id' => 44,'name' => 'Serbia and Montenegro'),
            array('code'=>'SK','id' => 45,'name' => 'Slovakia'),
            array('code'=>'SI','id' => 46,'name' => 'Slovenia'),
            array('code'=>'ES','id' => 47,'name' => 'Spain'),
            array('code'=>'SJ','id' => 48,'name' => 'Svalbard and Jan Mayen'),
            array('code'=>'SE','id' => 49,'name' => 'Sweden'),
            array('code'=>'CH','id' => 50,'name' => 'Switzerland'),
            array('code'=>'UA','id' => 51,'name' => 'Ukraine'),
            array('code'=>'GB','id' => 52,'name' => 'United Kingdom')
        );

          for ($i=0; $i < 52 ; $i++) {
                  DB::table('countries')->insert([
                      "code" =>$countries[$i]['code'],
                      "name" => $countries[$i]['name'],
                  ]);
          }
    }
}
