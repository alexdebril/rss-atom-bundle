<?php
/**
 *  file name      : ProtocolTest.php
 *  file creation  : 25 déc. 2012
 *
 * @author : Alexandre Debril <alex.debril@gmail.com>
 */

namespace Debril\RssBundle\Tests\Protocol;

use Debril\RssBundle\Protocol\FeedReader;
use Debril\RssBundle\Protocol\FeedContent;
use Debril\RssBundle\Protocol\Parser\RssParser;
use \DateTime;

class RssReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testParserDetection()
    {
        $reader = new FeedReader();
        date_default_timezone_set('GMT');
        $date = DateTime::createFromFormat("Y-m-d H:i:s", "2012-12-25 22:04:00");
        $url = 'http://feeds2.feedburner.com/androidcentral';

        $response = $reader->getResponse($url, $date);
        $parser = $reader->getAccurateParser(new \SimpleXMLElement($response->getBody()));

        $this->assertTrue($parser instanceof RssParser);
    }

    public function testResponse()
    {
        $reader = new FeedReader();
        date_default_timezone_set('GMT');
        $date = DateTime::createFromFormat("Y-m-d H:i:s", "2012-12-25 22:04:00");
        $url = 'http://feeds2.feedburner.com/androidcentral';

        $response = $reader->getResponse($url, $date);
        $this->assertTrue($response instanceof \HttpMessage, '$response is a HttpMessage');
    }

    public function testParser()
    {
        $reader = new RssReader();
        date_default_timezone_set('GMT');
        $date = DateTime::createFromFormat("Y-m-d H:i:s", "2012-12-25 22:04:00");
        $url = 'http://feeds2.feedburner.com/androidcentral';

        $response = $reader->getResponse($url, $date);
        $xmlBody = new \SimpleXMLElement($response->getBody());
        $parser = $reader->getAccurateParser($xmlBody);

        $feedContent = $parser->parse($xmlBody);

        $this->assertTrue($feedContent instanceof FeedContent, '$feedContent is a FeedContent');
    }

}