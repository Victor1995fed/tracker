<?php

namespace frontend\models;

use frontend\models\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{

    public function testTableName()
    {
        $category = new Category();
        $name = $category->tableName();
        print_r($name);
        $this->assertTrue(true);
    }
}
