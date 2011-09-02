Fixtures
========

Efficient fixtures framework.

Basics
------

All the library is articulated around the **FixtureManager** so the use
of the library is a two-steps process.

### Configure the manager

As this is the least funny part, it is automated as much as possible.

### Use the manager

This is the funniest part - well, it comes to fixtures eh ;) - as this is
where you can start dealing with your fixtures.

How it works
------------

### The Storages

### The Factories

#### The ValueProvider

The value provider is passed as first parameter of the `->create()`method
of the factories. Its main role is to let you retrieve the data provided
by the user.

### The Sequences
