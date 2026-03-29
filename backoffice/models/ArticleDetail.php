<?php

class ArticleDetail
{
    public $id;
    public $article_id;
    public $details; // references another article id per schema

    public function __construct($id = null, $article_id = null, $details = null)
    {
        $this->id = $id;
        $this->article_id = $article_id;
        $this->details = $details;
    }

    public static function fromArray($row)
    {
        $d = new self();
        $d->id = isset($row['id']) ? (int)$row['id'] : null;
        $d->article_id = isset($row['article_id']) ? (int)$row['article_id'] : null;
        $d->details = isset($row['details']) ? (int)$row['details'] : null;
        return $d;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'article_id' => $this->article_id,
            'details' => $this->details,
        ];
    }
}
