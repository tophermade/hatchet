# Hatchet
The small agency starter theme.

![Hatchet](https://share.workandwit.co/hatchet.jpg)

Hatchet is more than a theme, it's a development paradigm for agencies that build on WordPress. It leverages a unique structure and ACF to provide a strong foundation to build a human-usable, "my staff can edit this no problem!" site. That's what agencies want to provide their clients, and Hatchet helps them do just that.

You'll find that Hatchet

* ships as a functional blank slate
* contains all necessary theme files and templates to run out of the box
* is framework agnostic
* is opinionated about structure
* easily maintained

## Requirements
* requires Node (or something like preprocess but that is untested)
* requires ACF (pro) *note that fields are created not from the UI but within the theme for obvious reasons.

## Getting Started
Clone or download this repo in your `/wp-content/themes/` folder. Rename or not, as you choose. Rename `.envsample` to `.env`.

From the theme's directory run `npm install` to install the required dependencies.

## Theme Organization
`/assets/` the folder contains all the compiled and minified assets for the site. The content of the folder should not be committed to git, nor manually deployed to a server. The build pipeline will generate and deploy all necessary files.

`/src/`this folder and it's subfolders contain the source files and images that are compiled and dumped into `/assets`/. Everything within this `/src/` folder should be committed to git. `/src/js/` contains `less.js` which compiles the JS directly in browser when in dev mode. The images within `/src/img/` are also compressed and delivered to `/assets/`.

`/blocks/` contains all the blocks we use within the editor, and are typically editable directly within the Gutenberg editor within WordPress.

`/` contains the gulp and pkg files necessary to compile using gulp or the build pipeline. `gulpfile.js`, `less.js`, `package-lock.json`, and `package.json` should be committed to git. 

The remaining files & folders are not unique to Hatchet, and behave as you would expect.

## Usage
Hatchet expects you to structure the majority of your code into blocks, and you want the content of those blocks to be easily managed from within the WP Editor. To realize these assumptions, each block is a folder within `blocks/` containing a `setup.php` file and a `render.php` file, and a matching less file in the `less` source.

### An example
Have look at an example of a small component.

```render.php```
````
<section class="section announcement">
	<div class="inner">
		<h3>Hello there!</h3>
		<p><strong>Announcement Title Here</strong>Curabitur blandit tempus porttitor. Etiam porta sem <a href="#">malesuada magna mollis</a> euismod. Maecenas sed diam eget. </p>
		<a href="https://txnhog.co" class="button">Learn More</a>
	</div>
</section>
````

```_announcement.less```
````
.announcement{
	.inner{
		position: realtive;
	}
	
	h3{
		font-size: 1.3rem;
	}

	p{
		font-size: 1.2rem;
	}

	strong{
		font-weight: normal;
		font-style: italic;
	}

	a{
		text-decoration: underline;

		&.button{
			background: #333;
		}
	}
}
````

In this short example, you would create a folder inside `blocks/` named `announcement`, and inside it you would create the `setup.php`and `render.php` files. The `setup.php` file is automatically loaded by WordPress from the block's folder.

Within `render.php` would be the html and function calls to ACF for display, `setup.php` would contain the setup of those ACF fields and their options, allowing the custom blocks to display within the Gutenberg editor when editing a Page.

Finally, you would create the associated less file inside `./src/less/components/blocks/` as `_announcement.less`, and then link it inside `./src/less/style.less` like so: `@import 'components/blocks/_announcement.less';`

Regarding styling, `.section` would be generic and provide defaults that apply to most sections of this type. `.announcement` would match the component name `components/blocks/_announcement.less`, and be used as the parent for the specific styles of this component. If this component differed from the generic, and had a background image that would be styled on `.announcement`. If the h3 tag differed from the generic that would be styled as `.announcement h3`.

This gives us a significant level of control while maintaining flexibility. It also makes the blocks we create for one site **easily portable to another site** or theme. This approach to scoping also lends itself to predictable rendering within the editor.

All JS placed within `/src/js/` will be compiled into `/assets/scripts.js`. jQuery is enqueued from CDN by default.

## Gulp Usage
Gulp is used to compile and minify either the LESS or less (as you prefer), as well as the JS in the project. It also compresses and moves images from the /src/img/ folder to /assets/.

The gulp commands available to you are as follows: gulp - the default gulp command will watch & compile your JS and LESS.

* gulp watchAllLess - will watch & compile your JS, LESS, and minify your images.
* gulp watchLess - will watch & compile your LESS.
* gulp watchJs - will watch & compile your JS.
* gulp watchImg - will watch & minify your images.
* gulp lessTask - will compress your LESS and quit.
* gulp jsTask - will compress your JS and quit.
* gulp imgTask - will minify your images and quit.

## Using Less
LESS can be used in compiled mode or in live reload mode. When in live reload, changes made to your LESS files will be reflected on the current page immediately (ish) when the LESS is saved. You can activate or deactive live mode as shown below.

## Your .env
The env supports the MODE flag with 3 different settings, `mvp`, `dev`, and `production`.

* `mvp` When set to mvp, the compiled CSS will load after the inclusion of Andy Brewer's excellent [https://andybrewer.github.io/mvp/](MVP.CSS). MVP CSS will give you a set of base styles if you're throwing something up quickly, or even for a lean production site - see it in use on [https://imgy.dev](Imgy.dev).
* `dev` When set to dev the compiled css is not loaded, and instead the LESS is loaded and compiled directly in the browser through the inclusion of the LESS.js file, with styles reloaded automatically. Make sure you have the developer console open in your browser.
* `production` compiled CSS is loaded from assets.
