<?php

namespace App\Shared\Identifier;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Premier\Identifier\Identifier;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use function array_key_exists;
use function dd;
use function dump;
use function is_subclass_of;
use function sprintf;

final class IdentifierMapCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $map = [];

        foreach ($container->get(ManagerRegistry::class)->getManagers() as $manager) {
            /** @var ClassMetadata $metadatum */
            foreach ($manager->getMetadataFactory()->getAllMetadata() as $metadatum) {
                if ($metadatum->isIdentifierComposite) {
                    continue;
                }

                if ($metadatum->isEmbeddedClass) {
                    continue;
                }

                $ref = $metadatum->getReflectionProperty($metadatum->getSingleIdentifierFieldName());
                if (null === $ref) {
                    continue;
                }

                $refType = $ref->getType();
                if (null === $refType) {
                    continue;
                }

                $identifierClass = $refType->getName();
                if (!is_subclass_of($identifierClass, Identifier::class)) {
                    continue;
                }

                $entityClass = $ref->class;

                if (($map[$identifierClass] ?? $entityClass) !== $entityClass) {
                    throw new LogicException(sprintf('%s: %s -> %s', $identifierClass, $map[$identifierClass], $entityClass));
                }

                $map[$identifierClass] = $entityClass;
            }

            dump($map);
        }
    }
}
