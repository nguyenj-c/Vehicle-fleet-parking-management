<?php 

namespace App\UI;


use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class SymfonyMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null !== $envelope->last(ReceivedStamp::class)) {
            // Message just has been received...

        } else {
            // Message was just originally dispatched
        }

        return $stack->next()->handle($envelope, $stack);
    }
}