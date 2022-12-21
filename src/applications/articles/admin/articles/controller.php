<?php

namespace Applications\Articles\Admin\Articles;

use Applications\Articles\System\Article;
use System\Forms\Form;
use System\Forms\Button;
use System\Forms\Checkbox;
use System\Forms\Select;
use System\Forms\Label;
use System\Forms\Summernote;
use System\Forms\Text;
use System\Structures\Pagination;

class Controller extends \System\Classes\AdminController {
    public function Init()
    {
        \System\Page::SetTitle("Articles");

        \System\Views\Output::I()->params["sidebar"] = array(
            "Articles" => array(
                array("title" => "Articles", "link" => "")
            ),
            "Settings" => array(
                array("title" => "Banner", "link" => "banner")
            )
        );
    }

    public function Home() {
        if (array_key_exists(4, $this->system->system->params)) {
            $article = new Article($this->system->system->params[4]);

            if ($article->id) {
                \System\Page::SetTitle($article->title);

                $action = $this->system->system->params[5];

                if ($action) {
                    switch (strtolower($action)) {
                        case "publish":
                            $form = new Form("admin/articles/articles/home/$article->id/publish");

                            $actionTitle = ($article->published == 1) ? "Unpublish" : "Publish";

                            $form->Add(new Label("", "Confirmation"));
                            $form->Add(new Label("", "Are you sure you want to " . $actionTitle ." article: $article->title ($article->id)?"));

                            $form->SetButtons("_confirm", array(
                                new Button(1, "Cancel"),
                                new Button(2, $actionTitle)
                            ));

                            if ($values = $form->Validate()) {
                                if ($form->submitType == "2") {
                                    $article->published = ($article->published == 1) ? 0 : 1;
                                    $article->published_date = date('Y-m-d H:i:s');
                                    $article->Save();
                                }

                                \System\Views\Output::I()->Redirect(URL . "/admin/articles/articles/home/");
                            }

                            \System\Views\Output::I()->params["page"] = (string) $form;
                            break;
                        default:
                            new \System\Errors\Error("404");
                            break;
                    }
                } else {
                    $form = new Form("admin/articles/articles/home/$article->id");
                    
                    $form->Add(new Text("title", "Article Title", $article->title));
                    $form->Add(new Text("subtitle", "Article Subtitle", $article->subtitle));
                    $form->Add(new Summernote("content", "Article Contents", $article->content));

                    $form->SetButtons("_save", array(new Button(1, "Save")));

                    if ($values = $form->Validate()) {
                        $article->title = $values["title"];
                        $article->subtitle = $values["subtitle"];
                        $article->content = $values["content"];

                        $article->Save();

                        // Stop resubmission...
                        \System\Views\Output::I()->Redirect(URL . "/admin/articles/articles/home/" . $article->id);
                    }

                    \System\Views\Output::I()->params["page"] = (string) $form;
                }
            }
        } else {
            $pagination = (new Pagination("articles"))->Do("ORDER BY `published_date`");

            \System\Views\Output::I()->IncludeView("articles_listview", "admin", array(
                "pagination" => $pagination
            ));

            \System\Views\Output::I()->css["table"] = array("app" => "core", "css" => "table");
            \System\Views\Output::I()->css["pagination"] = array("app" => "core", "css" => "pagination");
        }

        \System\Views\Output::I()->css["forms"] = array("app" => "core", "css" => "forms");
        \System\Views\Output::I()->css["modals"] = array("app" => "core", "css" => "modals");
    }
}