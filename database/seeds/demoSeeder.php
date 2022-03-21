<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class demoSeeder extends BaseSeeder
{
    private $tinycount = 5;
    private $count = 15;
    private $longCount = 30;
    private $longLongCount = 50;
    private $veryLongCount = 150;
    private $now;

    public function __construct()
    {
        $this->now = Carbon::Now();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images_data = [];

        // factory(App\Role::class, $this->tinycount)->create();

        factory(App\User::class, 1)
            ->create([
                'id' => 2,
                'shop_id' => Null,
                'role_id' => \App\Role::ADMIN,
                'nice_name' => 'Admin',
                'name' => 'Admin User',
                'email' => 'admin@demo.com',
                'password' => bcrypt('123456'),
                'active' => 1,
            ])
            ->each(function ($user) {
                $user->dashboard()->save(factory(App\Dashboard::class)->make());

                $user->addresses()->save(
                    factory(App\Address::class)->make(['address_title' => $user->name, 'address_type' => 'Primary'])
                );
            });

        $this->call('VendorsSeeder');

        factory(App\Customer::class, 1)
            ->create([
                'id' => 1,
                'nice_name' => 'Customer',
                'name' => 'Customer Name',
                'email' => 'customer@demo.com',
                'password' => bcrypt('123456'),
                'sex' => 'app.male',
                'active' => 1,
            ])
            ->each(function ($customer) {
                $customer->addresses()->save(factory(App\Address::class)->make(['address_title' => $customer->name, 'address_type' => 'Primary']));
                $customer->addresses()->save(factory(App\Address::class)->make(['address_type' => 'Billing']));
                $customer->addresses()->save(factory(App\Address::class)->make(['address_type' => 'Shipping']));
            });
        factory(App\Customer::class, 2)
            ->create()
            ->each(function ($customer) {
                $customer->addresses()->save(factory(App\Address::class)->make(['address_title' => $customer->name, 'address_type' => 'Primary']));
                $customer->addresses()->save(factory(App\Address::class)->make(['address_type' => 'Billing']));
                $customer->addresses()->save(factory(App\Address::class)->make(['address_type' => 'Shipping']));
            });

        // Demo Categories with real text
        $this->call('CategoryGroupsSeeder');

        $this->call('CategorySubGroupsSeeder');

        // factory(App\CategoryGroup::class, $this->count)->create();

        factory(App\CategorySubGroup::class, $this->count)->create();

        $this->call('CategoriesSeeder');

        factory(App\Category::class, $this->longCount)->create();

        factory(App\Manufacturer::class, $this->count)->create();

        factory(App\Supplier::class, $this->tinycount)
            ->create()
            ->each(function ($supplier) {
                $supplier->addresses()->save(factory(App\Address::class)->make(['address_title' => $supplier->name, 'address_type' => 'Primary']));
            });

        $this->call('ProductsSeeder');

        factory(App\AttributeValue::class, $this->longCount)->create();

        factory(App\Warehouse::class, 1)->create()
            ->each(function ($warehouse) {
                $warehouse->addresses()->save(factory(App\Address::class)->make(['address_title' => $warehouse->name, 'address_type' => 'Primary']));
            });

        $shipping_zones   = \DB::table('shipping_zones')->pluck('id')->toArray();

        foreach ($shipping_zones as $zone) {
            factory(App\ShippingRate::class, $this->tinycount)->create([
                'shipping_zone_id' => $zone,
            ]);
        }

        factory(App\Tax::class, $this->tinycount)->create();

        factory(App\Carrier::class, $this->tinycount)->create();

        factory(App\Packaging::class, $this->tinycount)->create();

        $this->call('InventoriesSeeder');

        factory(App\Order::class, $this->count)->create();

        factory(App\Dispute::class, $this->tinycount)->create();

        $this->call('BlogSeeder');

        factory(App\BlogComment::class, $this->longCount)->create();

        factory(App\Tag::class, $this->longCount)->create();

        // factory(App\GiftCard::class, $this->count)->create();

        factory(App\Coupon::class, $this->count)->create();

        factory(App\Message::class, $this->count)->create();

        factory(App\Ticket::class, $this->tinycount)->create();

        factory(App\Reply::class, $this->longCount)->create();

        //PIVOT TABLE SEEDERS
        $customers  = \DB::table('customers')->pluck('id')->toArray();
        $users      = \DB::table('users')->pluck('id')->toArray();
        $products   = \DB::table('products')->pluck('id')->toArray();
        $shops      = \DB::table('shops')->pluck('id')->toArray();
        $warehouses = \DB::table('warehouses')->pluck('id')->toArray();
        $categories = \DB::table('categories')->pluck('id')->toArray();
        $category_sub_groups = \DB::table('category_sub_groups')->pluck('id')->toArray();
        $attributes   = \DB::table('attributes')->pluck('id')->toArray();
        $coupons   = \DB::table('coupons')->pluck('id')->toArray();
        $inventories_ids = \DB::table('inventories')->pluck('id')->toArray();
        $manufacturers = \DB::table('manufacturers')->pluck('id')->toArray();

        // shop_payment_methods
        $wire = \DB::table('payment_methods')->where('code', 'wire')->first()->id;
        $cod = \DB::table('payment_methods')->where('code', 'cod')->first()->id;
        $shop_payment_methods = [];
        $config_manual_payments = [];
        foreach ($shops as $shop) {
            $shop_payment_methods[] = [
                'shop_id' => $shop,
                'payment_method_id' => $cod,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];
            $shop_payment_methods[] = [
                'shop_id' => $shop,
                'payment_method_id' => $wire,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];

            $config_manual_payments[] = [
                'shop_id' => $shop,
                'payment_method_id' => $wire,
                'additional_details' => 'Send the payment via Bank Wire Transfer.',
                'payment_instructions' => 'Payment instructions for Bank Wire Transfer',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];
            $config_manual_payments[] = [
                'shop_id' => $shop,
                'payment_method_id' => $cod,
                'additional_details' => 'Our man will collect the payment when deliver the item to your doorstep.',
                'payment_instructions' => 'Payment instructions for COD',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];
        }
        DB::table('shop_payment_methods')->insert($shop_payment_methods);
        DB::table('config_manual_payments')->insert($config_manual_payments);

        // attribute_inventory
        $attribute_inventory = [];
        foreach ((range(1, $this->longCount)) as $index) {
            $attribute_id = $attributes[array_rand($attributes)];
            $attribute_values = \DB::table('attribute_values')->where('attribute_id', $attribute_id)->pluck('id')->toArray();
            if (empty($attribute_values)) continue;

            $attribute_inventory[] = [
                'attribute_id' => $attribute_id,
                'inventory_id' => $inventories_ids[array_rand($inventories_ids)],
                'attribute_value_id' => $attribute_values[array_rand($attribute_values)],
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];
        }
        DB::table('attribute_inventory')->insert($attribute_inventory);

        // order_items
        $orders = DB::table('orders')->get();

        $order_items = [];
        foreach ($orders as $order) {
            $inventories = \DB::table('inventories')->where('shop_id', $order->shop_id)->get()->toArray();
            $shipping_weight = 0;
            $item_count = 0;
            $shipping_qtt = 0;
            $total = 0;

            $temps = array_rand($inventories, rand(2, 4));

            foreach ($temps as $temp) {
                $qtt = rand(1, 3);
                $order_items[] = [
                    'order_id' => $order->id,
                    'inventory_id' => $inventories[$temp]->id,
                    'item_description' => $inventories[$temp]->title . ' - ' . $inventories[$temp]->condition,
                    'quantity' => $qtt,
                    'unit_price' => $inventories[$temp]->sale_price,
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ];

                $item_count++;
                $shipping_qtt += $qtt;
                $shipping_weight += $inventories[$temp]->shipping_weight * $qtt;
                $total += $inventories[$temp]->sale_price * $qtt;
            }

            $shipping = rand(1, 9);
            // Update order with correct qtt and total
            DB::table('orders')->where('id', $order->id)->update([
                'item_count' => $item_count,
                'quantity' => $shipping_qtt,
                'shipping_weight' => $shipping_weight,
                'shipping' => $shipping,
                'total' => $total,
                'grand_total' => $shipping + $total,
            ]);
        }
        DB::table('order_items')->insert($order_items);

        // Blog tags
        $data = [];
        $blogs  = \DB::table('blogs')->pluck('id')->toArray();
        $tags   = \DB::table('tags')->pluck('id')->toArray();
        foreach ($blogs as $blog) {
            $z = rand(1, 7);
            for ($i = 1; $i <= $z; $i++) {
                $data[] = [
                    'tag_id' => $tags[array_rand($tags)],
                    'taggable_id' => $blog,
                    'taggable_type' => 'App\Blog',
                ];
            }
        }
        DB::table('taggables')->insert($data);

        // category_product
        $data = [];
        foreach ($products as $product) {
            for ($i = 0; $i <= rand(2, 4); $i++) {
                $data[] = [
                    'category_id' => $categories[array_rand($categories)],
                    'product_id' => $product,
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ];
            }
        }
        DB::table('category_product')->insert($data);

        // user_warehouse
        // foreach ((range(1, $this->longCount)) as $index) {
        //     DB::table('user_warehouse')->insert(
        //         [
        //             'warehouse_id' => $warehouses[array_rand($warehouses)],
        //             'user_id' => $users[array_rand($users)],
        //             'created_at' => $this->now,
        //             'updated_at' => $this->now,
        //         ]
        //     );
        // }

        // foreach ((range(1, 30)) as $index) {
        //     DB::table('taggables')->insert(
        //         [
        //             'tag_id' => rand(1, 20),
        //             'taggable_id' => rand(1, 20),
        //             'taggable_type' => rand(0, 1) == 1 ? 'App\Post' : 'App\Video'
        //         ]
        //     );
        // }

        // coupon_customers
        foreach ((range(1, $this->count)) as $index) {
            DB::table('coupon_customer')->insert(
                [
                    'coupon_id' => $coupons[array_rand($coupons)],
                    'customer_id' => $customers[array_rand($customers)],
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ]
            );
        }

        // Frontend Seeder

        $this->call('SlidersSeeder');

        $this->call('BannersSeeder');

        factory(App\Wishlist::class, $this->count)->create();
        factory(App\Feedback::class, $this->longCount)->create();

        $this->call('EmailTemplateSeeder');

        // announcement seeder
        $deals = ['**Deal** of the day', 'Fashion accessories **deals**', 'Kids item **deals**', 'Black Friday **Deals**!', 'ONLY FOR TODAY:: **80% Off!**', 'Everyday essentials **deals**', '**Save** up to 40%', '**FLASH SALE ::** 20% **Discount** only for TODAY!!!'];
        DB::table('announcements')->insert(
            [
                'id' => '9e274a6b-1340-4862-8ca2-525331830725',
                'user_id' => 1,
                'body' => $deals[array_rand($deals)],
                'action_text' => 'Shop Now',
                'action_url' => '/',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]
        );

        factory(App\Visitor::class, $this->longCount)->create();

        // options table seeder

        $taglines = ['40% off on kids item only', 'Free shipping!', 'Black Friday Offer!', '50% OFF ONLY FOR TODAY', 'Everyday essentials deals', 'Save up to 40%', 'FLASH SALE 40% Discount!'];

        $max_qtt_item = \DB::table('inventories')->orderBy('stock_quantity', 'desc')->first();

        DB::table('options')->insert([
            [
                'option_name' => 'trending_categories',
                'option_value' => serialize($this->array_random($categories, 3)),
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ], [
                'option_name' => 'promotional_tagline',
                'option_value' => serialize(['text' => $taglines[array_rand($taglines)], 'action_url' => '/']),
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ], [
                'option_name' => 'featured_items',
                'option_value' => serialize($this->array_random($inventories_ids, 10)),
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ], [
                'option_name' => 'featured_brands',
                'option_value' => serialize($this->array_random($manufacturers, 3)),
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ], [
                'option_name' => 'deal_of_the_day',
                'option_value' => $max_qtt_item->id,
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]
        ]);

        // Insert all images at once
        if (count($images_data) > 0) {
            DB::table('images')->insert($images_data);
        }

        $this->call('PostDemoSeeder');
    }
}
