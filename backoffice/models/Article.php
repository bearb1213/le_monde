<?php

class Article
{
    public $id;
    public $titre;
    public $html;
    public $date_publication;
    public $auteur;
    public $url;
    public $images;


    public function __construct($id = null, $titre = null, $html = null, $date_publication = null, $auteur = null)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->html = $html;
        $this->date_publication = $date_publication;
        $this->auteur = $auteur;
        $this->images = [];
    }

    public static function fromArray($row)
    {
        $a = new self();
        $a->id = isset($row['id']) ? (int)$row['id'] : null;
        $a->titre = isset($row['titre']) ? $row['titre'] : null;
        $a->html = isset($row['html']) ? $row['html'] : null;
        $a->date_publication = isset($row['date_publication']) ? $row['date_publication'] : null;
        $a->auteur = isset($row['auteur']) ? (int)$row['auteur'] : null;
        return $a;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'html' => $this->html,
            'date_publication' => $this->date_publication,
            'auteur' => $this->auteur,
        ];
    }
}
