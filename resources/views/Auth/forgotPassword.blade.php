@extends('layout.master')

@section('content')
	<div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Forgot Password</h3>
                    </div>
                    <div class="panel-body">
                        {!!Form::fopen(['url'=>route('password-reset')])!!}
                            <fieldset>
                            	{!!Form::finput('email','email','','',['no_label'=>true,'placeholder'=>'E-mail'])!!}
                            	<div class="pull-right">
                            		{!!Form::button('Reset Password',['class'=>'btn btn-success','type'=>'submit'])!!}
                            	</div>
                            	<div class="pull-left">
                            		&nbsp;&nbsp;<a href="{{route('login')}}">Go&nbsp;Back</a>
                            	</div>                                
                                
                            </fieldset>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
    </div>
@stop