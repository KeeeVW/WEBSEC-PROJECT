<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'code' => 'TV01',
                'name' => 'LG TV 50 Insh',
                'price' => 28000,
                'model' => 'LG8768787',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'lgtv50.jpg',
                'created_at' => null,
                'updated_at' => '2025-02-25 06:40:56',
                'deleted_at' => null
            ],
            [
                'code' => 'RF01',
                'name' => 'Toshipa Refrigerator 14"',
                'price' => 22000,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'tsrf50.jpg',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'code' => 'RF02',
                'name' => 'Toshipa Refrigerator 18"',
                'price' => 28000,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'rf2.jpg',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'code' => 'RF03',
                'name' => 'Toshipa Refrigerator 19"',
                'price' => 32000,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'rf3.jpg',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'code' => 'TV02',
                'name' => 'LG TV 55"',
                'price' => 23000,
                'model' => 'LG8768787',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'tv2.jpg',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'code' => 'RF04',
                'name' => 'LG Refrigerator 14"',
                'price' => 22000,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'rf4.jpg',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'code' => 'TV03',
                'name' => 'LG TV 60"',
                'price' => 44000,
                'model' => 'LG8768787',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'tv3.jpg',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'code' => 'RF05',
                'name' => 'Toshipa Refrigerator 12"',
                'price' => 10700,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'rf5.jpg',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'code' => 'TV04',
                'name' => 'LG TV 99"',
                'price' => 108000,
                'model' => 'LG8768787',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'tv4.jpg',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'code' => 'RF05',
                'name' => 'Toshipa Refrigerator 19"',
                'price' => 44000,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'rf4.jpg',
                'created_at' => '2025-02-25 03:18:04',
                'updated_at' => '2025-02-25 03:18:04',
                'deleted_at' => null
            ],
            [
                'code' => 'TV01',
                'name' => 'LG TV 50"',
                'price' => 18000,
                'model' => 'LG8768787',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'lgtv50.jpg',
                'created_at' => '2025-02-25 03:24:04',
                'updated_at' => '2025-02-25 03:24:04',
                'deleted_at' => null
            ]
        ];

        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
} 