<?php

namespace TinySolutions\wp_quantity_field\Register;

use TinySolutions\wp_quantity_field\Abs\CustomPostType;

class Teammembers extends CustomPostType {
	// must use
	function set_post_type_name() {
		return 'team_member';
	}
}
