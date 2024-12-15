/**
 * Backend JS.
 *
 */

'use strict';

import '../scss/frontend.scss';
import { modules } from './frontend/modules';

let frontend = {};

frontend = {
	init: () => {
		modules();
	},
};

frontend.init();
