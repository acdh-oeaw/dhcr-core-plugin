# DHCR Core Plugin
This plugin provides the shared codebase between the DHCR main app (front-end) and the API. It contains model classes for data access and the according unit tests.
Maintaining test coverage for the model methods is important for the reliability of the API.
The plugin is imported as a submodule into the main projects.

For testing, the plugin ships it's own testing dependencies.
Integrating the plugin tests into the main app phpunit.xml.dist won't work!
Hence, the plugin is using an own test configuration.

