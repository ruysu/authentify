<?php namespace Authentify;

class EditController extends AuthentifyController {
	public function getIndex() {
		$edit_action = $this->action('postIndex');
		return $this->view('authentify::auth.edit', compact('edit_action'));
	}

	public function postIndex() {
		$user = $this->auth->user();
		$input = $this->inputFor('edit');

		if($this->users->edit($user, $input)) {
			return $this->redirect('getIndex')
				->with('authentify.notice', array('success', trans('authentify::messages.account.success')));
		}
		else {
			return $this->redirect('getIndex')
				->withErrors($this->errors())
				->withInput();
		}
	}
}
