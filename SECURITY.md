# Security Policy

## Reporting A Vulnerability

If you believe you have found a security issue in this package, please do not open a public issue first.

Please report it privately to:

- `sudiptpa@gmail.com`

Include the following where possible:

- affected version
- clear reproduction steps
- impact summary
- any suggested mitigation

## Scope Notes

This package focuses on PayPal IPN verification transport and dispatch behavior.

Application-level payment safety is still the responsibility of the integrating project. Even with a verified IPN response, applications should still validate:

- receiver identity
- transaction amount and currency
- payment status
- duplicate transaction handling
- fulfillment idempotency
