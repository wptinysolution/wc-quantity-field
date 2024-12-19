/**
 * Laravel Mix Configuration File.
 */
const mix = require("laravel-mix");
const fs = require("fs-extra");
const path = require("path");
const cliColor = require("cli-color");
const emojic = require("emojic");
const wpPot = require("wp-pot");
const archiver = require("archiver");
const min = mix.inProduction() ? ".min" : "";

const package_path = path.resolve(__dirname);
const package_slug = path.basename(path.resolve(package_path));
const temDirectory = package_path + "/dist";
require('mix-tailwindcss');

mix.options({
	terser: {
		extractComments: false,
	},
	processCssUrls: false,
});

mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'src'),
        },
        extensions: ['.js', '.jsx', '.tsx', '.json']
    }
});


// mix.webpackConfig({
// 	stats: {
// 		children: true,
// 	},
// });


if (process.env.npm_config_package) {
	mix.then(function () {
		const copyTo = path.resolve(`${temDirectory}/${package_slug}`);

		// Select All file then paste on list
		let includes = [
			"assets",
            "languages",
            "main",
            'templates',
			"vendor",
			"views",
			"index.php",
			"readme.txt",
			`${package_slug}.php`,
		];

		fs.ensureDir(copyTo, function (err) {
			if (err) return console.error(err);
			includes.map((include) => {
				fs.copy(
					`${package_path}/${include}`,
					`${copyTo}/${include}`,
					function (err) {
						if (err) return console.error(err);
						console.log(
							cliColor.white(`=> ${emojic.smiley}  ${include} copied...`)
						);
					}
				);
			});

			console.log(
				cliColor.white(`=> ${emojic.whiteCheckMark}  Build directory created`)
			);
		});
	});

	return;
}

if (
	!process.env.npm_config_block &&
	!process.env.npm_config_package &&
	(process.env.NODE_ENV === "development" ||
		process.env.NODE_ENV === "production")
) {
	if (mix.inProduction()) {
		let languages = path.resolve("languages");
		fs.ensureDir(languages, function (err) {
			if (err) return console.error(err); // if file or folder does not exist
			wpPot({
				package: "My Plugin Boilerplate - A modern WordPress plugin wp_quantity_field.",
				bugReport: "https://github.com/smrafiz/wp-epo",
				src: "**/*.php",
				domain: "my-plugin-text-domain",
				destFile: "languages/wp-epo.pot",
			});
		});
	}

	/**
	 * JS
	 */
	mix
		// Backend JS
		.js('src/js/backend.jsx', 'assets/js/backend/admin-settings.js').react().sourceMaps(true, 'source-map')
		// Frontend JS
		.js("src/js/frontend.js", "assets/js/frontend/frontend.js").sourceMaps(true, 'source-map');

    mix.sass('src/scss/backend.scss', 'assets/css/backend/admin-settings.css').tailwind('./tailwind.config.js');
	/**
	 * CSS
	 */
	if (!mix.inProduction()) {
		// Frontend CSS
		mix.sass("src/scss/frontend.scss", "assets/css/frontend.min.css",).sourceMaps(true, 'source-map');
		mix.sass("src/scss/frontend-rtl.scss", "assets/css/rtl/frontend-rtl.min.css").sourceMaps(true, 'source-map');
	} else {
		// Frontend CSS
		mix.sass("src/scss/frontend.scss", "assets/css/frontend.min.css");
		mix.sass("src/scss/frontend-rtl.scss", "assets/css/rtl/frontend-rtl.min.css");
	}

	// Backend CSS
	mix.postCss('assets/css/backend/admin-settings.css', 'assets/css/rtl/compiled-backend-rtl.css', [
		require('rtlcss'),
	]);
    // mix.combine([
    //     'assets/css/rtl/compiled-backend-rtl.css',
    //     'assets/css/rtl/backend-rtl.min.css'
    // ], 'assets/css/backend-rtl.min.css');

	// Frontend CSS
	mix.postCss('assets/css/frontend.min.css', 'assets/css/rtl/compiled-frontend-rtl.css', [
		require('rtlcss'),
	]);
	mix.combine([
		'assets/css/rtl/compiled-frontend-rtl.css',
		'assets/css/rtl/frontend-rtl.min.css'
	], 'assets/css/frontend-rtl.min.css');
}
if (process.env.npm_config_zip) {
	async function getVersion() {
		let data;
		try {
			data = await fs.readFile(package_path + `/${package_slug}.php`, "utf-8");
		} catch (err) {
			console.error(err);
		}
		const lines = data.split(/\r?\n/);
		let version = "";
		for (let i = 0; i < lines.length; i++) {
			if (lines[i].includes("* Version:") || lines[i].includes("*Version:")) {
				version = lines[i]
					.replace("* Version:", "")
					.replace("*Version:", "")
					.trim();
				break;
			}
		}
		return version;
	}

	const version_get = getVersion();
	version_get.then(function (version) {
		const destinationPath = `${temDirectory}/${package_slug}.${version}.zip`;
		const output = fs.createWriteStream(destinationPath);
		const archive = archiver("zip", {zlib: {level: 9}});
		output.on("close", function () {
			console.log(archive.pointer() + " total bytes");
			console.log(
				"Archive has been finalized and the output file descriptor has closed."
			);
			fs.removeSync(`${temDirectory}/${package_slug}`);
		});
		output.on("end", function () {
			console.log("Data has been drained");
		});
		archive.on("error", function (err) {
			throw err;
		});

		archive.pipe(output);
		archive.directory(`${temDirectory}/${package_slug}`, package_slug);
		archive.finalize();
	});
}
