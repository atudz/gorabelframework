@extends('layout.master')

@section('content')
	<div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form">
                            <fieldset>
                            	{!!Form::finput('email','email','',['no_label'=>true,'placeholder'=>'E-mail'])!!}
                            	{!!Form::finput('password','password','',['no_label'=>true,'placeholder'=>'Password'])!!}
                            	{!!Form::fbox('checkbox','remember',['Remember Me'=>'Remember Me'])!!}
                                <!-- Change this to a button or input when using this as a form -->
                                <button class="btn btn-success">Sign In</button>
                                &nbsp;&nbsp;<a href="{{route('forgot-password')}}">Forgot Password?</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
    </div>
@stop