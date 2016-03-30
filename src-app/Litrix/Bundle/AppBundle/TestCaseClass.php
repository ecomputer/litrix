<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 31/12/15
 * Time: 15:43
 */


namespace Litrix\Bundle\AppBundle;

class TestCaseClass extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testMethod($data)
    {
        $this->assertTrue($data);
    }

    public function provider()
    {
        return array(
            'my named data' => array(true),
            'my data'       => array(true)
        );
    }
}
