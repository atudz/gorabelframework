@extends('layout.master')

@section('content')
	<div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Forgot Password</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form">
                            <fieldset>
                            	{!!Form::finput('email','email','',['no_label'=>true,'placeholder'=>'E-mail'])!!}
                            	<div class="pull-right">
                            		<button class="btn btn-success">Reset Password</button>
                            	</div>
                            	<div class="pull-left">
                            		&nbsp;&nbsp;<a href="{{route('login')}}">Go&nbsp;Back</a>
                            	</div>                                
                                
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
    </div>
@stop