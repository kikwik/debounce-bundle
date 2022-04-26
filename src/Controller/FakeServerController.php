<?php

namespace Kikwik\DebounceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FakeServerController
{
    public function debouceResponse(Request $request)
    {
        $emailToCheck = $request->query->get('email','');
        if(preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/',$emailToCheck))
        {
            if(strpos($emailToCheck,'fake')!==false)
            {
                $data = [
                    'debounce' => [
                        'email'=> $emailToCheck,
                        'code'=> '3',
                        'role'=> 'false',
                        'free_email'=> 'true',
                        'result'=> 'Invalid',
                        'reason'=> 'Disposable',
                        'send_transactional'=> '0',
                        'did_you_mean'=> ''
                    ],
                    'success' => '1',
                    'balance'=>'666',
                ];
            }
            else
            {
                $data = [
                    'debounce' =>[
                        'email'=> $emailToCheck,
                        'code'=> '5',
                        'role'=> 'false',
                        'free_email'=> 'true',
                        'result'=> 'Safe to Send',
                        'reason'=> 'Deliverable',
                        'send_transactional'=> '1',
                        'did_you_mean'=> ''
                    ],
                    'success' => '1',
                    'balance'=>'666',
                ];
            }
        }
        else
        {
            $data = [
                'debounce' => [
                    'email'=> $emailToCheck,
                    'code'=> '1',
                    'role'=> 'false',
                    'free_email'=> 'false',
                    'result'=> 'Invalid',
                    'reason'=> 'Syntax',
                    'send_transactional'=> '0',
                    'did_you_mean'=> ''
                ],
                'success' => '1',
                'balance'=>'666',
            ];
        }


        return new JsonResponse($data);
    }
}