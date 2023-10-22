<h1>Create Account</h1>
<?php
echo validation_errors();
echo form_open('members/submit_create_account');
echo form_label('Username');
$attr['placeholder'] = 'Enter your username here...';
$attr['autocomplete'] = 'off';
echo form_input('username', $username, $attr);

echo form_label('Password');
$attr['placeholder'] = 'Enter your password here...';
echo form_password('password', '', $attr);

echo form_label('Repeat Password');
$attr['placeholder'] = 'Repeat your password here...';
echo form_password('repeat_password', '', $attr);

echo form_submit('submit', 'Submit');

echo form_close(); // automaticallly adds CSRF protection!