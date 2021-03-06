<?php
namespace App\GraphQL\Queries;

use Log;
use GraphQL;
use App\Models\BlogCategory;
use App\Models\Blog;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use App\Models\Customer;

class BlogQuery extends Query
{
    protected $attributes = [
        'name' => 'blog query',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('blogType'));
    }

    public function args(): array
    {
        return [
            'blog_id' => ['type' => Type::String()],
            'delete_blog_id' => ['type' => Type::String()],
        ];
    }

    public function resolve($root, $args)
    {
        $blog_model = new Blog();
        $customer_model = new Customer();
        $customer = $customer_model->GetCustomerID();
        if (isset($args['blog_id'])) {
            $blog = $blog_model->DisplayBlogByID(
                $args['blog_id'],
                $customer->id
            );
            return $blog;
        }

        if (isset($args['delete_blog_id'])) {
            $blog = $blog_model->DeleteBlogById(
                $args['delete_blog_id'],
                $customer->id
            );
        }

        $blog = $blog_model->DisplayBlogByCustomerID($customer->id);
        return $blog;
    }
}
?>