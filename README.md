Country
=======

Country management package.

Idea
----

This package provides an interface for working with countries. All countries always return as an entity that meets the Country interface. The specific implementation of the entity is decided by the package itself according to the available environment.

Possible implementations:

- DoctrineCountry
- FileCountry

The default country data is available in the json data file, according to which the data is subsequently updated.
