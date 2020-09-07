<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Sonata\DoctrineMongoDBAdminBundle\Tests\App\Admin\CategoryAdmin;
use Sonata\DoctrineMongoDBAdminBundle\Tests\App\Document\Category;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->services()

        ->set(CategoryAdmin::class)
        ->public()
        ->tag('sonata.admin', [
            'manager_type' => 'doctrine_mongodb',
        ])
        ->args([
            '',
            Category::class,
            null,
        ]);
};