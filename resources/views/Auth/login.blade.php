@extends('layouts.master')

@section('content')
	<div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        {!!Form::fopen(['url'=>route('user-login')])!!}
                            <fieldset>
                            	{!!Form::finput('email','email','','',['no_label'=>true,'placeholder'=>'E-mail'])!!}
                            	{!!Form::finput('password','password','','',['no_label'=>true,'placeholder'=>'Password'])!!}
                            	{!!Form::fcheckbox('checkbox','remember',['Remember Me'=>'1'])!!}
								{!!Form::button('Sign In',['class'=>'btn btn-success','type'=>'submit'])!!}                                
                                &nbsp;&nbsp;<a href="{{route('forgot-password')}}">Forgot Password?</a>
                            </fieldset>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
    </div>
@stop