<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        DB::listen(function(QueryExecuted $query) {
            $sql=vsprintf(str_replace("?", "'%s'", $query->sql), $query->bindings);
            Log::channel('sqllog')->info($sql.'---'.$query->time);
            Log::channel(env('LOG_CHANNEL', 'stack'))->info((new \Exception())->getTraceAsString());
        });

//        DB::listen(function (QueryExecuted $query) {
//            $sql      = $query->sql;
//            $bindings = $query->bindings;
//            $time     = $query->time;
//
//            Log::debug(json_encode($query));
//            Log::debug(var_export(compact('sql','bindings','time'),true));
//        });

//        DB::listen(function (QueryExecuted $sql) {
//            Log::info($sql->sql);
//            Log::info((new \Exception())->getTraceAsString());
//        });
    }
}
