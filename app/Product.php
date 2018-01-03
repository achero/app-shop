<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	//product->category
	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	//product->images
	public function images()
	{
		return $this->hasMany(ProductImage::class);
	}

	public function getFeaturedImageUrlAttribute()
	{
		//obtener la imagen asociada
		$featuredImage = $this->images()->where('featured', true)->first();
		//en el caso que el producto no contenga la imagen destacada
		if(!$featuredImage){
			$featuredImage = $this->images()->first();
		}

		// si hemos encontrado al menos una imagen asociada al producto
		if($featuredImage){
			return $featuredImage->url;
		}

		//default
		return '/images/products/default.png';

	}

}
