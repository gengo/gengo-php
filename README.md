[![Build Status](https://secure.travis-ci.org/gengo/gengo-php.png?branch=master)](https://travis-ci.org/gengo/gengo-php)

Gengo PHP Library (for the [Gengo API](http://gengo.com/api/))
======================================================================================================================================================
Translating your tools and products helps people all over the world access them; this is, of course, a somewhat tricky problem to solve.
**[Gengo](http://gengo.com/)** is a service that offers human-translation (which is often a higher quality than machine translation), and an API to
manage sending in work and watching jobs. This is a PHP interface to make using the API simpler (some would say incredibly easy).

This package contains both a client library for accessing the Gengo Translate API, as well as some example code showing how to use the library.


Installation & Requirements
------------------------------------------------------------------------------------------------------------------------------------------------------
Installing the library is simple. Just add it to "require" of your composer.json

Then inside of your code you can call:

\Gengo\Config::setAPIkey("your_api_key");
\Gengo\Config::setPrivateKey("your_private_key");

$job1 = array(
 "type"     => "text",
 "slug"     => "API Liverpool 1",
 "body_src" => "Liverpool_1 Football Club is an English Premier League football club based in Liverpool, Merseyside.",
 "lc_src"   => "en",
 "lc_tgt"   => "ja",
 "tier"     => "standard",
 "force"    => 1,
);

$jobs = array("job_01" => $job1);

$api = new \Gengo\Jobs();
$api->postJobs($jobs);
$response = json_decode($api->getResponseBody(), true);

When you are ready to go live insert the following before calls to Gengo:

\Gengo\Config::useProduction();

You can read through files in tests folder for more usage examples.

Question, Comments, Complaints, Praise?
------------------------------------------------------------------------------------------------------------------------------------------------------
If you have questions or comments and would like to reach us directly, please feel free to do so at the following outlets. We love hearing from
developers!

* Email: api [at] gengo dot com
* Twitter: [@gengoit](https://twitter.com/gengoit)
* IRC: [#gengo](irc://irc.freenode.net/gengo)

If you come across any issues, please file them on the [Github project issue tracker](https://github.com/gengo/gengo-php/issues). Thanks!


Documentation
------------------------------------------------------------------------------------------------------------------------------------------------------
Check out the full [Gengo API documentation](http://developers.gengo.com).
