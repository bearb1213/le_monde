<?php

class ArticleImage
{
    public $id;
    public $article_id;
    public $chemin;
    public $alt;

    public function __construct($id = null, $article_id = null, $chemin = null, $alt = null)
    {
        $this->id = $id;
        $this->article_id = $article_id;
        $this->chemin = $chemin;
        $this->alt = $alt;
    }

    public static function fromArray($row)
    {
        $i = new self();
        $i->id = isset($row['id']) ? (int)$row['id'] : null;
        $i->article_id = isset($row['article_id']) ? (int)$row['article_id'] : null;
        $i->chemin = isset($row['chemin']) ? $row['chemin'] : null;
        $i->alt = isset($row['alt']) ? $row['alt'] : null;
        return $i;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'article_id' => $this->article_id,
            'chemin' => $this->chemin,
            'alt' => $this->alt,
        ];
    }
}
