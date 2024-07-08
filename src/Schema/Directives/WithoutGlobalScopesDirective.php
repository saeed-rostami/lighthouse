<?php declare(strict_types=1);

namespace Nuwave\Lighthouse\Schema\Directives;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Support\Contracts\ArgBuilderDirective;

final class WithoutGlobalScopesDirective extends BaseDirective implements ArgBuilderDirective
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Omit any number of global scopes to the query builder.
"""
directive @withoutGlobalScopes(
"""
The method will receive the client-given value of the argument as the second parameter.

The names of the global scopes to omit.
  """
names: [String!]
) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
GRAPHQL;
    }

    public function handleBuilder(QueryBuilder|EloquentBuilder|Relation $builder, mixed $value): QueryBuilder|EloquentBuilder|Relation
    {
        if (!$value) {
            return $builder;
        }
        $scopes = $this->directiveArgValue('names', $this->nodeName());

        return $builder->withoutGlobalScopes($scopes);

    }
}
