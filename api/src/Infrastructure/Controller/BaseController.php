<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Domain\Common\EntityNotFoundException;
use App\Domain\User\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Messenger\Envelope;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
abstract class BaseController extends AbstractController
{
    protected function validate(
        object $object,
        bool $throw = true,
        $groups = null
    ): ConstraintViolationListInterface {
        /** @var ValidatorInterface $validator */
        $validator = $this->container->get('validator');

        $errors = $validator->validate($object, null, $groups);
        if ($throw && $errors->count()) {
            throw new ValidationFailedException($object, $errors);
        }

        return $errors;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function denormalize(array $data, string $type, array $context = []): object {
        return $this->container->get('denormalizer')->denormalize($data, $type, $context);
    }

    /**
     * @throws EntityNotFoundException
     *
     * @return User
     */
    protected function getUser(): UserInterface
    {
        $user = parent::getUser();
        if (!$user instanceof UserInterface) {
            throw new EntityNotFoundException('Anonymous authentication, no user model');
        }

        return $user;
    }

    /**
     * Dispatches a message to the bus.
     *
     * @param object $message The message or the message pre-wrapped in an envelope
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function dispatchMessage(object $message, array $stamps = []): Envelope
    {
        return $this->container->get('messenger.default_bus')->dispatch($message, $stamps);
    }

    public static function getSubscribedServices(): array
    {
        return [
            'router' => '?'.RouterInterface::class,
            'request_stack' => '?'.RequestStack::class,
            //'http_kernel' => '?'.HttpKernelInterface::class,
            'serializer' => '?'.SerializerInterface::class,
            //'session' => '?'.SessionInterface::class,
            'security.authorization_checker' => '?'.AuthorizationCheckerInterface::class,
            //'twig' => '?'.Environment::class,
            //'doctrine' => '?'.ManagerRegistry::class,
            'form.factory' => '?'.FormFactoryInterface::class,
            'security.token_storage' => '?'.TokenStorageInterface::class,
            //'security.csrf.token_manager' => '?'.CsrfTokenManagerInterface::class,
            'parameter_bag' => '?'.ContainerBagInterface::class,
            'message_bus' => '?'.MessageBusInterface::class,
            'messenger.default_bus' => '?'.MessageBusInterface::class,
            'validator' => '?'.ValidatorInterface::class,
            'denormalizer' => '?'.DenormalizerInterface::class,
        ];
    }
}
