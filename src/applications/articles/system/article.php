<?php

namespace Applications\Articles\System;

use System\Structures\DBEntity;

class Article extends DBEntity {

    protected static $table = "articles";

    public static function GetFeatured() {
        return (new Article(\System\DB::I()->Query("articles.id as `id` FROM articles INNER JOIN article_settings WHERE article_settings.name = 'featured' AND article_settings.value = articles.id AND articles.active = '1' AND articles.published = '1' LIMIT 1", array(
        ), false)->id));
    }

    public function __construct($id) {
        return $this->Load("* FROM articles WHERE id = :id LIMIT 1", array(
            ":id" => $id
        ));
    }
}