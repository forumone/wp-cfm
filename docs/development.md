# Developing

These instructions will help you set up a development environment for working on the woocart-defaults source code.

## Prerequisites

To compile and test woocart-defaults, you will need:

- make
- git
- PHP7.2
- composer

In most cases, install each prerequisite according to its instructions.

## Setup

After you have setup Golang runtime and SDK, you need to setup root of the project,
the GOPATH environment variable which must be in the root of the project.

```shell
$ git clone git@github.com:woocart/woocart-defaults.git
$ make ensure
```

## Making changes

Start by creating new branch prefixed with `feature/short-name` or `docs/short-name` or `cleanup/short-name`, depending on the change you are working on.

Run `make ensure` to install project dependencies.

### Test your changes

Run `make tests` to ensure the project is developed with best practices in mind.

## Making a release

The version is injected into the app at compilation time, where version is same one as defined in Makefile. Actual compilation happens in CI when master branch is tagged with version.

1. Change `VERSION` in Makefile according to [Semver](https://semver.org/) rules.
2. Run `make release` which will do all necessary steps.

After CI built the project, zip file is uploaded to Release in GitHub.

## Backward compatibility

woocart-defaults maintains a strong commitment to backward compatibility. All of our changes to protocols and formats are backward compatible. No features, flags, or commands are removed or substantially modified (other than bug fixes).

We also try very hard to not change publicly accessible Go library definitions inside of the src/ directory of our source code, but it is not guaranteed to be backward compatible as we move the project forward.

For a quick summary of our backward compatibility guidelines for major releases:

- Command line commands, flags, and arguments MUST be backward compatible
- REST API definitions MUST be backward compatible

Other changes are permissable according to [Semver](https://semver.org/).