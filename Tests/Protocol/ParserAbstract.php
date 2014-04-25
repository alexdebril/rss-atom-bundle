<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25/04/14
 * Time: 23:56
 */

namespace Debril\RssAtomBundle\Tests\Protocol;


class ParserAbstract extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function getDefaultFormats()
    {
        return
            array(
                array(
                    array(
                        \DateTime::RFC3339,
                        \DateTime::RSS,
                    )
                )
            );
    }

} 