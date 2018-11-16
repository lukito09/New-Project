@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!--<div class="card-header">User File - {{ $folderName }}</div>-->
                <div class="card-header">User File - {{ str_replace(env('DEFAULT_UPLOAD_DIRECTORY').'/', '', $folderName) }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="container">
                        <div class="row pt-5">
                            <div class="col-sm-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if (Session::has('success'))
                                    <div class="alert alert-info">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <p>{{ Session::get('success') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-8">
                                <a href="newFolder"><button type="submit">Create Folder</button></a>
                                <form action="{{ url('changeDirectory/') }}" method="POST">
                                                 {{ csrf_field() }}
                                                    <input type="hidden" name="dir" value=".." />
                                                 <button type="submit" class="btn btn-default">..</button>
                                             </form>
                                @if (count($images) > 0)
                                   
                                    <table>
                                    @foreach ($images as $image)
                                         <tr>
                                            @if ($image['src'] != '')
                                            <td><a href="{{$image['src']}}">{{$image['name']}}</a></td>
                                             <td><form action="{{ url('images/' . $image['name']) }}" method="POST">
                                                 {{ csrf_field() }}
                                                 {{ method_field('DELETE') }}
                                                 <button type="submit" class="btn btn-default">Remove</button>
                                             </form></td>
                                            @else
                                            <td>
                                            <form action="{{ url('changeDirectory/') }}" method="POST">
                                                 {{ csrf_field() }}
                                                    <input type="hidden" name="dir" value="{{ $image['name'] }}" />
                                                 <button type="submit" class="btn btn-default">{{ $image['name'] }}</button>
                                             </form></td>
                                            <td><form action="{{ url('images/' . $image['name']) }}" method="POST">
                                                 {{ csrf_field() }}
                                                 {{ method_field('DELETE') }}
                                                 <button type="submit" class="btn btn-default">Remove</button>
                                             </form></td>
                                            @endif
                                            
                                         </tr>
                                     @endforeach
                                @endif
                                <form method="get" action="/upload">
                                        <button type="submit" class="btn btn-default">Upload</button>
                                    </form>
                            </div>
                            <div class="col-sm-4">
                                <div class="card border-0 text-center">
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
