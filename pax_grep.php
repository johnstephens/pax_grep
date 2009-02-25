<?php

// This is a PLUGIN TEMPLATE.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Uncomment and edit this line to override:
# $plugin['name'] = 'pax_grep';

// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 1;

$plugin['version'] = '0.1';
$plugin['author'] = 'John Stephens';
$plugin['author_uri'] = 'http://designop.us/';
$plugin['description'] = 'Replace all occurrences of a regular expression with replacements.';

// Plugin types:
// 0 = regular plugin; loaded on the public web side only
// 1 = admin plugin; loaded on both the public and admin side
// 2 = library; loaded only when include_plugin() or require_plugin() is called
$plugin['type'] = 1; 

if (!defined('txpinterface'))
	@include_once('../zem_tpl.php');

if (0) {
?>
# --- BEGIN PLUGIN HELP ---
h1. pax_grep

This is a plugin for "Textpattern(the free, flexible content management system for designers)":http://textpattern.com/ that allows you to _find occurrences of a "regular expression":http://en.wikipedia.org/wiki/Regular_expression pattern_ and _replace them with something else_. It's almost identical to it's awesome parent plugin "rah_replace":http://rahforum.biz/plugins/rah_replace by Jukka Svahn. *pax_grep* uses PHP's "preg_replace()":http://fi.php.net/preg_replace function, packaged in a compact and easy-to-use Textpattern tag.

* Version: 0.1.1
* Updated: 2009-01-25

h3. Installaing/Uninstalling

In Textpattern, navigate to the "Plugins" tab under "Admin", and paste the code into the "Install plugin" pane. Install and enable the plugin.

To uninstall, simply delete the plugin from the "Plugins" tab.

h3(#intro). Usage

*pax_grep* takes a regular expression and searches content for matching patterns. Then it replaces matches with the string(s) you supply. This works just like "rah_replace":http://rahforum.biz/plugins/rah_replace. If you don't need to search and replace based on a regular expression, *you should use rah_replace instead*.

h3. Syntax

*pax_grep* is a container tag with two attributes.

bc. <txp:pax_grep ↩
	from="/search pattern/" ↩
	to="replacement text">
	Content
</txp:pax_grep>

h3(#atts). Attributes

h4(#from). from

Give this attribute the value of a pattern or patterns you wish to search for. A pattern should be *delimited with single quotes* or some other character not used in the pattern. Separate multiple patterns with commas.

h5. Default: @from=""@

h5. Example: @from="'^foo','bar$'"@

h4(#to). to

This attribute holds the replacement value(s) for each pattern in the @from@ attribute. No delimiters are needed, but *multiple values must be separated by commas*. Each @from@ value will be replaced with the corresponding @to@ value.

h5. Default: @to=""@

h5. Example: @to="fox,bat"@

h3(#examples). Examples

h4(#vowels). Replace all vowels with hyphens.

This example searches for the any vowel, replacing each one with a hyphen.

bc.. <txp:pax_grep from="'[aeiou]'" to="-">
	My favorite animal is a dog.
</txp:pax_grep>

h5. Result: _M- f-v-r-t- -n-m-l -s - d-g._

h4(#words). Get words out of a @request_uri@.

This example outputs the current request URI(Uniform Resource Identifier), and uses a regular expression to drop the leading slash and transform many delimiter characters into spaces.

bc.. <txp:pax_grep ↩
	from="'^\/','\/','%20','\-','\+','\?=','\_'" ↩
	to=", , , , , , ">
	<txp:page_url type="request_uri" />
</txp:pax_grep>

p. If the browser is pointed to @example.com/section-name/my-article-title/@, *pax_grep* will return the following:

h5. _section name my article title_

h3(#changes). Changelog

h4. Version 0.1.1

* 2009-01-25: Changed plugin name from *opus_grep* to *pax_grep* in compliance with defacto standard of thre-letter prefix for plugins.

h4. Version 0.1

* 2009-01-23: Branched from "rah_replace":http://rahforum.biz/plugins/rah_replace by Jukka Svahn-- changed @str_replace()@ to @preg_replace()@.

# --- END PLUGIN HELP ---
<?php
}

# --- BEGIN PLUGIN CODE ---

##################
#
#	This is pax_grep: A plugin for Textpattern
#	version 0.1
#	by John Stephens, adapted from rah_replace by Jukka Svahn
#	http://designop.us/
#
###################

function pax_grep($atts,$thing) {
	global $pretext;
	extract(lAtts(array(
		'from' => '',
		'to' => ''
	),$atts));
	$from = explode(',',$from);
	$to = explode(',',$to);
	$count = count($to);
	if($count == 1) $to = implode('',$to);
	if($count == 0) $to = '';
	return preg_replace($from,$to,parse($thing));
}

# --- END PLUGIN CODE ---

?>
