<?php namespace Ruysu\Authentify\Models;

use Config;
use Eloquent;

abstract class AuthentifySocialProfile extends Eloquent {
	protected $fillable = array('network', 'network_id', 'access_token', 'secret', 'user_id');

	public function user(){
		return $this->belongsTo(Config::get('auth.model'), 'user_id');
	}
}