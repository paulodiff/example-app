<?php

// php artisan test --testsuite=Feature --stop-on-failure
// php artisan test --testsuite=Feature --filter=RainbowTableIndexCheckConfigTest --stop-on-failure

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Faker\Factory as Faker;


use App\RainbowTableIndex\RainbowTableIndexService;



class RainbowTableIndex01CheckConfigTest extends TestCase
{

    // test configuration
    public function test_check_config()
    {

        Log::channel('stderr')->info('CheckConfig:', [] );

        // Test database connection
        Log::channel('stderr')->info('CheckConfig:', ['Checking db connection'] );
        try {
            DB::connection()->getPdo();
            Log::channel('stderr')->info('CheckConfig:', ['db connection OK'] );
        } catch (\Exception $e) {
            Log::channel('stderr')->error('CheckConfig:', ['db connection ERROR!', $e] );
            $this->assertTrue(false);
        }

        Log::channel('stderr')->info('CheckConfig:', ['Creating table categories ...'] );
        if ( !Schema::hasTable('categories'))
        {
            Schema::create('categories', function (Blueprint $table) {
                $table->integer('cat_id')->unsigned()->unique();
                $table->text('description');
                $table->text('description_enc');
                $table->timestamps();
            });
            Log::channel('stderr')->info('CheckConfig:', ['table categories created'] );
        }
        else
        {
            Log::channel('stderr')->info('CheckConfig:', ['table categories already exits'] );
        }

        Log::channel('stderr')->info('CheckConfig:', ['Creating table posts ...'] );
        if ( !Schema::hasTable('posts'))
        {
            Schema::create('posts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('title_enc');  // for test only
                $table->integer('category_id')->unsigned();
                $table->timestamps();
            });
            Log::channel('stderr')->info('CheckConfig:', ['table posts created'] );
        }
        else
        {
            Log::channel('stderr')->info('CheckConfig:', ['table categories already exits'] );
        }

        Log::channel('stderr')->info('CheckConfig:', ['Creating table comments ...'] );
        if ( !Schema::hasTable('comments'))
        {
               Schema::create('comments', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('post_id')->unsigned();
                $table->text('body');
                $table->text('body_enc'); // for test only
                $table->timestamps();
            });
            Log::channel('stderr')->info('CheckConfig:', ['table comments created'] );
        }
        else
        {
            Log::channel('stderr')->info('CheckConfig:', ['table comments already exits'] );
        }

        Log::channel('stderr')->info('CheckConfig:', ['Creating table authors ...'] );
        if ( !Schema::hasTable('authors'))
        {
               Schema::create('authors', function (Blueprint $table) {
                $table->increments('id');
                $table->text('name');
                $table->text('name_enc'); // for test only
                $table->text('card_number');
                $table->text('card_number_enc'); // for test only
                $table->text('address');
                $table->text('address_enc'); // for test only
                $table->text('role');
                $table->text('role_enc'); // for test only
                $table->timestamps();
            });
            Log::channel('stderr')->info('CheckConfig:', ['table authors created'] );
        }
        else
        {
            Log::channel('stderr')->info('CheckConfig:', ['table comments already exits'] );
        }


        Log::channel('stderr')->info('CheckConfig:', ['Checking PHP SODIUM'] );
        try {
            $out = sodium_crypto_generichash('CHECK SODIUM');
            Log::channel('stderr')->info('CheckConfig:', ['SODIUM OK'] );
        } catch (\Exception $e) {
            Log::channel('stderr')->info('CheckConfig:', ['Could not use PHP SODIUM.  Please check your PHP.INI for SODIUM configuration'] );
            // die("Could not use PHP SODIUM.  Please check your PHP.INI for SODIUM configuration" . $e );
            $this->assertTrue(false);
        }

        Log::channel('stderr')->info('CheckConfig:', ['Checking .env Rainbow parameter'] );

        if (config('rainbowtableindex.key') && config('rainbowtableindex.nonce') ) {
            Log::channel('stderr')->info('CheckConfig:', ['Check .env Rainbow parameter OK '] );
        } else {
            Log::channel('stderr')->info('CheckConfig:', ['!ERROR! .env parameters NOT FOUND, check config/rainbowtableindex.php configuration, add this following values to .env and run '] );
            $key = sodium_crypto_secretbox_keygen();
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

            // return  sodium_base642bin(config('rainbowtable.key') , SODIUM_BASE64_VARIANT_ORIGINAL);
            Log::channel('stderr')->info('RAINBOW_TABLE_INDEX_KEY=' . sodium_bin2base64( $key , SODIUM_BASE64_VARIANT_ORIGINAL ), [] );
            Log::channel('stderr')->info('RAINBOW_TABLE_INDEX_NONCE=' . sodium_bin2base64( $nonce , SODIUM_BASE64_VARIANT_ORIGINAL ), [] );
            Log::channel('stderr')->info('RAINBOW_TABLE_INDEX_ENCRYPT=true', ['false only for debugging purpose'] );
            $this->assertTrue(false);
        }

        Log::channel('stderr')->info('CheckConfig:', ['Checking RainbowTableService'] );
        try {
            $rtService = new \App\RainbowTableIndex\RainbowTableIndexService();
            Log::channel('stderr')->info('CheckConfig:', ['Rainbow index creating ...  TAG DEMO'] );
            $rtService->setRT('TEST','DEMO',999);
            Log::channel('stderr')->info('CheckConfig:', ['Rainbow index OK'] );
            Log::channel('stderr')->info('CheckConfig:', ['Check database for table rt_test!!!!!'] );
        } catch (\Exception $e) {
            Log::channel('stderr')->error('CheckConfig:', ['Rainbow ERROR', $e] );
            $this->assertTrue(false);
            // die("ERRORE RainbowTableService re check previuos step!" . $e );
        }

        $this->assertTrue(true);
    }


    // create database model and seed dat


    }
