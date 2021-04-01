@extends('layout.users.master')
@section('titulo-pagina', __('messages.title_page_update_password'))

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Usuário</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.title_edit_user')}}</li>
  </ol>
</nav> 
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">{{__('messages.title_box_edit_user')}}</h6>
          <form method="POST" action="{{route('users.tenant.atualizar.senha', $user->id)}}">
          {{ method_field('PUT') }}
          @csrf 
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('name') ? 'has-danger' : ''}}">
                  <label class="control-label">{{__('messages.name')}}</label>
                  <input type="text" value="{{$user->name}}" class="form-control {{$errors->has('name') ? 'form-control-danger' : ''}}" name="name" placeholder="{{__('messages.name')}}">
                  @if($errors->has('name'))
                    <label id="name-error" class="error mt-2 text-danger" for="name">
                      {{$errors->first('name')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-2">
            
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row">
            <div class="col-sm-5">
                <div class="form-group {{$errors->has('email') ? 'has-danger' : ''}}">
                  <label class="control-label">{{__('messages.email')}}</label>
                  <input type="text" value="{{$user->email}}" class="form-control {{$errors->has('email') ? 'form-control-danger' : ''}}" name="email" placeholder="{{__('messages.email')}}">
                  @if($errors->has('email'))
                    <label id="name-error" class="error mt-2 text-danger" for="email">
                      {{$errors->first('email')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('password') ? 'has-danger' : ''}}">
                  <label class="control-label">{{__('messages.password')}}</label>
                  <input type="password" value="{{$user->password}}" class="form-control {{$errors->has('password') ? 'form-control-danger' : ''}}" name="password" placeholder="Senha">
                  @if($errors->has('password'))
                    <label id="name-error" class="error mt-2 text-danger" for="password">
                      {{$errors->first('password')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
            </div>
            <div class="row">
              <div class="col-sm-5">
              <p></p>
              </div>
            </div>
            <button type="submit" class="btn btn-primary submit">{{__('messages.button_save')}}</button>  
          </form>
      </div>
    </div>
  </div>
</div>
@endsection
