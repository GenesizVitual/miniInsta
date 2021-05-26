<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posting;
use Session;
use App\Models\Like;
class PostingController extends Controller
{
    //
    private $img = 'image-not-found.svg';
    private $directory;

    public function __construct()
    {
        $this->directory = public_path().'/img_posting/';
    }

    public function show_posting(){
        $data_posting = Posting::all()->where('id_user',auth()->user()->id)->sortBy('created_at');
        $container= [];
        foreach ($data_posting as $item_value){
            $container[] = $this->container($item_value);
        }
        return response()->json(array('data'=>$container));
    }


    public function store(Request $req){
        $this->validate($req,[
            'status'=> 'required'
        ]);

         # Check Jika ada gambah yang akan diupload
         if(!empty($req->file)){
            $initial_img = $req->file;
            $this->img=$initial_img->getClientOriginalName();
            $array['img']=$this->img;
        }
        $array['status']=$req->status;
        $array['id_user']=auth()->user()->id;
        $model = new Posting($array);
        if($model->save()){
            if(!empty($req->file)){
                $initial_img->move($this->directory, $this->img);
            }
            return redirect()->back()->with('message_success','Anda telah membuat postingan terbaru');
        }else{
            return redirect()->back()->with('message_error','Gagal membuat postingan');
        }
    }

    public function posting_like(Request $req){
        $this->validate($req,[
            'id_posting'
        ]);
        $likes = Like::where('id_user', auth()->user()->id)->where('id_posting', $req->id_posting);
        if(!empty($likes->first())){
            $likes->delete();
        }else{
            Like::create(['id_user'=>auth()->user()->id,'id_posting'=>$req->id_posting]);
        }
        return response()->json(array('message'=>'process success', 'total_like'=>$likes->count('id')));
    }

    private function container($model){
        $img = "";
        $like_count = '';
        $comment= '';
        if(!empty($model->img)){
            $img = ' <div class="form-group">
                       <img src="'.asset('/img_posting/'.$model->img).'">
                    </div>';
        }

        if($model->linkToMannyLike->count('id')!=0){
            $like_count='<a href="#" id="like_count_'.$model->id.'">'.$model->linkToMannyLike->count('id').' Sukai</a>';
        }else{
            $like_count='<a href="#" id="like_count_'.$model->id.'"></a>';
        }

        if($model->linkToMannyLike->count('id')!=0){
            $like_count='<a href="#" id="like_count_'.$model->id.'">'.$model->linkToMannyLike->count('id').' Sukai</a>';
        }else{
            $like_count='<a href="#" id="like_count_'.$model->id.'"></a>';
        }

        if(!empty($model->linkToMannyComment)){
            foreach ($model->linkToMannyComment as $item_comment){
                $comment.='<div class="col-md-12 ">
                              <label >'.$item_comment->linkToUser->name.'</label><br><small style="font-size: x-small;color: gray">'.date('d-m-Y', strtotime($item_comment->created_at)).'</small><br>
                              <h4>'.$item_comment->comment.'</h4>
                              <hr></hr>
                           </div>';
            }
        }

        $html = '<div class="col-md-12">
                    <div class="card mt-3">
                        <div class="card-body">
                        '.$img.'
                            <div class="form-group">
                                <h6>'.$model->status.'</h6>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                              <div class="col-md-6">
                                <button onclick="like('.$model->id.')"><i class="fa fas fa-thumbs-up"></i> Suka </button>
                                '.$like_count.'
                              </div>
                              <div class="col-md-6">
                                <button type="button" onclick="comments('.$model->id.')"><i class="fa fas fa-comments"></i> Komen </button>
                              </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                   '.$comment.'
                            </div>
                        </div>
                    </div>
                 </div>';
        return $html;
    }

}
