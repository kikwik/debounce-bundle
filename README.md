KikwikDebounceBundle
=======================

[https://debounce.io/](https://debounce.io/) integration for symfony 4


Installation
------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require kikwik/debounce-bundle
```

Configuration
-------------

Create the `config/packages/kikwik_debounce.yaml` config file, set the `api_key` parameter

```yaml
kikwik_debounce:
    api_key: '%env(DEBOUNCE_API_KEY)%'
```

create the api from [https://app.debounce.io/api](https://app.debounce.io/api) and copy it in your .env file

```dotenv
DEBOUNCE_API_KEY=xxxxxxxxxxx
```

Usage
-----

Autowire the `Kikwik\DebounceBundle\Service\Debounce` service in your controller and call `check` method:

```php
namespace App\Controller;

use Kikwik\DebounceBundle\Service\Debounce;

class HomeController extends AbstractController
{
    /**
     * @Route("/debounce/{email}", name="app_debounce")
     */
    public function debounce($email, Debounce $debounce)
    {
        $message = '';
        $result = $debounce->check($email);
        if($result['success']==1)
        {
            switch($result['debounce']['code'])
            {
                case '1':
                    $message = 'Syntax, Not an email, Not safe';
                    break;
                case '2':
                    $message = 'Spam Trap, Spam-trap by ESPs, Not safe';
                    break;
                case '3':
                    $message = 'Disposable, A temporary, disposable address, Not safe';
                    break;
                case '4':
                    $message = 'Accept-All, A domain-wide setting, Maybe safe';
                    break;
                case '5':
                    $message = 'Deliverable, Verified as real address, Safe';
                    break;
                case '6':
                    $message = 'Invalid, Verified as invalid (Bounce), Not safe';
                    break;
                case '7':
                    $message = 'Unknown, The server cannot be reached, Not safe';
                    break;
                case '8':
                    $message = 'Role, Role accounts such as info, support, etc, Maybe safe';
                    break;
            }
        }
        else
        {
            $message = $result['debounce']['error'];
        }
        return new Response($message);
    }
}
```