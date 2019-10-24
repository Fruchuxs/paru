Paru CMS (in dev)
=======================================
A headless CMS to power small (mostly single user) websites. Paru is designed as a flat-file CMS with Database Servers in mind. With it's REST API it must not be headless at all. With content negotiation it can also serve HTML pages with text/html accept-header later.
Some websites are very small but need a simple system for editing content, or just require some sort of news system. But most CMS require a database server, complicated setups and maintenance (like symfony full stack applications). So the base idea is, to put Paru on a webspace and it works out of the box. You can just use the parts of the API you need to fulfill the requirements of your website.


Milestones
=======================================
Base structure
---------------------------------------
* Dependency Injection and Framework Setup
* Data Storage with mime type strategies 
* JWT authentication

Content Managament 
---------------------------------------
* Possibility to add first pages with content (simple markdown without files)
* Upload files
* Blog API with tags and categories
* Compose content into articles and compose articles into pages


ToDo
=======================================
* Add Logging
* Add exception middleware which maps exceptions to web api errors
* Setup dependency definitions in \Core instead of global definitions
* Create dependency definitions modules
* Implement meaningful exception classes
* Implement Storage get-Interface
* Move class MimeTypeMap to file namespace