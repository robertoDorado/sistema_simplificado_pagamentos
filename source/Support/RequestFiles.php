<?php
namespace Source\Support;

/**
 * RequestFiles Source\Support
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Support
 */
class RequestFiles
{
    /** @var array Armazena a variavel global $_FILES */
    protected $files;

    /**
     * RequestFiles constructor
     */
    public function __construct()
    {
        $this->files = $_FILES;
    }

    public function getFile($key) {
        if (isset($this->files[$key])) {
            return $this->files[$key];
        } else {
            throw new \Exception('Arquivo '. $key .' não encontrado');
        }
    }

    public function getAllFiles() {
        return $this->files;
    }
}
