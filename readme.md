Paru CMS (in dev)
=======================================
A headless CMS to power small (mostly single user) websites. Paru is designed as a flat-file CMS with Database Servers in mind. With it's REST API it must not be headless at all. With content negotiation it can also serve HTML pages with text/html accept-header later.
Some websites are very small but need an simple system for editing content too, or just requires some sort of a news system. But most CMS require a database server and complicated setups and maintenances (like symfony full stack applications). So the base idea is, to put Paru on a webspace and it works out of the box and you just use the parts of the API you need to fulfill the requirements of your website.


Milestones
=======================================
Base structure
---------------------------------------
* Dependency Injection and Framework Setup
* Data Storage with mime type strategies 
* JWT Authentication

Content Managament 
---------------------------------------
* Possibility to add first pages with content (simple markdown without files)
* Upload files
* Blog API with Tags and Categories
* Compose Content into articles and compose articles into pages


ToDos
=======================================
* Add Logging
* Add exception middleware which maps exceptions to web api errors
* Setup dependency definitions in \Core instead of global definitions
* Create dependency definitions modules
* Implement meaningful exception classes
* Implement Storage get-Interface
* Move class MimeTypeMap to file namespace