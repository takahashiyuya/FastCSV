<?php
App::uses('Controller', 'Controller');
App::uses('AppHelper', 'View/Helper');
App::uses('FastCSVHelper', 'FastCSV.View/Helper');

class FastCSVTestController extends Controller
{

    public $uses = null;
}

class FastCSVTestHelper extends FastCSVHelper
{

    public $path;

    public function setHeaders() {}

    public function export()
    {
        $this->path = TMP . 'tests' . DS . $this->filename;
        $this->handle = fopen($this->path, 'w');
        parent::export();
    }
}

class FastCSVHelperTest extends CakeTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->View = $this->getMock('View', array('append'), array(new FastCSVTestController()));
        $this->Helper = new FastCSVTestHelper($this->View);
    }

    public function tearDown()
    {
        parent::tearDown();
        if ($this->Helper->path) {
            fclose($this->Helper->handle);
            @unlink($this->Helper->path);
        }
    }

    public function test_fastExportWithModelClass()
    {
        $filename = 'user';
        $data = array(
            array(
                'User' => array(
                    'id' => 1,
                    'name' => "foo 'bar",
                    'created' => '2013-05-05 23:59:59',
                ),
            ),
            array(
                'User' => array(
                    'id' => 2,
                    'name' => 'hoge "moge',
                    'created' => '2013-05-15 03:09:49',
                ),
            ),
        );

        $this->Helper->fastExport($data, $filename, 'User');
        $csv = file($this->Helper->path);
        $this->assertEquals('1,"foo \'bar","2013-05-05 23:59:59"', trim($csv[0]));
        $this->assertEquals('2,"hoge ""moge","2013-05-15 03:09:49"', trim($csv[1]));
    }

    public function test_fastExportWithoutModelClass()
    {
        $data = array(
            array('id' => 2, 'name' => 'foo'),
            array('id' => 4, 'name' => 'bar'),
        );
        $this->Helper->fastExport($data);
        $csv = file($this->Helper->path);
        $this->assertEquals('2,foo', trim($csv[0]));
        $this->assertEquals('4,bar', trim($csv[1]));
    }

    public function test_setRow()
    {
        $fruits = array(
            array('いちご', '赤', 6),
            array('ぶどう', '紫', 4),
            array('ばなな', '黄', 20),
        );
        $fruit = array('りんご', '赤', 13);
        $thead = array('名前', '色', '大きさ');
        $tfoot = array('name', 'color', 'length');

        $this->Helper->setRows($fruits);
        $this->Helper->setRow($fruit);
        $this->Helper->setLastRow($tfoot);
        $this->Helper->setFirstRow($thead);
        $this->Helper->export();

        $csv = file($this->Helper->path);
        $this->assertEquals(6, count($csv));
        $this->assertEquals($this->conv('名前,色,大きさ'), trim($csv[0]));
        $this->assertEquals($this->conv('いちご,赤,6'), trim($csv[1]));
        $this->assertEquals($this->conv('name,color,length'), trim($csv[5]));
    }

    private function conv($var)
    {
        $to = $this->Helper->to_encoding;
        $from = $this->Helper->from_encoding;
        mb_convert_variables($to, $from, $var);

        return $var;
    }

    public function test_setFilename()
    {
        $this->Helper->setFilename('foo');
        $this->assertEquals('foo.csv', $this->Helper->filename);
        $this->Helper->setFilename('bar.csv');
        $this->assertEquals('bar.csv', $this->Helper->filename);
    }

}



