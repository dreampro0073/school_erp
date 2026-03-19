@extends('layout.layout')

@section('meta')
    <title>Reset Password</title>
@endsection

@section('main')
    <section>
        <div style="padding-top:32px;">
            @if(Session::has('failure'))
                <div class="alert alert-danger">
                    <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <img src="{{url('front-end/images/cancel.svg')}}" width="15px" height="15px" style="margin-top: -12px;">
                    </button>
                </div>
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success">
                    <i class="fa fa-ban-circle"></i><strong>Success!</strong> {{Session::get('success')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <img src="{{url('front-end/images/cancel.svg')}}" width="15px" height="15px" style="margin-top: -12px;">
                    </button>
                </div>
            @endif

            <form action="{{url('/settings/reset-password')}}" method="post" class="login-form">
                <div class="row">
                    <div class="col-md-4 form-group ">
                        <input name="old_password" type="password" class="password-field form-control" placeholder="Old Password" required>
                        <span class="error">{{$errors->first('old_password')}}</span>   
                    </div>
        
                    <div class="col-md-4 form-group ">
                        <input name="new_password" type="password" class="password-field form-control" placeholder="New Password" required>
                        <span class="error">{{$errors->first('new_password')}}</span>
                    </div>

                    <div class="col-md-4 form-group ">
                        <input name="confirm_password" type="password" class="password-field form-control" placeholder="Confirm Password" required>
                        <span class="error">{{$errors->first('confirm_password')}}</span>
                    </div>
                </div>
                
                {{csrf_field()}}
                
                <div class="form-group mt-5">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </section>
    
@endsection