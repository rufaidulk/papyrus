<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Tag;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Artilcle\ArticleResource;
use App\Http\Resources\Artilcle\ArticleCollection;
use App\Services\ArticleService;

class ArticleController extends ApiBaseController
{
    private $service;

    public function __construct(ArticleService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response['data'] =  (new ArticleCollection(Article::paginate()))->response()->getData(true);

        return $this->success($response, 'Article List', Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:1000'],
            'topic_id' => 'required|exists:topics,id',
            'tags' => 'required|exists:tags,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $this->service->handle();
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response['data'] = new ArticleResource($this->service->article);

        return $this->success($response, 'Article created', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $response['data'] = new ArticleResource($tag);

        return $this->success($response, 'Artilcle details', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:1000'],
            'topic_id' => 'required|exists:topics,id',
            'tags' => 'required|exists:tags,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $this->service->article = $article;
            $this->service->handleUpdate();
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response['data'] = new ArticleResource($article);

        return $this->success($response, 'Article updated', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
