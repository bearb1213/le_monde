<?php

class Article
{
    public $id;
    public $titre;
    public $html;

    public function __construct($id = null, $titre = null, $html = null)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->html = $html;
    }

    public static function fromArray($row)
    {
        $a = new self();
        $a->id = isset($row['id']) ? (int)$row['id'] : null;
        $a->titre = isset($row['titre']) ? $row['titre'] : null;
        $a->html = isset($row['html']) ? $row['html'] : null;
        return $a;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'html' => $this->html,
        ];
    }
}
