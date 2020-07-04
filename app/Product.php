<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Uuid, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'category', 'price', 'stock',
    ];

    protected $dates = ['deleted_at'];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            if (isset($search->name))
                $query->orWhere('name', 'like', '%'.$search->name.'%');
            if (isset($search->description))
                $query->orWhere('description', 'like', '%'.$search->description.'%');
            if (isset($search->category))
                $query->orWhere('category', 'like', '%'.$search->category.'%');
        });
    }

    public function scopeGroup($query, $search)
    {
        if(isset($request->name))
            $query->groupBy('name')->where('name', 'like',"%$request->name%");
        if(isset($request->description))
            $query->groupBy('description')->where('description', 'like', '%'.$search->description.'%');
        if(isset($request->category))
            $query->groupBy('category')->where('category', 'like', '%'.$search->category.'%');

        return $query;
    }
}
