<?php

namespace App\Services;

use Exception;
use App\Models\Article;
use App\Models\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Article Service
 */
class ArticleService
{
    public $article;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function handle()
    {
        DB::beginTransaction();

        try
        {
            $this->saveArticle();
            $this->saveArticleTags();

            DB::commit();
        }
        catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
    
    public function handleUpdate()
    {
        DB::beginTransaction();

        try
        {
            $this->saveArticle();
            $this->deleteArticleTags();
            $this->saveArticleTags();

            DB::commit();
        }
        catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    private function saveArticle()
    {
        $this->article = $this->article ?? new Article();
        $this->article->title = $this->request->title;
        $this->article->body = $this->request->body;
        $this->article->topic_id = $this->request->topic_id;
        $this->article->status = Article::STATUS_PENDING;
        $this->article->user_id = Auth::id();
        $this->article->save();
    }

    private function saveArticleTags()
    {
        $data = [];
        foreach ($this->request->tags as $tag) {
            $data[] = [
                'article_id' => $this->article->id,
                'tag_id' => $tag,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        if (! empty($data)) {
            DB::table('article_tags')->insert($data);
        }
    }

    private function deleteArticleTags()
    {
        ArticleTag::where('article_id', $this->article->id)->delete();
    }
}