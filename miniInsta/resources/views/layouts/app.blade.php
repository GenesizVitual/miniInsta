<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        @livewireStyles
         <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('#btn_img').click(function(){
            $('#file').click();
        });

        edit_status = function (id) {
            $.ajax({
                url:'{{ url('posting') }}/'+id+'/edit',
                dataType:'json',
                success:function(result){
                   $('[name="status"]').text(result.status);
                   $('[name="_method"]').val('put');
                   $('#form_posting').attr('action','{{ url('posting') }}/'+id);
                }
            });
        }

        edit_comment = function (id) {
            $.ajax({
                url:'{{ url('comment') }}/'+id+'/edit',
                dataType:'json',
                success:function(result){
                    $('[name="comment"]').text(result.comment);
                    $('#comment_method').val('put');
                    $('#form-comment-update').attr('action','{{ url('comment') }}/'+id);
                    $('#exampleModal1').modal('show');
                }
            });
        }

        delete_status = function (id) {
            if(confirm('Apakah anda akan menghapus komentar ini...?')==true) {
                $.ajax({
                    url: '{{ url('posting') }}/' + id,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'delete'
                    },
                    success: function (result) {
                        alert(result.message);
                        window.location.reload();
                    }
                });
            }else{
                alert('Proses dihentikan')
            }
        }

        delete_comment = function (id) {
           if(confirm('Apakah anda akan menghapus komentar ini...?')==true){
               $.ajax({
                   url:'{{ url('comment') }}/'+id,
                   type:'post',
                   dataType:'json',
                   data:{
                       '_token':'{{ csrf_token() }}',
                       '_method':'delete'
                   },
                   success:function(result){
                       alert(result.message);
                       window.location.reload();
                   }
               });
           }else{
                alert('Proses dihentikan')
           }
        }

        $(document).ready(function(){
           $.ajax({
               url:'{{ url('show-posting') }}',
               dataType:'json',
               success:function(result){
                   $.each(result.data, function(index, val){
                       var html = "";
                       $('#list_posting').append(val);
                   });
               }
           });

            $.ajax({
                url:'{{ url('self-posting') }}',
                dataType:'json',
                success:function(result){
                    $.each(result.data, function(index, val){
                        var html = "";
                        $('#self_posting').append(val);
                    });
                }
            });

           like = function(id){
               $.ajax({
                   url:'{{ url('posting-like') }}',
                   type:'post',
                   data : {
                       'id_posting':id,
                       '_token': '{{ csrf_token() }}'
                   },
                   success:function(result){
                       console.log(result);
                       if(result.total_like!=0){
                          $('#like_count_'+id).text('Sukai '+ result.total_like);
                       }else{
                           $('#like_count_'+id).text('');
                       }
                   }
               });
           }

           comments = function(id){
              console.log(id);
              $('#id_posting').val(id);
              $('#exampleModal').modal('show');
           }

            push_comment = function(){
               var data = $('#form-comment').serialize();
               $.ajax({
                   url:'{{ url('comment') }}',
                   type:'post',
                   data: data,
                   success: function (result) {
                       alert(result.message);
                       window.location.reload();
                   },
                   error: function(xhr, success, error){
                       var err = eval("(" + xhr.responseText + ")");
                       alert(err.Message);
                   }
               })
            }

            update_comment = function(){
               var data = $('#form-comment-update').serialize();
               $.ajax({
                   url:$('#form-comment-update').attr('action'),
                   type:'post',
                   data: data,
                   success: function (result) {
                       alert(result.message);
                       window.location.reload();
                   },
                   error: function(xhr, success, error){
                       var err = eval("(" + xhr.responseText + ")");
                       alert(err.Message);
                   }
               })
            }
        });

    </script> 
</html>
