# Custom Transport

Any custom transport only needs to implement `Sujip\PayPal\Notification\Contracts\Service`.

```php
use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Http\Response;
use Sujip\PayPal\Notification\Payload;

final class CustomTransport implements Service
{
    public function call(Payload $payload): Response
    {
        // Map your HTTP client response into the package Response object.
        return new Response('VERIFIED', 200);
    }
}
```

Attach it:

```php
$manager = (new ArrayHandler($_POST))
    ->using(new CustomTransport())
    ->handle();
```
