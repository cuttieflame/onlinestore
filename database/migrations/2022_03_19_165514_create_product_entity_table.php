<?php

use Eav\Entity;
use Eav\Attribute;
use Eav\AttributeSet;
use Eav\AttributeGroup;
use Eav\EntityAttribute;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $entity = Entity::create([
        	'entity_code' => 'product',
        	'entity_class' => '\\App\\Products',
        	'entity_table' => 'products',
        ]);


        $attributeSet = AttributeSet::create([
        	'attribute_set_name' => 'Default',
        	'entity_id' => $entity->entity_id,
        ]);

        $entity->default_attribute_set_id = $attributeSet->attribute_set_id;
        $entity->save();

        $attributeGroup = AttributeGroup::create([
        	'attribute_set_id' => $attributeSet->attribute_set_id,
        	'attribute_group_name' => 'General',
        ]);

        $this->addTimeStampAttributes();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->removeTimeStampAttributes();


		Schema::drop('product_boolean');

		Schema::drop('product_date');

		Schema::drop('product_datetime');

		Schema::drop('product_double');

		Schema::drop('product_integer');

		Schema::drop('product_text');

		Schema::drop('product_string');


        $entity = Entity::where('entity_code', '=', 'product');
        $attributeSet = AttributeSet::where('attribute_set_name', '=', 'Default')
        				->where('entity_id', '=', $entity->first()->entity_id);
        $attributeGroup = AttributeGroup::where('attribute_set_id', '=', $attributeSet->first()->attribute_set_id)
        				->where('attribute_group_name', '=', 'General');


        $attributeGroup->delete();
        $attributeSet->delete();
        $entity->delete();

    }


    protected function addTimeStampAttributes()
    {
        Attribute::add([
            'attribute_code' => 'created_at',
            'entity_code' => 'product',
            'backend_class' => NULL,
            'backend_type' => 'static',
            'backend_table' =>  NULL,
            'frontend_class' =>  NULL,
            'frontend_type' => 'input',
            'frontend_label' => ucwords(str_replace('_',' ','created_at')),
            'source_class' =>  NULL,
            'default_value' => '',
            'is_required' => 0,
            'required_validate_class' =>  NULL
        ]);

        EntityAttribute::map([
            'attribute_code' => 'created_at',
            'entity_code' => 'product',
            'attribute_set' => 'Default',
            'attribute_group' => 'General'
        ]);

        Attribute::add([
            'attribute_code' => 'updated_at',
            'entity_code' => 'product',
            'backend_class' => NULL,
            'backend_type' => 'static',
            'backend_table' =>  NULL,
            'frontend_class' =>  NULL,
            'frontend_type' => 'input',
            'frontend_label' => ucwords(str_replace('_',' ','updated_at')),
            'source_class' =>  NULL,
            'default_value' => '',
            'is_required' => 0,
            'required_validate_class' =>  NULL
        ]);

        EntityAttribute::map([
            'attribute_code' => 'updated_at',
            'entity_code' => 'product',
            'attribute_set' => 'Default',
            'attribute_group' => 'General'
        ]);


    }

    protected function removeTimeStampAttributes()
    {
        EntityAttribute::unmap([
            'attribute_code' => 'created_at',
            'entity_code' => 'product',
        ]);

        Attribute::remove([
            'attribute_code' => 'created_at',
            'entity_code' => 'product',
        ]);

        EntityAttribute::unmap([
            'attribute_code' => 'updated_at',
            'entity_code' => 'product',
        ]);

        Attribute::remove([
            'attribute_code' => 'updated_at',
            'entity_code' => 'product',
        ]);
    }
}
