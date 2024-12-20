<?php

namespace TinySolutions\wcqf\Register;

use TinySolutions\wcqf\Abs\CustomPostType;

class Teammembers extends CustomPostType {
	// must use
	function set_post_type_name() {
		return 'team_member';
	}
}
