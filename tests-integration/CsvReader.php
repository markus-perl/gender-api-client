<?php


class CsvReader implements \Iterator, \Countable
{

    private $mimeTypes = array(
        'text/csv',
        'text/plain',
        'application/csv',
        'text/comma-separated-values',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'text/anytext',
        'application/octet-stream',
        'application/txt',
        'text/x-fortran', //http://stackoverflow.com/questions/16190929/detecting-a-mime-type-fails-in-php
    );

    /**
     * Zeigt auf die CSV Datei
     *
     * @var resource
     */
    private $_file;

    /**
     * Maximale Größe eines Datensatzes
     *
     * @var int
     */
    const MAX_LINE_LENGTH = 8192;

    /**
     * @var string
     */
    private $_filePath;

    /**
     * @var bool
     */
    private $_isGzip = false;

    /**
     * Aktueller Index
     *
     * @var int
     */
    private $_index = 1;

    /**
     * Delimiter
     *
     * @var string
     */
    private $_delimiter;

    /**
     * Spaltennamen
     *
     * @var array
     */
    private $_labels;

    /**
     * @var int
     */
    private $_count;

    /**
     * Strict parsing mode
     *
     * @var bool
     */
    private $_strict = false;

    /**
     * @param $file
     * @throws Exception
     * @return string
     */
    private function _removeBom()
    {
        if ($this->_isGzip) {
            return false;
        }

        $content = file_get_contents($this->_filePath);

        if (false === $content) {
            throw new \Exception('cannot open file ' . $this->_filePath);
        }

        $content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1, UTF16', true));

        //remove bom http://de.wikipedia.org/wiki/Byte_Order_Mark
        if (substr($content, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
            $content = mb_substr($content, 1);
        }

        $this->_file = tmpfile();
        fwrite($this->_file, $content);
        return true;
    }


    /**
     * Constructor
     *
     * @param string $file CSV Datei
     * @param string $delimiter
     */
    public function __construct($file, $delimiter = 'auto')
    {
        ini_set("auto_detect_line_endings", true);

        $this->_filePath = $file;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $type = $finfo->file($file);

        if ($type == 'application/x-gzip') {
            $this->_isGzip = true;
        } else {
            if (!in_array($type, $this->mimeTypes)) {
                throw new \Exception('Wrong mime type: ' . $type . '. The file is not a valid CSV file.');
            }
        }

        if ($delimiter == 'auto') {
            $delimiter = $this->detectDelimiter();
        }

        $this->_delimiter = $delimiter;

        if (false == $this->_removeBom()) {
            $this->_file = $this->fopen();
        }

        $this->rewind();

        if (false === $this->_file) {
            throw new \Exception('cannot open file ' . $file);
        }

        $this->_index++;
    }

    /**
     * @return resource
     */
    private function fopen()
    {
        if ($this->_isGzip) {
            return gzopen($this->_filePath, 'r');
        }

        return fopen($this->_filePath, 'r');
    }

    /**
     * @param $handle
     * @return bool
     */
    private function fclose($handle)
    {
        if ($this->_isGzip) {
            return gzclose($handle);
        }

        return fclose($handle);
    }

    /**
     * @return string
     */
    public function detectDelimiter()
    {
        $delimiters = array(
            ';' => 0,
            ',' => 0,
            "\t" => 0,
            "|" => 0
        );

        $handle = $this->fopen();

        if (false === $handle) {
            throw new \Exception('cannot open file ' . $this->_filePath);
        }
        $firstLine = fgets($handle);

        $this->fclose($handle);

        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }

    /**
     * @return int
     */
    public function count()
    {
        // return max(0, count(file($this->_filePath)) - 1);

        $handle = $this->fopen();
        $count = 0;

        while (!feof($handle)) {
            if (fgets($handle)) {
                $count++;
            }
        }

        $this->fclose($handle);

        return max(0, $count - 1);

    }

    /**
     * Iterator Funktion zum zurücksetzen des Arrays
     *
     */
    public function rewind()
    {
        $this->_index = 1;

        if (is_resource($this->_file)) {
            rewind($this->_file);
            $labels = fgetcsv($this->_file, self::MAX_LINE_LENGTH, $this->_delimiter);

            $this->_labels = $labels;
        }
    }

    /**
     * Iterator Funktion, gibt das aktuelle Element zurück
     *
     * @return array
     */
    public function current()
    {
        if (is_resource($this->_file)) {
            $values = $this->mapLabelsToValues(fgetcsv($this->_file, self::MAX_LINE_LENGTH, $this->_delimiter));
            return $values;
        }

        return null;
    }

    /**
     * Mapt die Labels auf die Datensätze
     *
     * @param mixed $data
     * @return mixed
     */
    private function mapLabelsToValues($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $mapped = array();

        foreach ($this->_labels as $i => $label) {
            $mapped[$i] = null;
        }

        foreach ($data as $i => $field) {
            if (false === isset($this->_labels[$i])) {

                //mehr Daten als Felder
                if (!$this->_strict) {
                    continue;
                }
                throw new \Exception('Parsing failed, label count does not match field count: ' . print_r($data, true));
            }

            if (mb_check_encoding($field, 'UTF-8') == false) {
                $field = utf8_encode($field);
            }

            $mapped[$i] = $field;
        }

        if (count($this->_labels) != count($mapped)) {
            $msg = 'Parsing failed, label count does not match field count. We expected ' . count($this->_labels) . ' fields, got ' . count($mapped) . ' fields.' . PHP_EOL;
            $msg .= 'Expected structure: ' . print_r($this->_labels, true) . PHP_EOL;
            $msg .= 'Got: ' . print_r($mapped, true);
            throw new \Exception($msg);
        }

        $result = array();
        foreach ($this->_labels as $id => $label) {
            if (isset($mapped[$id])) {
                $result[$label] = $mapped[$id];
            }
        }

        return $result;
    }

    /**
     * Iterator Funktion, gibt den aktuellen Index zurück
     *
     * @return int
     */
    public function key()
    {
        return $this->_index;
    }

    /**
     * Zum nächsten Element springen
     *
     * @return boolean true wenn noch ein element vorhanden ist
     */
    public function next($index = true)
    {
        if (is_resource($this->_file)) {
            if ($index) {
                ++$this->_index;
            }
            return !feof($this->_file);
        }
        return false;
    }

    /**
     * Iterator Funktion, prüft ob Element gültig
     *
     * @return boolean
     */
    public function valid()
    {
        if (!$this->next(false)) {
            if (is_resource($this->_file)) {
                $this->fclose($this->_file);
            }
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->_delimiter;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->_delimiter = $delimiter;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->_labels;
    }

}