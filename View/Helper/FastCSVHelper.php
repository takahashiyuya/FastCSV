<?php

/**
 * This is the FastCSVHelper class file. 
 *
 * PHP 5
 *
 * Copyright 2012, Yuya Takahashi(@takahashiyuya).
 * Licensed under The MIT License
 *
 * @version       0.8
 * @copyright     Copyright 2012, Yuya Takahashi(@takahashiyuya).
 * @package       FastCSV.View.Helper
 * @since         2012-03-31
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Fast CSV Helper class for easy output a CSV file at high speed.
 *
 * @package       FastCSV.View.Helper
 * @link          https://github.com/takahashiyuya/FastCSV
 */
class FastCSVHelper extends AppHelper {

    /**
     * The file pointer must be valid, and must point to a file successfully opened by fopen() or fsockopen() (and not yet closed by fclose())
     * 
     * @var resource
     */
    protected $handle;

    /**
     * Array you want to CSV. 
     *
     * @var array
     */
    public $table = array();

    /**
     * The optional delimiter parameter sets the field delimiter (one character only). 
     *
     * @var string
     */
    public $delimiter = ',';

    /**
     * The optional enclosure parameter sets the field enclosure (one character only).  
     *
     * @var string
     */
    public $enclosure = '"';

    /**
     * The filename of exported CSV.
     *
     * @var string
     */
    public $filename = 'ExportFile.csv';

    /**
     * The type of encoding that str is being converted to.
     *
     * @var string
     */
    public $to_encoding = 'sjis';

    /**
     * Is specified by character code names before conversion. It is either an array, or a comma separated enumerated list. If from_encoding is not specified, the internal encoding will be used. 
     *
     * @var string
     */
    public $from_encoding = 'utf8';

    /**
     * Constructor
     */
    public function __construct(View $View, $settings = array()) {
        parent::__construct($View, $settings);
        $this->_init();
    }

    /**
     * Initialization, get resource.
     */
    public function _init() {
        $this->handle = fopen('php://output', 'w');
    }

    /**
     * Export a CSV fast and easy.
     * 
     * @param array $table Two-dimensional array or array was extracted with "CakePHP find()".
     * @param mixed $filename The filename of exported CSV.
     * @param mixed $modelClass The case of two-dimensional array was extracted with "CakePHP find()", to specify the name of that model. For example "User".
     * @return void
     */
    public function fastExport($table, $filename = null, $modelClass = false) {
        if ($filename) {
            $this->setFilename($filename);
        }
        if (is_string($modelClass)) {
            $this->formatTable($table, $modelClass);
        } else {
            $this->setTable($table);
        }
        $this->export();
    }

    /**
     * To be formatted into a common two-dimensional array, the array was extracted with "CakePHP find()".
     * 
     * @param array $table The array was extracted with "CakePHP find()".
     * @param type $modelClass The case of two-dimensional array was extracted with "CakePHP find()", to specify the name of that model. For example "User".
     * @return void
     */
    public function formatTable($table, $modelClass) {
        foreach ($table as $row) {
            $this->setRow($row[$modelClass]);
        }
    }

    /**
     * Set the Two-dimensional array.
     * 
     * @param array $table Common Two-dimensional array.
     * @return void
     */
    public function setTable($table = array()) {
        $this->table = $table;
    }

    /**
     * This is a function of lap setTable().
     * 
     * @param array $table Common Two-dimensional array.
     * @return void
     */
    public function setRows($table = array()) {
        $this->setTable($table);
    }

    /**
     * Set the one-dimensional array.
     *
     * @param array $row 
     * @return void
     */
    public function setRow($row = array()) {
        $this->table[] = $row;
    }

    /**
     * Set the one-dimensional array in the first line.
     *
     * @param array $row 
     * @return void
     */
    public function setFirstRow($row = array()) {
        array_unshift($this->table, $row);
    }

    /**
     * Set the one-dimensional array in the last line.
     * This is a function of lap setRow().
     *
     * @param array $row 
     * @return void
     */
    public function setLastRow($row = array()) {
        $this->setRow($row);
    }

    /**
     * Set the filename of the CSV.
     *
     * @param type $filename 
     * @return void
     */
    public function setFilename($filename) {
        $this->filename = $filename;
        if (strtolower(substr($this->filename, -4)) != '.csv') {
            $this->filename .= '.csv';
        }
    }

    /**
     * Set some headers.
     * 
     * @return void
     */
    public function setHeaders() {
        //header("Content-type: application/vnd.ms-excel");
        //header("Content-type: text/csv");
        header("Content-Type: application/x-csv");
        header("Content-disposition: attachment;filename=" . $this->filename);
    }

    /**
     * Export a CSV.
     * 
     * @return void
     */
    public function export() {
        $this->setFilename($this->filename);
        $this->setHeaders();
        mb_convert_variables($this->to_encoding, $this->from_encoding, $this->table);
        foreach ($this->table as $row) {
            fputcsv($this->handle, $row, $this->delimiter, $this->enclosure);
        }
    }

}