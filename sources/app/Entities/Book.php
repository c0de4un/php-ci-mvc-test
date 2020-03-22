<?php namespace App\Entities;

use CodeIgniter\Entity;

class Book extends Entity
{ // @TODO Лучше бы отделить запись и чтение (отдельные интерфейсы по SOLID).

    protected $text = 'This Book Is Empty';
    protected $name = 'Untitled';

    public function __construct(string $name, array $data = null)
    {
        parent::__construct($data);
        $this->name = $name;
    }

    public function get_name() : string
    {
        return $this->name;
    }

    public function set_text(string $text)
    {
        $this->text = $text;
    }

    public function get_text() : string
    {
        return $this->text;
    }

    public function append_text(string $text)
    {
        $this->text .= $text;
    }
}

?>