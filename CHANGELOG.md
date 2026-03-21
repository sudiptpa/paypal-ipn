# Changelog

All notable changes to this project will be documented in this file.

## [3.0.0] - 2026-03-21

### Added
- Modern PHP `8.2` to `<8.6` support.
- Zero-hard-dependency runtime architecture.
- Built-in lightweight event dispatcher.
- Built-in cURL transport with optional Guzzle integration.
- Custom transport injection via `Contracts\Service`.
- External dispatcher interoperability via `withDispatcher()`.
- Expanded PHPUnit coverage for payloads, manager flow, dispatcher behavior, and transport validation.
- PHPStan, Rector, lint, and GitHub Actions CI support.
- Architecture and user-guide documentation.

### Changed
- Internal architecture was rewritten from the ground up while preserving the familiar handler flow.
- `ArrayHandler` and `StreamHandler` remain the primary entry points.
- Verification internals now use clearer, modern naming conventions.
- Composer metadata and development tooling were modernized.
- README and docs were rewritten to match the standards used in newer packages.

### Compatibility
- Existing listener-based usage remains the intended upgrade path.
- Guzzle is no longer a hard dependency; projects that want Guzzle should require it directly.
- Symfony Event Dispatcher is no longer a hard dependency; the package now ships with its own dispatcher.

## [2.0.0]

- Upgraded to support Guzzle 6.*

## [1.0.1]

- Allow handling PayPal IPN with InputStreamListener.
- Allow handling PayPal IPN with ArrayListener.
- Support for Guzzle 5.*
