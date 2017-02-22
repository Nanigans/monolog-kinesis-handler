<?php

namespace CascadeEnergy\Tests\Monolog;

use CascadeEnergy\Monolog\Formatter\KinesisFormatter;

class KinesisFormatterTest extends \PHPUnit_Framework_TestCase
{
    /** @var KinesisFormatter */
    private $formatter;

    public function setUp()
    {
        $this->formatter = new KinesisFormatter();
    }

    public function testItShouldFormatASingleRecordAsParametersForAKinesisPutRecordCall()
    {
        $record = array('foo' => 'bar', 'channel' => 'channelName');

        $result = $this->formatter->format($record);

        $this->assertEquals(
            array(
                'Data' => json_encode($record),
                'PartitionKey' => 'channelName'
            ),
            $result
        );
    }

    public function testItShouldFormatABatchOfRecordsAsParametersForPutRecords()
    {
        $recordList = array(
            array('foo' => 'bar', 'channel' => 'channelNameFoo'),
            array('baz' => 'qux', 'channel' => 'channelNameBaz'),
        );

        $result = $this->formatter->formatBatch($recordList);

        $this->assertEquals(
            array(
                'Records' => array(
                    array('Data' => json_encode($recordList[0]), 'PartitionKey' => 'channelNameFoo'),
                    array('Data' => json_encode($recordList[1]), 'PartitionKey' => 'channelNameBaz'),
                )
            ),
            $result
        );
    }
}
