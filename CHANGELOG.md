
### Spring of 2008

Registered the volax.gr domain and started a static html website with about 10 pages.

### Mar 2013

* Started a very simple blog, with the purpose of joining 
both volax.gr and volax-tinos.gr content.
* Login / Logout, Change / Forgot password, Posts, Comments. No admin

### Jun 2018

* Source code moving from Mercurial to Git, from BitBucket to GitHub.
* Some improvements done on feature branch never make it to master.

### Jan 2021

Discussions on migrating the blog to WordPress.

Main gains would be:

* Better support for mobile
* Better sharing, liking, commenting tools
* Post by e-mail, a very convenient feature for posting from Aploma.

We may incorporate a Wiki section, to allow for a Volax encyclopedia.

### Feb 25, 2021

* Quick fixes on the project, to up the PHP version to 7.2 or 7.4, to allow
for Worpress to work.
* Wordpress files installed on the wp-demo folder.
* Auto-detection of environment (prod / dev) in config file, 
config now auto adjusting to this environment.

### Mar 14, 2021

* Code for transfering at least the bulk of posts, comments, pages, categories, tags from old site to new. Idempotent.
* Still pending:
    * Fixing the `[more]` shortcode and a post's excerpt
    * Fixing other short codes, such as `[gallery], [video], [audio]`.
    * Assigning the post's main picture
* Redirection plugin for old urls (e.g. `volax.gr/post/<id>`)



