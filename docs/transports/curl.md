# Curl Transport

The package ships with a built-in cURL transport.

It is selected automatically when:

- no custom transport has been injected
- no custom service has been injected
- `ext-curl` is installed

## Advantages

- zero extra package dependency
- suitable for small and framework-agnostic integrations
- good default for legacy projects upgrading in place

## Requirement

Make sure the PHP cURL extension is installed in the environment.
