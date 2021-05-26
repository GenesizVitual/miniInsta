<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
class CommentController extends Controller
{
    //
    public function store(Request $req){
        $this->validate($req,[
            'comment'=> 'required',
            'id_posting'=> 'required'
        ]);
        $id_user = auth()->user()->id;
        $comment = new Comment([
            'id_user'=>$id_user,
            'id_posting'=>$req->id_posting,
            'comment'=>$req->comment,
        ]);

        if($comment->save()){
            return response()->json(array('data'=> $comment,'message'=>'Posting telah dicomment'));
        }else{
            return response()->json(array('data'=> null,'message'=>'Gagal comment'));
        }
    }
}
