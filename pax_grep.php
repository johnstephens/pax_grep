<?php

// This is a PLUGIN TEMPLATE for Textpattern CMS.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ("abc" is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'pax_grep';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 1;

$plugin['version'] = '0.2.2';
$plugin['author'] = 'John Stephens';
$plugin['author_uri'] = 'https://designop.us/';
$plugin['description'] = 'Replace all occurrences of a regular expression with replacements.';

// Plugin load order:
// The default value of 5 would fit most plugins, while for instance comment
// spam evaluators or URL redirectors would probably want to run earlier
// (1...4) to prepare the environment for everything else that follows.
// Values 6...9 should be considered for plugins which would work late.
// This order is user-overrideable.
$plugin['order'] = '5';

// Plugin 'type' defines where the plugin is loaded
// 0 = public              : only on the public side of the website (default)
// 1 = public+admin        : on both the public and admin side
// 2 = library             : only when include_plugin() or require_plugin() is called
// 3 = admin               : only on the admin side (no AJAX)
// 4 = admin+ajax          : only on the admin side (AJAX supported)
// 5 = public+admin+ajax   : on both the public and admin side (AJAX supported)
$plugin['type'] = '0';

// Plugin "flags" signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use
if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

$plugin['flags'] = '0';

// Plugin 'textpack' is optional. It provides i18n strings to be used in conjunction with gTxt().
// Syntax:
// ## arbitrary comment
// #@event
// #@language ISO-LANGUAGE-CODE
// abc_string_name => Localized String

/** Uncomment me, if you need a textpack
$plugin['textpack'] = <<< EOT
#@admin
#@language en-gb
abc_sample_string => Sample String
abc_one_more => One more
#@language de-de
abc_sample_string => Beispieltext
abc_one_more => Noch einer
EOT;
**/
// End of textpack

if (!defined('txpinterface'))
        @include_once('zem_tpl.php');

# --- BEGIN PLUGIN CODE ---
/**
 * This is pax_grep: A plugin for Textpattern
 * version 0.2.1
 * by John Stephens, adapted from rah_replace by Jukka Svahn
 * https://designop.us/
 */

if (class_exists('\Textpattern\Tag\Registry')) {
    Txp::get('\Textpattern\Tag\Registry')
       ->register('pax_grep');
}

function pax_grep($atts,$thing) {
    global $pretext;
    extract(lAtts(array(
        'from' => '',
        'to' => '',
        'delimiter' => ','
    ),$atts));
    $from = explode($delimiter,$from);
    $to = explode($delimiter,$to);
    $count = count($to);
    if($count == 1) $to = implode('',$to);
    if($count == 0) $to = '';
    return preg_replace($from,$to,parse($thing));
}

