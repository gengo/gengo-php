|Build Status|

Gengo PHP Library (for the `Gengo API <http://gengo.com/api/>`__)
=================================================================

Translating your tools and products helps people all over the world
access them; this is, of course, a somewhat tricky problem to solve.
`Gengo <http://gengo.com/>`__ is a service that offers
human-translation (which is often a higher quality than machine
translation), and an API to manage sending in work and watching jobs.
This is a PHP interface to make using the API simpler (some would say
incredibly easy).

This package contains both a client library for accessing the Gengo
Translate API, as well as some example code showing how to use the
library.

Installation & Requirements
---------------------------

Installing the library is simple.

-  Get the repo:

.. code:: sh

    $ git clone git://github.com/gengo/gengo-php.git

-  Edit the ``config.ini`` file and set the baseurl to the environment
   you're sending translations to so that your API keys authenticate
   correctly.

Question, Comments, Complaints, Praise?
---------------------------------------

If you have questions or comments and would like to reach us directly,
please feel free to do so at the following outlets. We love hearing from
developers!

-  Email: api [at] gengo dot com
-  Twitter: `@gengoit <https://twitter.com/gengoit>`__
-  IRC: `#gengo <irc://irc.freenode.net/gengo>`__

If you come across any issues, please file them on the `Github project
issue tracker <https://github.com/gengo/gengo-php/issues>`__. Thanks!

Documentation
-------------

Check out the full `Gengo API
documentation <http://developers.gengo.com>`__.

.. |Build Status| image:: https://secure.travis-ci.org/gengo/gengo-php.png?branch=master
   :target: https://travis-ci.org/gengo/gengo-php
