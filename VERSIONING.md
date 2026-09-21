# Versioning

## Basic Relation to VuFind-Versions
Since this module is only usable together with VuFind, we need to make it clear with which VuFind version the current module version will be compatible.
Of course we could define our own independant versioning scheme (starting with 1.0.0, then following basic rules of semantic versioning), and define a mapping which of our releases is compatible to which VuFind Version.

**BUT** - assume the following situation:
- We create a first release 1.0.0, which is compatible with VuFind 10.2
- We create a second release 1.1.0 (maybe 2.0.0), which is compatible with VuFind 11.0
- Now we want to release backports for VuFind 9.0 and 9.1 - which version would that be? (0.9.1 and 0.9.0? How to indicate updates within that version?)

That's why we maintain multiple release branches that are basically similar to the VuFind version.
- e.g. release-10.2 is compatible with VuFind 10.2
- e.g. release-11.0 is compatible with VuFind 11.0

Usually these release branches also contain special CI workflows, which will test compatibility against the specific PHP version range (e.g. 8.2...8.4) and other dependencies of the specific VuFind version to avoid any conflicts.

We need to stick with a semantic versioning scheme that contains of 3 parts and is compatible with composer:
- e.g. 10.2.X is compatible with VuFind 10.2
- e.g. 11.0.X is compatible with VuFind 11.0

Please be careful and always use the exact version in your composer.local.json, e.g. "11.0.0" (and not "^11.0.0"), since 11.0.1 might contain changes that are not backwards compatible.
(We discussed using a versioning scheme with additional parts like e.g. 11.0-1.0, but this does not seem to be supported by composer).