# --- END PLUGIN CODE ---
if (0) {
?>
<!--
# --- BEGIN PLUGIN HELP ---
<h1>pax_grep</h1>

	<dl>
		<dt>Summary</dt>
		<dd>Replace all occurrences of a regular expression with replacements.</dd>
		<dt>Version</dt>
		<dd>0.2.2 (updated 5 Dec 2016)</dd>
	</dl>

	<h2>Table of contents</h2>

	<ol>
		<li><a href="#help-section01" rel="nofollow">Overview</a></li>
		<li><a href="#help-section02" rel="nofollow">Installing and uninstalling</a></li>
		<li><a href="#help-section03" rel="nofollow">Usage and syntax</a></li>
		<li><a href="#help-section04" rel="nofollow">Attributes</a>
	<ol>
		<li><a href="#help-section05" rel="nofollow">from</a></li>
		<li><a href="#help-section06" rel="nofollow">to</a></li>
		<li><a href="#help-section07" rel="nofollow">delimiter</a></li>
	</ol></li>
		<li><a href="#help-section08" rel="nofollow">Examples</a></li>
		<li><a href="#help-section09" rel="nofollow">License</a></li>
		<li><a href="#help-section10" rel="nofollow">Author contact</a></li>
		<li><a href="#help-section11" rel="nofollow">Changelog</a></li>
	</ol>

	<h2>Overview</h2>

	<p>This plugin allows you to <em>find occurrences of a <a href="http://en.wikipedia.org/wiki/Regular_expression" rel="nofollow">regular expression</a> pattern</em> and <em>replace them with something else</em>. It&#8217;s almost identical to it&#8217;s awesome parent plugin <a href="http://rahforum.biz/plugins/rah_replace" rel="nofollow">rah_replace by Jukka Svahn</a>, but <strong>pax_grep</strong> uses <span class="caps">PHP</span>&#8217;s <a href="http://fi.php.net/preg_replace" rel="nofollow">preg_replace()</a> function, packaged in a compact and easy-to-use Textpattern tag.</p>

	<h2>Installing and uninstalling</h2>

	<p>In Textpattern, navigate to the &#8220;Plugins&#8221; tab under &#8220;Admin&#8221;, and paste the code into the &#8220;Install plugin&#8221; pane. Install and enable the plugin.</p>

	<p>To uninstall, simply delete the plugin from the &#8220;Plugins&#8221; tab.</p>

	<h2>Usage and syntax</h2>

	<p><strong>pax_grep</strong> takes a regular expression and searches content for matching patterns. Then it replaces matches with the string(s) you supply. This works just like <a href="http://rahforum.biz/plugins/rah_replace" rel="nofollow">rah_replace</a>, except it supports regular expression searches. If you don&#8217;t need to search and replace based on a regular expression, <strong>you should use rah_replace instead</strong>.</p>

	<p><strong>pax_grep</strong> s a container tag with three attributes.</p>

<pre><code>&lt;txp:pax_grep ↩
    from=&quot;/search pattern/&quot; ↩
    to=&quot;replacement text&quot; ↩
    delimiter=&quot;|&quot;&gt;
    Content
&lt;/txp:pax_grep&gt;
</code></pre>

	<h3>Attributes</h3>

	<h4><code>from</code> &#8212; <em>Required</em></h4>

	<p>Give this attribute the value of a pattern or patterns you wish to search for. A pattern should be <strong>delimited with single quotes</strong> or some other character not used in the pattern. Separate multiple patterns by commas (use the <code>delimiter</code> attribute to specify and alternate separator).</p>

	<dl>
		<dt>Default</dt>
		<dd><code>from=&quot;&quot;</code></dd>
		<dt>Example</dt>
		<dd><code>from=&quot;'^foo','bar$'&quot;</code></dd>
	</dl>

	<h4><code>to</code> &#8212; <em>Required</em></h4>

	<p>This attribute holds the replacement value(s) for each pattern in the <code>from</code> attribute. No delimiters are needed, but <strong>multiple values must be separated by commas (use the <code>delimiter</code> attribute to specify and alternate separator)</strong>. Each <code>from</code> value will be replaced with the corresponding <code>to</code> value.</p>

	<dl>
		<dt>Default</dt>
		<dd><code>to=&quot;&quot;</code></dd>
		<dt>Example</dt>
		<dd><code>to=&quot;fox,bat&quot;</code></dd>
	</dl>

	<h4><code>delimiter</code> &#8212; <em>Optional</em></h4>

	<p>So you don&#8217;t like using commas to separate your search patterns or replacement values? Sometimes, you need to use a comma in the search pattern, and you need a different separator to break search patterns and replacement values. Use this attribute to specify an alternate separator.</p>

	<dl>
		<dt>Default</dt>
		<dd><code>delimiter=&quot;,&quot;</code></dd>
		<dt>Example</dt>
		<dd><code>delimiter=&quot; | &quot;</code></dd>
	</dl>

	<h2>Examples</h2>

	<h3>Example 1: Get words out of Textpattern&#8217;s <code>request_uri</code>.</h3>

	<p>This example outputs the current request <acronym title="Uniform Resource Identifier"><span class="caps">URI</span></acronym>, and uses a regular expression to drop the leading slash and transform many delimiter characters into spaces. You might use code like this on a 404 error page to populate a search field with information from a mistyped <span class="caps">URL</span>.</p>

<pre><code>&lt;txp:pax_grep
    from=&quot;'^\/','\/','%20','\-','\+','\?=','\_'&quot; ↩
    to=&quot;, , , , , , &quot;&gt;
    &lt;txp:page_url type=&quot;request_uri&quot;/&gt;
&lt;/txp:pax_grep&gt;
</code></pre>

	<h3>Example 2: Strip Textile-generated <code>p</code> elements from Textpattern&#8217;s excerpt output</h3>

	<p>Sometimes you might want to show the excerpt of a Textpattern article without mucking around with a bunch of paragraph tags. Here&#8217;s how.</p>

<pre><code>&lt;txp:pax_grep from=&quot;/&lt;\/?p&gt;/,/\t/&quot; to=&quot;&quot;&gt;
    &lt;txp:excerpt/&gt;
&lt;/txp:pax_grep&gt;
</code></pre>

	<h2>Licence</h2>

	<p>This plugin is licenced under <a href="http://textpattern.com/about/51/license" rel="nofollow"><span class="caps">GPL</span>, Version 2</a>.</p>

	<h2>Author contact</h2>

	<p>John Stephens is known as &#8220;johnstephens&#8221; on the Textpattern support forum and on Twitter. You can reach me at <a href="https://designop.us/" rel="nofollow">Design Opus</a> or find <a href="https://twitter.com/johnstephens" rel="nofollow">@johnstephens</a> on Twitter <a href="https://twitter.com/johnstephens" rel="nofollow">here</a>.</p>



	<h2>Changelog</h2>

	<dl>
		<dt>Version 0.2.2</dt>
		<dd>2016-12-05: Support the tag registry in Textpattern 4.6.0+.</dd>
		<dt>Version 0.2.1</dt>
		<dd>2012-04-26: Expand and revise plugin help.</dd>
		<dt>Version 0.2</dt>
		<dd>2010-10-01: Add an optional <code>delimiter</code> attribute, so you can use commas in your search pattern. <a href="http://forum.textpattern.com/viewtopic.php?pid=235323#p235323" rel="nofollow">Thanks, Jan</a>.</dd>
		<dt>Version 0.1.1</dt>
		<dd>2009-01-25: Change plugin name from <strong>opus_grep</strong> to <strong>pax_grep</strong> in compliance with de facto standard of three-letter prefix for plugins.</dd>
		<dt>Version 0.1</dt>
		<dd>2009-01-23: Branch from <a href="http://rahforum.biz/plugins/rah_replace" rel="nofollow">rah_replace</a> by Jukka Svahn&#8212; use <code>preg_replace()</code> instead of <code>str_replace()</code> to allow regex search patterns.</dd>
	</dl>
# --- END PLUGIN HELP ---
-->
<?php
}
?>
