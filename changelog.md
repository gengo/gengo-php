# Change Log

## [3.0.0] - 27 August 2016
### Changed
- No longer using Zend library, new Zend Framework is quite heavy. Using logics/http package as lightweight alternative
- Removed redundant class Gengo_Exception
- Added docblocks for PHPDocumentor automatic documentation
- Strict compliance to Beauty coding standard
- Dropped useless constructors
- getGlossaries() did not use $page_size , removed
- No more debug option: inline debugging is bad for performance - use PHPUnit and phpdbg instead
- Removed API::factory() method: static method in abstract class? Really?! new Job() looks much better!
- Removed Gengo_Crypto class: whole class for one line single method is an overkill, moved to API class
- There no reason to have configurable useragent string: only webserver concerned is gengo.com and it is better for it to know actual API client name
- Dropped support for config.ini file: default config values can be hardcoded, rest should be applied at runtime through \Gengo\Config class
- \Gengo\Config is a static class, solely responsible for settings sanity checks
- api_key and private_key should be set through Config::set() prior API calls, passing them to each API client on each invocation is no longer needed
- Removed setBaseUrl() as there only two endpoints for gengo.com , added \Gengo\Config::useProduction() method which must be called prior to all calls to live API
- In PHPUnit's assertEquals() expected value comes first
- Response format is controlled solely through \Gengo\Config::setResponseFormat() method
- Fixed broken Gengo_Jobs->cancel() - digital signature was incorrectly computed
- Added missing Job->archive() method
- Removed Jobs->cancel() as this method is not listed in Gengo API
- Jobs->getJobs() was not really doing what it was ought to do and was mixed with Jobs by ID. Now accepts parameters for filtering.
- Added Jobs->getJobsByID() method as per Gengo API
- Method Service->getLanguagePairs() now supports optional filterin as per Gengo API
- Dropped support for non-"v2" API in Jobs API
- Removed Glossary methods which are not listed in official Gengo API
- Fixed the issue #37: aprroval 'rating' should follow official Gengo API
- Removed support for file_attachments in Jobs->postJobs() as it does not exist in official Gengo API
- All examples now are actual tests
- All Gengo API calls now return responses on API function call
- Switched on HTTP keep-alive: we should keep HTTP connection alive to speed up communication with Gengo server
- 100% test coverage

## [2.1.6] - 28 October 2015
### Changed

- Added support for tone and purpose at the order level
- Updated "postTranslateJobs.php" example file

## [2.1.5] - 17 August 2015
### Changed
- Added new functions(GET, POST) to add comment on order.
- wrote some example files for user.

## [2.1.4] - 18 June 2015
### Changed
- enable revise and reject end-points to receive a $comment paramter
- minor improvements

## [2.1.3] - 23 May 2015
### Changed
- Upgrade Zend library to version 1.12.13
- Bump release version to 2.1.3
