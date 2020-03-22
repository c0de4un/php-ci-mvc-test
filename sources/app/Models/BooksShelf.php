<?php namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Book;

class BooksShelf extends Model
{
    /** Текущая книга. ** */
    protected $book;

    /**
     * Конвертирует название книги в путь файла.
    **/
    private static function parse_book_path(string $book)
    { return strtolower($book).'txt'; }

    /**
     * Возвращает указатель (ссылку) на текущую открытую книгу.
     * Позволяет изюежать случайного изменения книги из-вне.
     *
     * @return Book - книга, может быть null, елси не бала открыта.
    **/
    public function get_book() : Book
    { return $this->book; }

    /**
     * Достать книгу из шкафа (прочитать).
     * Результат хранится в переменной этой модели 'book'.
     *
     * @param $book - название книги (без формата/расширения).
     * @return true если успешно
     * @return false если нет.
    **/
    public function open_book($book) : bool
    {
        if (empty($this->book) == false)
        { unset($this->book); } // Указатель (ссылка) теперь null, GC удалить объект.

        $book_path = self::parse_book_path($book);
        if (is_readable($book_path))
        {
            $handle = fopen($book, "r");
            try
            {
                $this->book = new Book($book);
                $this->book->set_text(fread($handle, filesize($book_path))); // Естественно, если книга большая, может провалиться.
            }
            finally
            { fclose($handle); }

            return true;
        }

        return false;
    }

    /**
     * Добавляет данные в книгу.
     *
     * (!) Не сохраняет данные в файл. Для этого нужно отдельно вызвать @see put_book
     *
     * @param $text - доп. текст для книги.
     * @return true - если успешно.
    **/
    public function append_books_text($text) : bool
    {
        if (empty($this->book) == false)
        {
            $this->book->append_text($text);
            return true;
        }

        return false;
    }

    /**
     * Положить книгу в шкаф.
     * Сохраняет изменения.
    **/
    public function put_book()
    {
        if (empty($this->book) == false)
        {
            $book_path = self::parse_book_path($this->book->get_name());

            $handle = fopen($book_path, "w");

            try
            { fwrite($handle, $this->book->get_text()); }
            finally
            { fclose($handle); }
        }
    }

}

?>