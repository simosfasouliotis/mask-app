<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;

/**
 * Class Customer
 *
 * @package App\Models
 */
class Customer extends Fluent
{
    public string $name;

    public string $tel;

    public string $mail;
}
