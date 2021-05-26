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
            return response()->json(array('data'=> $comment,'message'=>'Posting telah dikomentar'));
        }else{
            return response()->json(array('data'=> null,'message'=>'Gagal Komentar'));
        }
    }

    public function edit($id){
        $model = Comment::where('id_user', auth()->user()->id)->findOrFail($id);
        return response()->json($model);
    }

    public function update(Request $req, $id){
        $this->validate($req,[
            'comment'=> 'required',
        ]);
        $id_user = auth()->user()->id;
        $comment = Comment::where('id_user',auth()->user()->id)->findOrFail($id);

        if($comment->update([
            'id_user'=>$id_user,
            'comment'=>$req->comment,
        ])){
            return response()->json(array('data'=> $comment,'message'=>'Komentar telah diubah'));
        }else{
            return response()->json(array('data'=> null,'message'=>'Gagal Komentar'));
        }
    }

    public function destroy(Request $req, $id){
        $comment = Comment::where('id_user',auth()->user()->id)->findOrFail($id);
        if($comment->delete()){
            return response()->json(array('data'=> $comment,'message'=>'Komentar telah dihapus'));
        }else{
            return response()->json(array('data'=> null,'message'=>'Gagal menghapus Komentar'));
        }
    }
}
