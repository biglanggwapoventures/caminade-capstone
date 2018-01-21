<div class="modal fade" id="profile" tabindex="-1" role="dialog" aria-labelledby="profile-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profile-title">Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::model(Auth::user(), ['url' => route('account.update'), 'method' => 'PATCH','class' => 'ajax']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            {!! Form::bsText('username', 'Username', null, ['readonly' => true, 'class' => 'form-control form-control-plaintext']) !!}
                        </div>
                        <div class="col-sm-6">
                            {!! Form::bsText('email', 'Email Address') !!}
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-sm-6">
                            {!! Form::bsText('firstname', 'First Name') !!}
                        </div>
                        <div class="col-sm-6">
                            {!! Form::bsText('lastname', 'Last Name') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            {!! Form::bsSelect('gender', 'Gender', ['' => '', 'MALE' => 'MALE', 'FEMALE' => 'FEMALE'], null, ['class' => 'custom-select w-100']) !!}
                        </div>
                        <div class="col-8">
                            {!! Form::bsText('contact_number', 'Contact Number') !!}
                        </div>
                    </div>
                     {!! Form::bsText('address', 'Address') !!}
                     <hr>
                     <div class="row">
                         <div class="col">
                             {!! Form::bsPassword('password', 'Password') !!}
                         </div>
                         <div class="col">
                             {!! Form::bsPassword('password_confirmaton', 'Confirm Password') !!}
                         </div>
                     </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Log me in!</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
