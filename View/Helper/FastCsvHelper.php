<?php

class FastCsvHelper extends AppHelper {

    public $handle;
    public $table = array();
    public $delimiter = ',';
    public $enclosure = '"';
    public $filename = 'ExportFile.csv';
    public $to_encoding = 'sjis';
    public $from_encoding = 'utf8';

    public function FastCsvHelper() {
        $this->init();
    }

    public function init() {
        $this->handle = fopen('php://output', 'w');
    }

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

    public function formatTable($table, $modelClass) {
        foreach ($table as $row) {
            $this->addRow($row[$modelClass]);
        }
    }

    public function setTable($table) {
        $this->table = $table;
    }

    public function addRows($table) {
        $this->setTable($table);
    }

    public function addRow($row) {
        $this->table[] = $row;
    }

    public function addFirstRow($row) {
        array_unshift($this->table, $row);
    }

    public function addLastRow($row) {
        $this->addRow($row);
    }

    public function setFilename($filename) {
        $this->filename = $filename;
        if (strtolower(substr($this->filename, -4)) != '.csv') {
            $this->filename .= '.csv';
        }
    }

    public function setHeaders() {
        //header("Content-type: application/vnd.ms-excel");
        //header('Content-Type: application/x-csv');
        header("Content-type: text/csv");
        header("Content-disposition: attachment;filename=" . $this->filename);
    }

    public function export() {
        foreach ($this->table as $row) {
            fputcsv($this->handle, $row, $this->delimiter, $this->enclosure);
        }
        $this->setHeaders();
        $contents = stream_get_contents($this->handle);
        $contents = mb_convert_encoding($contents, $this->to_encoding, $this->from_encoding);
        return $this->output($contents);
    }

}

