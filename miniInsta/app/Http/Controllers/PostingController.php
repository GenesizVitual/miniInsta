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
        // Todo Set default Directory
        $this->directory = public_path().'/img_posting/';
    }

    // Todo Show Posting By All User
    public function self_posting(){
        $data_posting = Posting::all()->where('id_user',auth()->user()->id)->sortByDesc('created_at');
        $container= [];
        foreach ($data_posting as $item_value){
            $container[] = $this->container($item_value);
        }
        return response()->json(array('data'=>$container));
    }

    // Todo Show Posting for All User
    public function show_posting(){
        $data_posting = Posting::all()->sortByDesc('created_at');
        $container= [];
        foreach ($data_posting as $item_value){
            $container[] = $this->container($item_value);
        }
        return response()->json(array('data'=>$container));
    }

    // Todo Edit Posting
    public function edit($id){
        $model = Posting::where('id_user', auth()->user()->id)->findOrFail($id);
        return response()->json($model);
    }

    // Todo Store Posting
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

    // Todo Update Posting
    public function update(Request $req, $id){
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
        $model = Posting::where('id_user', auth()->user()->id)->find($id);
        if($model->update($array)){
            if(!empty($req->file)){
                $initial_img->move($this->directory, $this->img);
            }
            return redirect()->back()->with('message_success','Anda telah membuat postingan terbaru');
        }else{
            return redirect()->back()->with('message_error','Gagal membuat postingan');
        }
    }

    // Todo Delete Posting
    public function destroy(Request $req, $id){
        $model = Posting::where('id_user', auth()->user()->id)->find($id);
        if($model->delete()){
            return response()->json(array('message'=> 'Status telah dihapus'));
        }else{
            return response()->json(array('message'=> 'Status gagal dihapus'));
        }
    }

    // Todo Like Posting
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
        return response()->json(array('message'=>'process success', 'total_like'=>Posting::find($req->id_posting)->linkToMannyLike->count('id')));
    }


    // Todo Manual Component
    private function container($model){
        $img = "";
        $like_count = '';
        $comment= '';
        $button_edit= '';
        $button_delete= '';

        //Todo Image
        if(!empty($model->img)){
            $img = ' <div class="form-group">
                       <img src="'.asset('/img_posting/'.$model->img).'" class="m-auto" style="width: 300px; height: 400px">
                    </div>';
        }

        // Todo  Calculate total like
        if($model->linkToMannyLike->count('id')!=0){
            $like_count='<a href="#" class="like_count_'.$model->id.'" id="like_count_'.$model->id.'"> By '.$model->linkToMannyLike->count('id').' </a>';
        }else{
            $like_count='<a href="#" class="like_count_'.$model->id.'" id="like_count_'.$model->id.'"></a>';
        }

        // Todo Set limit access for posting
        if(auth()->user()->id==$model->id_user){
            $button_edit ='<a href="#" onclick="edit_status('.$model->id.')" class="m-1" style="font-size:small;"><i class="fa fa-pen"></i></a>';
            $button_delete='<a href="#" onclick="delete_status('.$model->id.')" class="m-1" style="font-size:small;"><i class="fa fa-eraser"></i></a>';
        }

        // Todo Build Component for each Posting
        if($model->linkToMannyComment->count('id')!=0){
            $comment.='  <div class="card-body" style="height: 200px; overflow-y: scroll">
                            <div class="row">';
            foreach ($model->linkToMannyComment as $item_comment){
                $btn_delete_coment = '';
                $btn_update_coment = '';

                if($item_comment->id_user== auth()->user()->id){
                    $btn_delete_coment = '<a href="#" onclick="edit_comment('.$item_comment->id.')" class="m-1" style="font-size: small"><i class="fa fa-pen"></i></a>';
                    $btn_update_coment = '<a href="#" onclick="delete_comment('.$item_comment->id.')" class="m-1" style="font-size: small"><i class="fa fa-eraser" ></i></a>';
                }

                $comment.='<div class="col-md-12 ">
                              <label style="font-weight: bold">'.$item_comment->linkToUser->name.'</label> '.$item_comment->comment.'<br>
                              <small style="font-size: x-small;color: gray">Tgl:'.date('d-m-Y', strtotime($item_comment->created_at)).'</small>
                                   '.$btn_delete_coment.$btn_update_coment.' 
                              <hr>
                           </div>';
            }
        }else{
            $comment="";
        }
        //Todo Building in one html
        $html = '<div class="col-md-12">
                    <div class="card mt-3">
                        <div class="card-header">
                             <label style="font-weight: bold">'.$model->linkToUser->name.'</label>
                        </div>
                        <div class="card-body">
                           <div class="row">
                                <div class="col-md-12">
                                    '.$img.'
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                         <button onclick="like('.$model->id.')" class="float-left mr-1"><i class="fa fas fa-thumbs-up"></i> Suka '.$like_count.'</button>                                      
                                         <button type="button" class="float-left" onclick="comments('.$model->id.')"><i class="fa fas fa-comments"></i> Komentar </button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h6>'.$model->status.' '.$button_edit.$button_delete.'</h6>
                                    </div>
                                </div>
                                '.$comment.'    
                            </div>
                        </div>                                        
                                          
                    </div>
                 </div>';
        return $html;
    }

}
