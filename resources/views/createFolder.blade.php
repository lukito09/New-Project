@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Folder</div>

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
                                <form method="post" action="#">
                                    {{ csrf_field() }}
                                    <label for="folderName">Folder Name: </label>
                                    <input type="text" name="folderName" id="folderName">
                                    <br>
                                    <button type="submit" class="btn btn-default">Save</button>
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
