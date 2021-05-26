<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           <div class="row">
                <div class="col-md-12">
                    <div class="row align-items-center">
                        <div class="col-sm-12">
                            <div class="col-sm-6 m-auto">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <form action="{{ url('posting') }}" id='form_posting' method='post'  enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        @method('post')
                                        <div class="form-group">
                                            <label for="status"></label>
                                            <textarea name="status" class="form-control" require></textarea>
                                            <div style='overflow: hidden; width: 0px;height: 0px;'>
                                                <input type='file' name="file" id="file">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <img src="#" style="visibility: hidden" id="img_gambar">
                                        </div>
                                        <div class="form-group">
                                            <button type='button' class="btn btn-primary float-left" id="btn_img"><i class="fa fa-image"></i> Images </button>
                                            <button type='submit' class="btn btn-success float-right" id="btn_posting"><i class="fa fa-submit"></i> Posting </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
                        {{--<div class="col-sm-12">--}}
                            {{--<div class="row" id="self_posting" style="height: 600px; overflow-y: scroll">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="col-sm-12">
                            <div class="col-sm-6 m-auto">
                                <div class="row" id="list_posting">
                            </div>
                        </div>
                    </div>
                </div>

           </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Komentar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       <form action="" method="post" id="form-comment">
                           {{ csrf_field() }}
                           <div class="form-group">
                               <textarea name="comment" class="form-control"></textarea>
                               <input type="hidden" name="id_posting" id="id_posting">
                           </div>
                       </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" onclick="push_comment()" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Komentar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       <form action="" method="post" id="form-comment-update">
                           {{ csrf_field() }}
                           <input type="hidden" name="_method" id="comment_method">
                           <div class="form-group">
                               <textarea name="comment" class="form-control"></textarea>
                               <input type="hidden" name="id_posting" id="id_posting">
                           </div>
                       </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" onclick="update_comment()" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>


