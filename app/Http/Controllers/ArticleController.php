<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller {
    //

    public function index() {
        $article = Article::orderBy( 'created_at', 'DESC' )->paginate( 10 );
        return view( 'articles.index', [
            'article'=>$article
        ] );
    }

    public function create() {
        return view( 'articles.create' );
    }

    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'title' => 'required|unique:articles|min:3',
            'text' => 'required',
            'author' => 'required'
        ] );
        if ( $validator->passes() ) {
            $new = new Article();
            $new->title = $request->title;
            $new->text = $request->text;
            $new->author = $request->author;

            if ( $request->hasfile( 'profile_image' ) ) {
                $file = $request->file( 'profile_image' );
                $extention = $file->getClientOriginalExtension();
                $filename = time().'.'.$extention;
                $file->move( 'uploads/students/', $filename );
                $new->profile_image = $filename;
            }

            $new->save();

            return redirect()->route( 'article.index' )->with( 'success', 'Article Added Successfully' );
        } else {
            return redirect()->route( 'article.create' )->withInput()->withErrors( $validator );
        }
    }

    public function edit( $id ) {
        $article = Article::findorfail( $id );
        return view( 'articles.edit', [
            'article' => $article
        ] );
    }

    public function update( Request $request, $id ) {
        $new = Article::findorfail( $id );
        $validator = Validator::make( $request->all(), [
            'title' => 'required|min:3',
            'text' => 'required',
            'author' => 'required'
        ] );
        if ( $validator->passes() ) {
            $new->title = $request->title;
            $new->text = $request->text;
            $new->author = $request->author;

            if ( $request->hasfile( 'profile_image' ) ) {
                $destination = 'uploads/students/'.$new->profile_image;
                if ( File::exists( $destination ) ) {
                    File::delete( $destination );
                }
                $file = $request->file( 'profile_image' );
                $extention = $file->getClientOriginalExtension();
                $filename = time().'.'.$extention;
                $file->move( 'uploads/students/', $filename );
                $new->profile_image = $filename;
            }

            $new->save();

            return redirect()->route( 'article.index' )->with( 'success', 'Article Updated Successfully' );
        } else {
            return redirect()->route( 'article.edit' )->withInput()->withErrors( $validator );
        }
    }

    public function destroy( Request $request ) {
        // print_r( $request->id );
        // die();
        $id = $request->id;
        $new = Article::find( $request->id );
        $article = Article::find( $id );
        if ( $article == null ) {
            session()->flash( 'error', 'Article not found' );
            return response()->json( [
                'status'=>false
            ] );
        }
        $article->delete();
        $destination = 'uploads/students/'.$new->profile_image;
        if ( File::exists( $destination ) ) {
            File::delete( $destination );
        }

        session()->flash( 'success', 'Article deleted found' );
        return response()->json( [
            'status'=>true
        ] );
    }

}
