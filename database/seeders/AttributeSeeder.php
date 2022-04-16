<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('attributes')->insert([
            [
                'attribute_id'=>4,
                'entity_id'=>2,
                'attribute_code'=>'name',
                'backend_type'=>'static',
                'frontend_type'=>'input',
                'frontend_label'=>'name',
                'default_value'=>0,
                'is_filterable'=>1,
                'is_searchable'=>1,
                'is_required'=>1,

            ],
            [
                'attribute_id'=>5,
                'entity_id'=>2,
                'attribute_code'=>'content',
                'backend_type'=>'static',
                'frontend_type'=>'input',
                'frontend_label'=>'content',
                'default_value'=>0,
                'is_filterable'=>1,
                'is_searchable'=>1,
                'is_required'=>1,

            ],
            [
                'attribute_id'=>6,
                'entity_id'=>2,
                'attribute_code'=>'main_image',
                'backend_type'=>'static',
                'frontend_type'=>'input',
                'frontend_label'=>'main_image',
                'default_value'=>0,
                'is_filterable'=>1,
                'is_searchable'=>1,
                'is_required'=>1,

            ],
            [
                'attribute_id'=>7,
                'entity_id'=>2,
                'attribute_code'=>'tags',
                'backend_type'=>'static',
                'frontend_type'=>'input',
                'frontend_label'=>'tags',
                'default_value'=>0,
                'is_filterable'=>1,
                'is_searchable'=>1,
                'is_required'=>1,
            ],
        ]);
    }
}
