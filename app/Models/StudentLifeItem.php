<?php

namespace App\Models;

use App\Models\Concerns\CleansUpFiles;
use Illuminate\Database\Eloquent\Model;

class StudentLifeItem extends Model
{
    use CleansUpFiles;

    protected $guarded = [];

    /** @var string[] */
    public array $fileFields = ['image'];

}

