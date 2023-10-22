<?php
class Members extends Trongate {

	function index() {
		$data['view_file'] = 'members_home';
		$this->template('public', $data);
	}

	function account_created() {
		$data['view_file'] = 'account_created';
		$this->template('public', $data);
	}

	function create_account() {
		$data['username'] = post('username');
		$data['view_file'] = 'create_account';
		$this->template('public', $data);
	}

	function submit_create_account() {
		$uvs = 'required|min_length[6]|max_length[55]|callback_username_unique';
		$this->validation_helper->set_rules('username', 'username', $uvs);

		$pvs = 'required|min_length[6]|max_length[55]';
		$this->validation_helper->set_rules('password', 'password', $pvs);

		$this->validation_helper->set_rules('repeat_password', 'repeat password', 'required|matches[password]');

		$result = $this->validation_helper->run(); // Returns true or false.

		if($result === true) {
			// Create new member account.

			// Start by creating a new record on Trongate users.
			$trongate_user_data['code'] = make_rand_str(32);
			$trongate_user_data['user_level_id'] = 2; // member.
			$trongate_user_id = $this->model->insert($trongate_user_data, 'trongate_users');

			// Now up an array of $data for the members record.
			$data['username'] = post('username', true);
			$password = post('password');
			$data['password'] = $this->_hash_string($password);
			$data['trongate_user_id'] = $trongate_user_id;

			// Create the new members record
			$this->model->insert($data, 'members');

			redirect('members/account_created');

		} else {
			$this->create_account();
		}
	}

	function _hash_string($str) {
        $hashed_string = password_hash($str, PASSWORD_BCRYPT, array(
            'cost' => 11
        ));
        return $hashed_string;
    }

    function _verify_hash($plain_text_str, $hashed_string) {
        $result = password_verify($plain_text_str, $hashed_string);
        return $result; //TRUE or FALSE
    }

	function username_unique($username) {
		// Check to see if the submitted username is available.
		$member_obj = $this->model->get_one_where('username', $username, 'members');
		if($member_obj === false) {
			return true; // Username is available!
		} else {
			$error_msg = 'The username that you submitted is not available.';
			return $error_msg;
		}
	}





}