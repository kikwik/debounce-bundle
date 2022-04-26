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
and define the return codes (as array) which you consider safe for your application (default is [4,5,7,8])

```yaml
kikwik_debounce:
    api_key: '%env(DEBOUNCE_API_KEY)%'
    safe_codes: [4,5,7,8]
```

create the api from [https://app.debounce.io/api](https://app.debounce.io/api) and copy it in your .env file

```dotenv
DEBOUNCE_API_KEY=xxxxxxxxxxx
```

Usage
-----

Autowire the `Kikwik\DebounceBundle\Service\DebounceInterface` service in your controller and call `check` method:

```php
namespace App\Controller;

use Kikwik\DebounceBundle\Service\DebounceInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/debounce/{email}", name="app_debounce")
     */
    public function debounce($email, DebounceInterface $debounce)
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

or use the `Kikwik\DebounceBundle\Model\DebounceTrait` to generate some fields in the entity:

```php
namespace App\Entity;

use Kikwik\DebounceBundle\Model\DebounceTrait;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    use DebounceTrait;
}
```
Don't forget to make migrations and update your database:

```console
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

Finally call `$user->setDebounceResponse($debounce->check($email));` to save debounce results in the entity

```php
namespace App\Controller;

use Kikwik\DebounceBundle\Service\DebounceInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/debounce/{email}", name="app_debounce")
     */
    public function debounce($email, DebounceInterface $debounce, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $user = $userRepository->findOneByEmail($email);
        if(!$user)
        {
            return $this->createNotFoundException();
        }

        $user->setDebounceResponse($debounce->check($email));
        $entityManager->flush();
        
        if($user->getIsDebounceSafe())
        {
            // safe email!!!
            $debounceCode = $user->getDebounceResponseCode();
            // ...  
        }

        return $this->redirectToRoute('app_home');
    }
}
```

Configure dev/test environment
------------------------------
Add fake_debounce_server route in `config/routes/dev/kikwik_debounce.yaml` and `config/routes/test/kikwik_debounce.yaml`

```yaml        
kikwik_debounce_bundle:
    resource: '@KikwikDebounceBundle/Resources/config/routes_dev_test.xml'
    prefix: '/'
```

Add dev/test configuration in `config/packages/dev/kikwik_debounce.yaml` and `config/packages/test/kikwik_debounce.yaml`

```yaml 
kikwik_debounce:
    api_url: http://concorsiweb2022.sf5.local/_fake_debounce_server
    api_key: xxxxx
```