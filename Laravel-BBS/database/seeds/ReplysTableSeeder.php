<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        $replys = factory(Reply::class)->times(50)->make()->each(function ($reply, $index) {
            if ($index == 0) {
                // $reply->field = 'value';
            }
        });

        Reply::insert($replys->toArray());
    }

}

