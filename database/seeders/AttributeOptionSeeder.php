<?php

namespace Database\Seeders;

use Eav\AttributeOption;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AttributeOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Faker::create();
        \DB::table('attribute_options')->truncate();
        for ($i = 1; $i <= 50; $i++) {
            $atr_options = new AttributeOption();
            $atr_options->attribute_id = 4;
            $atr_options->label = '';
            $atr_options->value = $this->faker->bs;
            $atr_options->product_id = $i;
            $atr_options->save();
        }
        for ($i = 1; $i <= 50; $i++) {
            $atr_options = new AttributeOption();
            $atr_options->attribute_id = 5;
            $atr_options->label = '';
            $atr_options->value = $this->faker->text($maxNbChars = 200);
            $atr_options->product_id = $i;
            $atr_options->save();
        }
        for ($i = 1; $i <= 50; $i++) {
            $atr_options = new AttributeOption();
            $atr_options->attribute_id = 6;
            $atr_options->label = '';
            $atr_options->value = 'images/HumKE711BtI.jpg';
            $atr_options->product_id = $i;
            $atr_options->save();
        }
        for ($i = 1; $i <= 50; $i++) {
            $a = $this->faker->words($nb = 7, $asText = false);
            $atr_options = new AttributeOption();
            $atr_options->attribute_id = 7;
            $atr_options->label = '';
            $atr_options->value = implode(' ',$a);
            $atr_options->product_id = $i;
            $atr_options->save();
        }
    }
}
