# Contributing

## Goals

This package aims to modernize the internal architecture of a legacy PayPal IPN library without breaking the familiar listener-based workflow used by existing projects.

When contributing, optimize for:

- backward-compatible behavior
- framework-agnostic design
- minimal hard dependencies
- readable internal naming
- clear upgrade paths for existing users

## Local Setup

```bash
composer update --prefer-dist --no-interaction
```

## QA Commands

```bash
composer lint
composer stan
composer rector:check
composer test
composer test:coverage
```

Coverage requires a local coverage driver such as `xdebug` or `pcov`.

## Contribution Guidelines

- Keep public behavior stable unless the change is clearly documented as a breaking change.
- Prefer additive extension points over forcing a new integration model.
- Avoid introducing new hard runtime dependencies unless there is a very strong reason.
- Keep docs updated whenever public usage, supported transports, or compatibility expectations change.
- Add or update tests for every behavior change.

## Public Compatibility Expectations

These areas should be treated as user-facing API unless explicitly documented otherwise:

- `ArrayHandler`
- `StreamHandler`
- `Manager`
- verification events
- `Contracts\Service`
- listener registration methods and event names

Internal implementation classes may evolve more freely, especially where compatibility wrappers already exist.

## Pull Requests

A good change usually includes:

- focused code changes
- updated tests
- updated docs when relevant
- changelog entry for user-visible behavior
