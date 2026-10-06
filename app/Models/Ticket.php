<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'customer_name',
        'customer_email',
        'subject',
        'category',
        'priority',
        'status',
        'agent',
        'team',
        'device',
        'location',
        'description',
    ];
}
