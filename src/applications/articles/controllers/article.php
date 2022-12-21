<?php

namespace Applications\Controllers\Articles;

use Applications\Articles\System\Article as SystemArticle;
use System\Errors\Error;

class Article extends \System\Classes\Controller {
    public function Init() {
        if (array_key_exists(2, $this->app->system->params)) {
            $article = new SystemArticle($this->app->system->params[2]);

            if ($article->id) {
                if ($article->published == 0 && !\System\Session::HasPermission("articles", "view_unpublished_articles")) {
                    new Error("404", "No article found...");
                    exit;
                }

                \System\Page::SetTitle($article->title);

                $author = \System\Members\Member::GetMember("id", $article->author);

                $article->published_date = (new \DateTime($article->published_date))->format('d M, Y');

                \System\Views\Output::I()->IncludeView("article", "articles", array(
                    "article" => $article,
                    "author" => $author
                ));

                \System\Views\Output::I()->css["article"] = array("app" => "articles", "css" => "article");

                \System\Views\Output::I()->Render();
                exit;
            }
        }
            
        new Error("404", "This article couldn't be found...");
    }
}