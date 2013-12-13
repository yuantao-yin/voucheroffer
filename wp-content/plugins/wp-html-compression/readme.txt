=== WP-HTML-Compression ===
Author: Steven Vachon
URL: http://www.svachon.com/
Contact: contact@svachon.com
Contributors: prometh
Tags: absolute, bandwidth, comment, comments, compress, compressed, compression, faster, google, html, link, links, loading, optimize, optimization, minification, minified, minify, performance, plugin, reduction, relative, seo, shorten, speed, space, template, uri, url, urls, whitespace
Requires at least: 3.2
Tested up to: 3.5.1
Stable tag: trunk

Reduce file size by shortening URLs and safely removing all standard comments and unnecessary whitespace from an HTML document.


== Description ==

Combining HTML "minification" with cache and HTTP compression (**[WP Super Cache](http://wordpress.org/extend/plugins/wp-super-cache/)**, or similar) will cut down your bandwidth and ensure near-immediate content delivery while increasing your Google rankings.

This plugin will compress your HTML by shortening **URLs** and removing **standard comments** and **whitespace**; including new lines, carriage returns, tabs and excess spaces. Most importantly, by ignoring `<pre>`, `<textarea>`, `<script>` and Explorer® `conditional comment` tags, ***presentation will not be affected***.


== Installation ==

1. Download the plugin (zip file).
2. Upload and activate the plugin through the "Plugins" menu in the WordPress admin.


== Frequently Asked Questions ==

= Will this plugin slow down my page load times? =

Yes, slightly. While you should be using **[WP Super Cache](http://wordpress.org/extend/plugins/wp-super-cache/)** anyway, it will correct the issue.

= Will Internet Explorer conditional comments be removed? =

No.

= Is this plugin HTML5-compatible? =

Yes.

= Will having invalid HTML cause an issue? =

Probably, however WordPress does a pretty good job of correcting invalid markup. But honestly, it's your job to make sure that your code doesn't suck.

= Will this plugin interfere with my hash-based JavaScript navigation? =

If the links in your HTML are *not* hard-coded as hashes without the use of a script, an issue could occur with URL shortening. If so, setting `$shorten_urls` on line `21` of *libs/html-minify.php* to `false` will fix that.

= My URLs have the "http:" and/or "https:" stripped?? =

This is totally fine and actually intentional. It's standard and will not cause 404s nor get in the way of your SEO, but it *will* lower file size! You might be interested in reading more about this technique, called **[scheme-relative URLs](http://paulirish.com/2010/the-protocol-relative-url/)**.

= Why does my compressed HTML have a space between most tags? =

To preserve *rendered* whitespace. One or more line breaks in your markup are rendered as a single space in a web browser. Visual discrepancies would occur if those line breaks were not converted to at least one space.

= How do I mark areas that should not be compressed? =

While &lt;pre&gt;, &lt;textarea&gt; and &lt;script&gt; tags are automatically left uncompressed, you can designate any code to be exempted from compression. Simply drop your content between a pair of `<!--wp-html-compression no compression-->` comment tags. A picture is worth a thousand words; so, check the **[screenshots](http://wordpress.org/extend/plugins/wp-html-compression/screenshots/)**.

= How do I compress the contents of &lt;script&gt; tags? =

Until a settings page is created, you'll have to edit the file from the "Plugins" menu in the WordPress admin. Set `$compress_js` on line `21` of *libs/html-minify.php* to `true`. **This is not recommended** as this plugin is not yet ready to *properly* compress inline scripts.

= Are you or have you thought of using HTML Tidy? =

Since not every WordPress server supports the installation of PHP extensions, this plugin does not currently make use of HTML Tidy. However, future releases may do so.

= Will this plugin work for WordPress version x.x.x? =

This plugin has only been tested with versions of WordPress as early as 3.2. For anything older, you'll have to see for yourself.


== Screenshots ==

1. This is what the XHTML looks like after being compressed with WP-HTML-Compression.
2. This is what the same XHTML from the above screenshot looked like prior to compression.
3. This is an example of how to use the compression override.


== Changelog ==

= 0.5.8 =
* URLs within `<script>` and `<style>` tags are no longer shortened unless compression on such tags has been enabled

= 0.5.7 =
* Upgraded to **[Absolute-to-Relative URLs](http://wordpress.org/extend/plugins/absolute-to-relative-urls/)** v0.3.4
* Empty, hash-only anchors (`"#"`) are no longer invalidated by the URL shortener
* Bypasses compression for **[Humans TXT](http://wordpress.org/extend/plugins/humanstxt/)** output

= 0.5.6 =
* Upgraded to **[Absolute-to-Relative URLs](http://wordpress.org/extend/plugins/absolute-to-relative-urls/)** v0.3.3
* Javascript URIs (`"javascript:"`) are no longer invalidated by the URL shortener
* Minor cleanup

= 0.5.5.1 =
* Oops, minor slip-up

= 0.5.5 =
* Upgraded to **[Absolute-to-Relative URLs](http://wordpress.org/extend/plugins/absolute-to-relative-urls/)** v0.3.2
* Data URIs (`"data:"`) are no longer invalidated by the URL shortener
* URL shortener now applied to `data` attribute values (common to `<object>`)
* Minor bug prevention

= 0.5.4.2 =
* PHP errors hidden if/when plugin file is accessed directly

= 0.5.4.1 =
* Fixed typo on variable

= 0.5.4 =
* Plugin should always load now, even for installations that seem to skip the execution of `template_redirect` (?)
* Converted for use in standard PHP as **[HTML Minify](http://www.svachon.com/blog/html-minify/)**, for which this plugin now simply wraps
* Compression statistics comment disabled by default

= 0.5.3 =
* Bypasses compression for robots.txt
* Corrected "invalid plugin header" issue when activating from install screen

= 0.5.2 =
* Upgraded to **[Absolute-to-Relative URLs](http://wordpress.org/extend/plugins/absolute-to-relative-urls/)** v0.3.1

= 0.5.1 =
* Upgraded to **[Absolute-to-Relative URLs](http://wordpress.org/extend/plugins/absolute-to-relative-urls/)** v0.3
* JavaScript library references with scheme-relative URLs (`//domain.com/`) are no longer broken
* Canonical URL no longer shortened
* Minor bug fixes

= 0.5 =
* Includes **[Absolute-to-Relative URLs](http://wordpress.org/extend/plugins/absolute-to-relative-urls/)** for `action`, `href`, `src` attributes
* Bypasses compression for RSS/Atom feeds
* Bypasses compression on admin/dashboard pages to free up resources for other tools/plugins
* Compresses themes that don't make use of a header.php file (previously did not)
* Removes any empty attributes using single-quote encapsulation (previously supported only double-quotes)
* Removes excess spacing within opening and closing tags (previously supported only self-closing tags)
* Converts new lines to spaces so that *rendered* whitespace is preserved
* Simplified compression statistics comment
* PHP errors hidden if/when plugin file is accessed directly
* Speed optimizations

= 0.4 =
* Removes empty attributes except `action`, `alt`, `content`, `src`

= 0.3 =
* Comments in &lt;textarea&gt; are no longer removed. Browsers seem to display such text
* Removes excess spacing within self-closing tags
* Speed optimizations

= 0.2 =
* Fixed compression override

= 0.1 =
* Initial release