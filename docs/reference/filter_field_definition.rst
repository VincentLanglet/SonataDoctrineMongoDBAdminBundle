Filter Field Definition
=======================

These fields are displayed inside the filter box. They allow you to filter
the list of entities by a number of different methods.

A filter instance is always linked to a Form Type, there are 3 types available :

  - sonata_type_filter_number  :  display 2 widgets, the operator ( >, >=, <= , <, =) and the value
  - sonata_type_filter_choice  :  display 2 widgets, the operator (yes and no) and the value
  - sonata_type_filter_default :  display 2 widgets, an hidden operator (can be changed on demand) and the value
  - sonata_type_filter_date ( not implemented yet )

The Form Type configuration is provided by the filter itself. But they can be tweaked in the ``configureDatagridFilters``
process with the ``add`` method.

The ``add`` method accepts 5 arguments :

  - the field name
  - the filter type     : the filter name
  - the filter options  : the options related to the filter
  - the field type      : the type of widget used to render the value part
  - the field options   : the type options

Filter types available
----------------------

Some filter types are missing. Contributions are welcome.

  - ``Sonata\DoctrineMongoDBAdminBundle\Filter\BooleanFilter``        : depends on the ``sonata_type_filter_default`` Form Type, renders yes or no field
  - ``Sonata\DoctrineMongoDBAdminBundle\Filter\CallbackFilter``       : depends on the ``sonata_type_filter_default`` Form Type, types can be configured as needed
  - ``Sonata\DoctrineMongoDBAdminBundle\Filter\ChoiceFilter``         : depends on the ``sonata_type_filter_choice`` Form Type, renders yes or no field
  - ``Sonata\DoctrineMongoDBAdminBundle\Filter\ModelFilter``          : depends on the ``sonata_type_filter_number`` Form Type
  - ``Sonata\DoctrineMongoDBAdminBundle\Filter\StringFilter``         : depends on the ``sonata_type_filter_choice``
  - ``Sonata\DoctrineMongoDBAdminBundle\Filter\NumberFilter``         : depends on the ``sonata_type_filter_choice`` Form Type, renders yes or no field

Example
-------

.. code-block:: php

    namespace Sonata\NewsBundle\Admin;

    use Sonata\AdminBundle\Admin\AbstractAdmin;
    use Sonata\AdminBundle\Datagrid\DatagridMapper;

    final class PostAdmin extends AbstractAdmin
    {
        protected function configureDatagridFilters(DatagridMapper $datagriMapper)
        {
            $datagridMapper
                ->add('title')
                ->add('enabled')
                ->add('tags', null, [], null, ['expanded' => true, 'multiple' => true])
            ;
        }
    }

Advanced usage
--------------

Filtering by sub entity properties
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you need to filter your base entities by the value of a sub entity property,
you can use the dot-separated notation (note that this only makes sense
when the prefix path is made of entities, not collections)::

    namespace App\Admin;

    use Sonata\AdminBundle\Admin\AbstractAdmin;
    use Sonata\AdminBundle\Datagrid\DatagridMapper;

    final class UserAdmin extends AbstractAdmin
    {
        protected function configureDatagridFilters(DatagridMapper $datagridMapper)
        {
            $datagridMapper
                ->add('id')
                ->add('firstName')
                ->add('lastName')
                ->add('address.street')
                ->add('address.ZIPCode')
                ->add('address.town')
            ;
        }
    }

Label
^^^^^

You can customize the label which appears on the main widget by using a ``label`` option::

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('tags', null, ['label' => 'les tags'], null, ['expanded' => true, 'multiple' => true]);
    }

Callback
^^^^^^^^

To create a custom callback filter, two methods need to be implemented; one to
define the field type and one to define how to use the field's value. The
latter shall return whether the filter actually is applied to the queryBuilder
or not::

    namespace Sonata\NewsBundle\Admin;

    use Sonata\AdminBundle\Admin\AbstractAdmin;
    use Sonata\AdminBundle\Datagrid\DatagridMapper;
    use Sonata\DoctrineMongoDBAdminBundle\Filter\CallbackFilter;

    use App\Application\Sonata\NewsBundle\Entity\Comment;

    final class PostAdmin extends AbstractAdmin
    {
        protected function configureDatagridFilters(DatagridMapper $datagridMapper)
        {
            $datagridMapper
                ->add('title')
                ->add('enabled')
                ->add('tags', null, [], null, ['expanded' => true, 'multiple' => true])
                ->add('author')
                ->add('finished', CallbackFilter::class', [
                    'callback' => function($queryBuilder, $alias, $field, $value) {
                        if (!$value) {
                            return;
                        }

                        $queryBuilder
                            ->field('end')
                            ->lt(new \DateTime());

                        return true;
                    },
                    'field_type' => 'checkbox',
                ])
            ;
        }
    }
